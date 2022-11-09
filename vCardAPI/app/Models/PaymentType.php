<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentType extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $primaryKey = 'code';
    public $incrementing = false;

    protected $fillable = [
        'code',
        'name',
        'description',
        'validation_rules',
        'custom_options',
        'custom_data',
    ];

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction','payment_type','code');
    }
}
