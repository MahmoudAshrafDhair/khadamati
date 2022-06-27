<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasFactory, HasTranslations;
    protected $table = 'settings';
    public $translatable = ['Terms_and_Conditions','privacy_policy'];
    protected $fillable = [
        'Terms_and_Conditions',
        'privacy_policy',
    ];
}
