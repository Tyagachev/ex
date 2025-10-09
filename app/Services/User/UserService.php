<?php

namespace App\Services\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Добавление юзера
     *
     * @param array $data
     * @return mixed
     */
    public static function store(array $data): mixed
    {
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
        $role = Role::query()->find($data['role']);
        return $user->roles()->attach($role);
    }

    /**
     * Обновление
     *
     * @param object $user
     * @param object $request
     * @return mixed
     */
    public static function update(object $user, object $request): mixed
    {
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->roles()->sync([$request->role]);

        return  $user->save();
    }

    /**
     * Удаление юзера и ролей
     *
     * @param object $user
     * @return mixed
     */
    public static function destroy(object $user): mixed
    {
        $roleIdx = [];

        foreach ($user['roles'] as $u) {
            $roleIdx[] = $u->id;
        }

        $user->roles()->detach($roleIdx);
        return $user->delete();
    }
}
