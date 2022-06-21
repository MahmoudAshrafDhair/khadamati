<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Worker extends Model
{
    use HasFactory,HasApiTokens,Notifiable,SoftDeletes;

    protected $table = 'workers';

    protected $fillable = [
        'username',
        'email',
        'password',
        'phone',
        'code',
        'active',
        'city_id',
        'fcm',
        'subCategory_id',
        'image'
    ];
    protected $dates = ['deleted_at'];

    //////////////////////////////////////// Relation //////////////////////////////////////

    public function city(){
        return $this->belongsTo(City::class,'city_id','id');
    }

    public function subCategory(){
        return $this->belongsTo(SubCategory::class,'subCategory_id','id');
    }


    //////////////////////////////////////// HTML Datatable //////////////////////////////////////

    public function getActionButtonsAttribute()
    {
        $button = '';
//        if (auth('admin')->user()->can('edit city')) {
        $button .= '<a href="' . route('admin.workers.edit', $this->id) . '" class="btn btn-icon btn-xs btn-info"><i class="flaticon2-edit"></i></a>';
//        }
//        if (auth('admin')->user()->can('delete city')) {
        $button .= '&nbsp;&nbsp;<button  title="Delete Worker" type="button" data-id="' . $this->id . '" data-name="' . $this->name . '" data-toggle="modal" data-target="#deleteModel" class="btn btn-icon btn-xs btn-danger delete-item"><i class="flaticon2-trash"></i></button>';
//        }
        return $button;
    }
}
