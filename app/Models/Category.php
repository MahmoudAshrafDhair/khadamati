<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations,SoftDeletes;

    protected $table = 'categories';

    public $translatable = ['name'];

    protected $fillable = ['name', 'image'];
    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($category){
            $category->subCategories()->delete();
        });
    }

    //////////////////////////////////////// Relation //////////////////////////////////////

    public function subCategories(){
        return $this->hasMany(SubCategory::class,'category_id','id');
    }

    //////////////////////////////////////// HTML Datatable //////////////////////////////////////

    public function getActionButtonsAttribute()
    {
        $button = '';
//        if (auth('admin')->user()->can('edit city')) {
        $button .= '<a href="' . route('admin.categories.edit', $this->id) . '" class="btn btn-icon btn-xs btn-info"><i class="flaticon2-edit"></i></a>';
//        }
//        if (auth('admin')->user()->can('delete city')) {
        $button .= '&nbsp;&nbsp;<button  title="Delete Category" type="button" data-id="' . $this->id . '" data-name="' . $this->name . '" data-toggle="modal" data-target="#deleteModel" class="btn btn-icon btn-xs btn-danger delete-item"><i class="flaticon2-trash"></i></button>';
//        }
        return $button;
    }

}
