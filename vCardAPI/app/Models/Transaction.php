<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'vcard',
        'date',
        'datetime',
        'type',
        'value',
        'old_balance',
        'new_balance',
        'payment_type',
        'payment_reference',
        'pair_transaction',
        'pair_vcard',
        'category_id',
        'description',
        'custom_options',
        'custom_data',
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    }

    public function vcard_object()
    {
        return $this->belongsTo('App\Models\VCard', 'vcard', 'phone_number');
    }
    public function pair_vcard_object()
    {
        return $this->belongsTo('App\Models\VCard', 'pair_vcard', 'phone_number');
    }
    public function pair_transaction_object()
    {
        return $this->hasOne('App\Models\Transaction');
    }
    public function payment_type_object()
    {
        return $this->belongsTo('App\Models\PaymentType','payment_type','code');
    }
}
