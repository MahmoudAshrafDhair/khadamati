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
        'image',
        'forget_status',
        'forget_code',
    ];
    protected $dates = ['deleted_at'];

    protected $appends = ['rate_average'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($worker){
            $worker->orders()->delete();
            $worker->rates()->delete();
            $worker->appointments()->delete();
        });
    }

    //////////////////////////////////////// Relation //////////////////////////////////////

    public function city(){
        return $this->belongsTo(City::class,'city_id','id');
    }

    public function subCategory(){
        return $this->belongsTo(SubCategory::class,'subCategory_id','id');
    }

    public function orders(){
        return $this->hasMany(Order::class,'worker_id','id');
    }

    public function rates(){
        return $this->hasMany(Rate::class,'worker_id','id');
    }

    public function appointments(){
        return $this->hasMany(Appointment::class,'worker_id','id');
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

    public function getRateAverageAttribute()
    {
        $rate_av = Rate::query()->where('worker_id', $this->id)->avg('rating');
        return (int)$rate_av ?? 0;
    }
}
