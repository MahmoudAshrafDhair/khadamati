<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class SubCategory extends Model
{
    use HasFactory, HasTranslations,SoftDeletes;

    protected $table = 'sub_categories';

    public $translatable = ['name'];

    protected $fillable = ['name', 'image','category_id','order_total'];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($subCategory){
            $subCategory->workers()->delete();
            $subCategory->orders()->delete();
        });
    }

    //////////////////////////////////////// Relation //////////////////////////////////////

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function workers(){
        return $this->hasMany(Worker::class,'subCategory_id','id');
    }

    public function orders(){
        return $this->hasMany(Order::class,'subCategory_id','id');
    }

    //////////////////////////////////////// HTML Datatable //////////////////////////////////////

    public function getActionButtonsAttribute()
    {
        $button = '';
//        if (auth('admin')->user()->can('edit city')) {
        $button .= '<a href="' . route('admin.sub.categories.edit', $this->id) . '" class="btn btn-icon btn-xs btn-info"><i class="flaticon2-edit"></i></a>';
//        }
//        if (auth('admin')->user()->can('delete city')) {
        $button .= '&nbsp;&nbsp;<button  title="Delete Sub Category" type="button" data-id="' . $this->id . '" data-name="' . $this->name . '" data-toggle="modal" data-target="#deleteModel" class="btn btn-icon btn-xs btn-danger delete-item"><i class="flaticon2-trash"></i></button>';
//        }
        return $button;
    }
}
