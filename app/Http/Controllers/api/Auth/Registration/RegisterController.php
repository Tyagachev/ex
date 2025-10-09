<?php

namespace App\Http\Controllers\api\Auth\Registration;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|max:55',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => 'required|confirmed|min:8'
        ];

        $messages = [
            'name.required' => 'Поле обязательно для заполнения',
            'password.min' => 'Пароль должен быть не менее 8 символов',
            'password.required' => 'Поле обязательно для заполнения',
            'password.confirmed' => 'Пароли не совпадают',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }

        $firstUser = User::count() === 0;
        $role = $firstUser ? 1 : 0;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $role = Role::query()->find(2);
        $user->roles()->attach($role);

        //event(new Registered($user));

        Auth::login($user);

        return redirect(route('welcome', absolute: false));

    }
}
