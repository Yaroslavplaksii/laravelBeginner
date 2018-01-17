<?php

namespace App;

use \Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    const IS_UNBAN = 1;
    const IS_BAN = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public static function add($fields)
    {//метод додавання користувача
        $user = new static;//поточний клас
        $user->fill($fields);//беремо і додаємо поля які вказані в масиві $fillable
        $user->password = bcrypt($fields['password']);//зашифровуємо пароль
        $user->save();//зберігаємо

        return $user;//якщо потрібно
    }

    public function edit($fields)
    {//метод зміни користувача

        $this->fill($fields);//беремо і додаємо поля які вказані в масиві $fillable
        $this->password = bcrypt($fields['password']);//зашифровуємо пароль
        $this->save();//зберігаємо
    }

    public function remove()
    {//видалення користувача
        Storage::delete('uploads/' . $this->avatar);//видалення старого зображення
        $this->delete();
    }

    public function uploadAvatar($avatar)//метод додавання аватара
    {
        if ($avatar == null) {//якщо зображення не було вибрано
            return;
        }
        if ($this->avatar != null) {
            Storage::delete('uploads/' . $this->avatar);
        }
        Storage::delete('uploads/' . $this->avatar);//видалення старого зображення
        $filename = str_random(10) . '.' . $avatar->extension();
        $avatar->StoreAs('uploads', $filename);//директорія вказується відносно згидшс
        $this->avatar = $filename;
        $this->save();
    }

    public function getImage()
    {//метод який виведе аватар
        if ($this->avatar == null) {
            return '/img/no-user.png';//коли немає зображення
        }
        return "/uploads/" . $this->avatar;
    }

    public function makeAdmin()
    {//метод який переводить користувача в адміни
        $this->is_admin = 1;
    }

    public function makeNormal()
    {//метод який переводить користувача в звичайний статус
        $this->is_admin = 0;
    }

    public function toggleAdmin($value)
    {//обєднуємо в один метод створення статутсу для користувача
        if ($value == null) {
            return $this->makeNormal();
        }
        return $this->makeAdmin();
    }

    public function ban()
    {
        $this->status = User::IS_BAN;
        $this->save();
    }

    public function unban()
    {
        $this->status = User::IS_UNBAN;
        $this->save();
    }

    public function toggleBan($value)
    {
        if ($value == null) {
            return $this->ban();
        }
        return $this->unban();
    }
}
