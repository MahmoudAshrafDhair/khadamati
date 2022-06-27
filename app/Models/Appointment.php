<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'appointments';

    protected $fillable = ['worker_id', 'day_id','start','end'];
    protected $dates = ['deleted_at'];

    //////////////////////////////////////// Relation //////////////////////////////////////

    public function worker(){
        return $this->belongsTo(Worker::class,'worker_id','id');
    }
    public function day(){
        return $this->belongsTo(Day::class,'day_id','id');
    }
}
