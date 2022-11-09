<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use Illuminate\Http\Request;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\VCard;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\Factory;
use Illuminate\Pagination\Paginator;

class TransactionController extends Controller
{
    public function index(VCard $vcard)
    {
        $data = Transaction::where('vcard', '=', $vcard->phone_number)->orderBy('date', 'DESC')->paginate(12);
        return TransactionResource::collection($data);
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    public function count(VCard $vcard)
    {
        return count(Transaction::where('vcard', '=', $vcard->phone_number)->get());
    }

    public function balance_history(Vcard $vcard)
    {
        $transactions = Transaction::where('vcard', '=', $vcard->phone_number)->get();
        $array = [];
        $orderedArray = [];
        foreach ($transactions as $t) {
            $d =new Carbon($t->date);
            $month = $d->month;
            $year = $d->year;
            if(!isset($array[$month.$year])){
                $array[$month.$year] = $t;
                array_push($orderedArray,$t);
            }
        }
        return $orderedArray;
    }

    public function create(Request $request)
    {
        $vcard = VCard::find($request->vcard);
        $transaction = new Transaction();
        $pair_transaction = new Transaction();
        $transaction->vcard = $vcard->phone_number;
        $transaction->date = Carbon::now()->format('Y-m-d');
        $transaction->datetime = Carbon::now()->format('Y-m-d h:i:s');
        $transaction->type = $request->type;
        $transaction->value = $request->value;
        $transaction->old_balance = $vcard->balance;
        if ($transaction->type == 'C') {
            $vcard->balance += $transaction->value;
        }
        if ($transaction->type == 'D') {
            $vcard->balance -= $transaction->value;
        }
        $transaction->new_balance = $vcard->balance;
        $transaction->payment_type = $request->payment_type;
        $transaction->payment_reference = $request->payment_reference;
        $transaction->category_id = $request->categcategory_idory_id;
        $pairVcard = VCard::find($transaction->payment_reference);
        if ($pairVcard != null) {

            $transaction->pair_vcard = $pairVcard->phone_number;

            $pair_transaction->vcard = $pairVcard->phone_number;

            $pair_transaction->date = \Carbon\Carbon::today()->format('Y-m-d');
            $pair_transaction->datetime = \Carbon\Carbon::today()->format('Y-m-d h:i:s');


            if ($request->type == 'C') {
                $pair_transaction->type = 'D';
            }
            if ($request->type == 'D') {
                $pair_transaction->type = 'C';
            }

            $pair_transaction->value = $request->value;

            $pair_transaction->old_balance = $pairVcard->balance;

            if ($pair_transaction->type == 'C') {
                $pairVcard->balance += $pair_transaction->value;
            }
            if ($pair_transaction->type == 'D') {
                $pairVcard->balance -= $pair_transaction->value;
            }


            $pair_transaction->category_id = $request->category_id;

            $pair_transaction->new_balance = $pairVcard->balance;
            $pair_transaction->payment_type = $request->payment_type;
            $pair_transaction->payment_reference = $request->vcard;
            $pair_transaction->pair_vcard = $request->vcard;
            $pair_transaction->pair_transaction = $transaction->id;
            $transaction->pair_transaction = $pair_transaction->id;
            $pair_transaction->description = $request->description;
            $pair_transaction->custom_options = $request->custom_options;
            $pair_transaction->custom_data = $request->custom_data;
            $pair_transaction->save();
        }
        $transaction->description = $request->description;
        $transaction->custom_options = $request->custom_options;
        $transaction->custom_data = $request->custom_data;
        $transaction->save();
        $vcard->save();
        if ($pairVcard != null) {
            $pairVcard->save();
            $transaction->pair_transaction = $pair_transaction->id;
            $pair_transaction->pair_transaction = $transaction->id;
            $transaction->save();
            $pair_transaction->save();
        }
        return new TransactionResource($transaction);
    }
}
