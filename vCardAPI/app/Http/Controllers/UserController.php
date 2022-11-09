<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserAdminRequest;
use App\Http\Requests\UpdateUserCodeRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\VCardResource;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VCard;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::orderBy('id', 'ASC')->paginate(12));
    }

    public function show(User $user)
    {
        $vcardUser = VCard::find($user->id);
        if ($vcardUser)
            return new VCardResource($vcardUser);

        return new UserResource($user);
    }

    public function show_me(Request $request)
    {
        return $this->show($request->user());
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $vcardUser = VCard::find($user->id);
        if ($vcardUser) {
            $vcardUser->update($request->validated());
            return new VCardResource($vcardUser);
        }

        $adminUser = Admin::find($user->id);
        if ($adminUser) {
            $adminUser->update($request->validated());
            return new UserResource($adminUser);
        }

        return new UserResource($user);

        $vcardUser = VCard::find($user->id);
        $vcardUser->update($request->validated());
        return new VCardResource($user);
    }

    public function updateAdmin(UpdateUserAdminRequest $request, User $user)
    {
        $vcardUser = VCard::find($user->id);
        if ($vcardUser) {
            $vcardUser->update($request->validated());
            return new VCardResource($vcardUser);
        }

        $adminUser = Admin::find($user->id);
        if ($adminUser) {
            $adminUser->update($request->validated());
            return new UserResource($adminUser);
        }

        return new UserResource($user);
    }


    public function update_password(UpdateUserPasswordRequest $request, User $user)
    {
        $vcardUser = VCard::find($user->id);
        if ($vcardUser) {
            $vcardUser->password = bcrypt($request->validated()['password']);
            $vcardUser->save();
            return new VCardResource($user);
        }
        $adminUser = Admin::find($user->id);
        if ($adminUser) {
            $adminUser->password = bcrypt($request->validated()['password']);
            $adminUser->save();
            return new UserResource($adminUser);
        }
    }

    public function update_code(UpdateUserCodeRequest $request, User $user)
    {
        $vcardUser = VCard::find($user->id);
        if ($vcardUser) {
            $vcardUser->confirmation_code = bcrypt($request->validated()['code']);
            $vcardUser->save();
            return new VCardResource($user);
        }

        $adminUser = Admin::find($user->id);
        if ($adminUser) {
            $adminUser->confirmation_code = bcrypt($request->validated()['code']);
            $vcardUser->save();
            return new UserResource($adminUser);
        }
    }

    public function destroy(User $user)
    {
        $vcardUser = VCard::find($user->id);
        if ($vcardUser)
        {
            if ($vcardUser->balance != 0) {
                return;
            }

            $transactions = Transaction::where('vcard', $user->id)->get();
            Category::where('vcard', $user->id)->delete();

            if ($transactions->count() == 0) {
                $vcardUser->forceDelete();
            }
            else {
                $vcardUser->delete();
            }
            return new VCardResource($vcardUser);
        }

        $adm = Admin::find($user->id);
        if ($adm) {
            $adm->delete();
            return new UserResource($adm);
        }

        return new UserResource($user);
    }
}
