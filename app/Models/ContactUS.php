<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUS extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'contact_u_s';

    protected $fillable = ['name','email','message','type'];

    protected $dates = ['deleted_at'];

    //////////////////////////////////////// HTML Datatable //////////////////////////////////////

    public function getActionButtonsUserAttribute()
    {
        $button = '';
//        if (auth('admin')->user()->can('edit city')) {
        $button .= '<a href="' . route('admin.contact.show.user', $this->id) . '" class="btn btn-icon btn-xs btn-info"><i class="flaticon-eye"></i></a>';
//        }
//        if (auth('admin')->user()->can('delete city')) {
        $button .= '&nbsp;&nbsp;<button  title="Delete Contact" type="button" data-id="' . $this->id . '" data-name="' . $this->name . '" data-toggle="modal" data-target="#deleteModel" class="btn btn-icon btn-xs btn-danger delete-item"><i class="flaticon2-trash"></i></button>';
//        }
        return $button;
    }

    public function getActionButtonsWorkerAttribute()
    {
        $button = '';
//        if (auth('admin')->user()->can('edit city')) {
        $button .= '<a href="' . route('admin.contact.show.worker', $this->id) . '" class="btn btn-icon btn-xs btn-info"><i class="flaticon-eye"></i></a>';
//        }
//        if (auth('admin')->user()->can('delete city')) {
        $button .= '&nbsp;&nbsp;<button  title="Delete Contact" type="button" data-id="' . $this->id . '" data-name="' . $this->name . '" data-toggle="modal" data-target="#deleteModel" class="btn btn-icon btn-xs btn-danger delete-item"><i class="flaticon2-trash"></i></button>';
//        }
        return $button;
    }

}
