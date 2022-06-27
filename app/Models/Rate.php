<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'rates';

    protected $fillable = [
        'order_id',
        'user_id',
        'worker_id',
        'rating',
        'description',
    ];
    protected $dates = ['deleted_at'];

    //////////////////////////////////////// Relation //////////////////////////////////////

    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function worker(){
        return $this->belongsTo(Worker::class,'worker_id','id');
    }


}
