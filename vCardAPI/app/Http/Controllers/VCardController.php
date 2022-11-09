<?php

namespace App\Http\Controllers;

use App\Models\VCard;
use Illuminate\Http\Request;
use App\Http\Resources\VCardResource;
use App\Http\Requests\StoreUpdateVCardRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\DefaultCategory;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class VCardController extends Controller
{
    public function index()
    {
        return VCardResource::collection(VCard::all());
    }

    public function show(VCard $vcard)
    {
        return new VCardResource($vcard);
    }
    public function show_me(Request $request)
    {
        $vcard = VCard::find($request->user()->id);
        if($vcard){
            return new VCardResource($vcard);
        }
    }

    public function confirm_code(Request $request, Vcard $vcard)
    {
        $isPasswordValid = false;
        $isCodeValid = false;
        $v_card = Vcard::find($vcard)[0];
        if(isset($request->password)){
            if(Hash::check($request->password, $v_card->password)){
                $isPasswordValid = true;
            }
        }
        if(isset($request->code)){
            if(Hash::check($request->code, $v_card->confirmation_code)){
                $isCodeValid = true;
            }
        }
        $isValid = false;
        if((!isset($request->password) || $isPasswordValid) && (!isset($request->code) || $isCodeValid)){
            $isValid = true;
        }
        return (object) [
            'isValid' =>$isValid,
        ];
    }
    public function count()
    {
        return count(VCard::all());
    }

    public function create(StoreUpdateVCardRequest $request)
    {
        $newVCard = new VCard();
        $newVCard->phone_number = $request->phone_number;
        $newVCard->name = $request->name;
        $newVCard->email = $request->email;
        $newVCard->password = bcrypt($request->password);
        $newVCard->confirmation_code = bcrypt($request->confirmation_code);
        $newVCard->blocked = 0;
        $newVCard->balance = 0;
        $newVCard->max_debit = 5000;
        $newVCard->save();
        $defaults = DefaultCategory::all();
        foreach ($defaults as $default_category) {
            $category = new Category();
            $category->vcard = $request->phone_number;
            $category->type = $default_category->type;
            $category->name = $default_category->name;
            $category->save();
        }
        return new VCardResource(VCard::find($request->phone_number));
    }

    public function delete(VCard $vcard)
    {
        $transactions_c = Transaction::where('vcard', '=', $vcard->phone_number)->get()->count();
        if ($vcard->balance == 0) {
            if ($transactions_c == 0){
                Category::where('vcard', '=', $vcard->phone_number)->forceDelete();
            }else{
                Category::where('vcard', '=', $vcard->phone_number)->delete();
            }
            $categories = Category::where('vcard', '=', $vcard->phone_number)->get();
            if($categories->count() == 0){
                if ($transactions_c == 0) {
                    $vcard->forceDelete();
                    return new VCardResource($vcard);
                } else {
                    Transaction::where('vcard', '=', $vcard->phone_number)->delete();
                    $vcard->delete();
                    return new VCardResource($vcard);
                }
            }

        }
        return;
    }

    public function vcards_over_time()
    {
        $allVcards = VCard::withTrashed()->get();
        $array = [];
        foreach ($allVcards as $vcard) {
            $created = new Carbon($vcard->created_at);
            if (!isset($array[$created->month . '-' . $created->year])) {
                $array[$created->month . '-' . $created->year] = (object) [
                    'month' => $created->month,
                    'year' => $created->year,
                    'vcards' => 1,
                ];
            } else {
                $array[$created->month . '-' . $created->year]->vcards++;
            }
            if ($vcard->deleted_at) {
                $deleted = new Carbon($vcard->deleted_at);
                if (isset($array[$deleted->month . '-' . $deleted->year])) {
                    $array[$deleted->month . '-' . $deleted->year]->vcards--;
                }
            }
        }
        usort($array, function($a,$b) {
            if($a->year == $b->year){
                return $a->month > $b->month;
            }
            return $a->year > $b->year;
        });
        return $array;
    }
}
