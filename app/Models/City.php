<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasFactory, HasTranslations,SoftDeletes;

    protected $table = 'cities';

    public $translatable = ['name'];

    protected $fillable = ['name'];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($city){
            $city->users()->delete();
        });
    }

    //////////////////////////////////////// Relation //////////////////////////////////////
    public function users(){
        return $this->hasMany(User::class,'city_id','id');
    }

    public function workers(){
        return $this->hasMany(Worker::class,'city_id','id');
    }


//////////////////////////////////////// HTML Datatable //////////////////////////////////////

    public function getActionButtonsAttribute()
    {
        $button = '';
//        if (auth('admin')->user()->can('edit city')) {
            $button .= '<a href="' . route('admin.cities.edit', $this->id) . '" class="btn btn-icon btn-xs btn-info"><i class="flaticon2-edit"></i></a>';
//        }
//        if (auth('admin')->user()->can('delete city')) {
            $button .= '&nbsp;&nbsp;<button  title="Delete Country" type="button" data-id="' . $this->id . '" data-name="' . $this->name . '" data-toggle="modal" data-target="#deleteModel" class="btn btn-icon btn-xs btn-danger delete-item"><i class="flaticon2-trash"></i></button>';
//        }
        return $button;
    }
}
