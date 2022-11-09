<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'vcard',
        'type',
        'name',
    ];

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'category_id', 'id');
    }
    public function vcard()
    {
        return $this->belongsTo('App\Models\VCard', 'phone_number', 'vcard');
    }
}
