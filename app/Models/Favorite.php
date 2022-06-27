<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favorite extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'favorites';

    protected $fillable = ['user_id', 'subCategory_id'];
    protected $dates = ['deleted_at'];

    //////////////////////////////////////// Relation //////////////////////////////////////

    public function subCategory(){
        return $this->belongsTo(SubCategory::class,'subCategory_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}
