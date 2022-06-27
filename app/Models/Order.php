<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'orders';
    protected $fillable = [
        'subCategory_id',
        'user_id',
        'worker_id',
        'time_type',
        'longitude',
        'latitude',
        'image',
        'description',
        'date',
        'is_completed',
        'type',
    ];
    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($order){
            $order->rates()->delete();
        });
    }

    //////////////////////////////////////// Relation //////////////////////////////////////

    public function subCategories(){
        return $this->belongsTo(SubCategory::class,'subCategory_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function worker(){
        return $this->belongsTo(Worker::class,'worker_id','id');
    }

    public function rates(){
        return $this->hasMany(Rate::class,'order_id','id');
    }

    //////////////////////////////////////// HTML Datatable //////////////////////////////////////

    public function getActionButtonsAttribute()
    {
        $button = '';
//        if (auth('admin')->user()->can('edit city')) {
        $button .= '<a href="' . route('admin.orders.show', $this->id) . '" class="btn btn-icon btn-xs btn-info"><i class="flaticon-eye"></i></a>';
//        }
//        if (auth('admin')->user()->can('delete city')) {
        $button .= '&nbsp;&nbsp;<button  title="Delete Category" type="button" data-id="' . $this->id . '" data-name="' . $this->name . '" data-toggle="modal" data-target="#deleteModel" class="btn btn-icon btn-xs btn-danger delete-item"><i class="flaticon2-trash"></i></button>';
//        }
        return $button;
    }

    public function getStatesAttribute(){
        if($this->type == 1){
           return '<span class="label label-light-info label-inline font-weight-bold">' . __('order.pending') . '</span>';
        }elseif ($this->type == 2){
            return '<span class="label label-light-primary label-inline font-weight-bold">' . __('order.pending') . '</span>';
        }elseif ($this->type == 3){
            return '<span class="label label-light-danger label-inline font-weight-bold">' . __('order.pending') . '</span>';
        }else{
            return '<span class="label label-light-success label-inline font-weight-bold">' . __('order.pending') . '</span>';
        }
    }
}
