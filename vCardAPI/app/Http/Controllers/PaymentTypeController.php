<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentType;
use App\Http\Resources\PaymentTypeResource;

class PaymentTypeController extends Controller
{
    public function index()
    {
        return PaymentTypeResource::collection(PaymentType::all());
    }
}
