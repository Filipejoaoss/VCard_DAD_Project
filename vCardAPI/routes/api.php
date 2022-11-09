<?php

use App\Http\Controllers\DefaultCategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VCardController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('vcards', [VCardController::class, 'create']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('users', [UserController::class, 'index']);
    Route::get('users/me', [UserController::class, 'show_me']);
    Route::get('users/{user}', [UserController::class, 'show'])->middleware('can:view,user');
    Route::put('users/{user}', [UserController::class, 'update'])->middleware('can:update,user');
    Route::put('users/{user}/admin', [UserController::class, 'updateAdmin'])->middleware('can:updateAdmin,App\Models\User');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('can:delete,App\Models\DefaultCategory');
    Route::patch('users/{user}/password', [UserController::class, 'update_password'])->middleware('can:updatePassword,user');
    Route::patch('users/{user}/confirmation_code', [UserController::class, 'update_code']);

    Route::get('vcards',[VCardController::class,'index']);
    Route::get('vcards/me',[VCardController::class,'show_me']);
    Route::get('vcards/count',[VCardController::class,'count']);
    Route::get('vcards/history',[VCardController::class,'vcards_over_time']);
    Route::get('vcards/{vcard}',[VCardController::class,'show']);
    Route::post('vcards/{vcard}/validate/code',[VCardController::class,'confirm_code']);
    Route::get('vcards/{vcard}/balance/history',[TransactionController::class,'balance_history']);
    Route::get('vcards/{vcard}/transactions',[TransactionController::class,'index']);
    Route::get('vcards/{vcard}/transactions/count',[TransactionController::class,'count']);
    Route::delete('vcards/{vcard}', [VCardController::class, 'delete']);

    Route::get('transactions/{transaction}',[TransactionController::class,'show']);
    Route::post('transactions',[TransactionController::class,'create']);
    Route::get('transactions/count_transactions',[TransactionController::class],'count_transactions');

    Route::get('default_categories', [DefaultCategoryController::class, 'index']);
    Route::post('default_categories', [DefaultCategoryController::class, 'store'])->middleware('can:create,App\Models\DefaultCategory');
    Route::get('default_categories/{defaultCategory}', [DefaultCategoryController::class, 'show']);
    Route::put('default_categories/{defaultCategory}', [DefaultCategoryController::class, 'update'])->middleware('can:update,defaultCategory');
    Route::delete('default_categories/{defaultCategory}', [DefaultCategoryController::class, 'destroy'])->middleware('can:delete,defaultCategory');
    Route::get('categories/{vcard}', [CategoryController::class, 'index']);
    Route::get('categories/get/{category}', [CategoryController::class, 'show']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::post('categories/{vcard}', [CategoryController::class, 'create']);
    Route::delete('categories/{category}', [CategoryController::class, 'delete']);
    Route::get('payment_types', [PaymentTypeController::class, 'index']);
});



