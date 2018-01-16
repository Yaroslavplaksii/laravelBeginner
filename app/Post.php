<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;

    protected $fillable = ['title', 'content'];

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;

    public function category()
    {
        return $this->hasOne(Category::class);//звязки один до одного
    }

    public function author()
    {
        return $this->hasOne(User::class);//модель
    }

    public function tags()
    {
        return $this->belongToMany(//звязки багато до багатьох
            Tag::class,//модель
            'post_tags',//назва таблиці
            'post_id',//id запису
            'tag_id'//id мітки
        );
    }

    public static function add($fields)
    {
        $post = new static;
        $post->fill($fields);
        $post->save();
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        Storage::delete('uploads/' . $this->image);//видалення старого зображення
        $this->delete();
    }

    public function uploadImage($image)
    {
        if ($image == null) {//якщо зображення не було вибрано
            return;
        }
        Storage::delete('uploads/' . $this->image);//видалення старого зображення
        $filename = str_random(10) . '.' . $image->extension();
        $image->saveAs('uploads', $filename);//директорія вказується відносно згидшс
        $this->image = $filename;
        $this->save();
    }

    public function setCategory($id)
    {
        if ($id == null) {
            return;
        }
        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)
    {
        if ($ids == null) {
            return;
        }
        $this->tags()->sync($ids);//синхронізуємо з id тегів
        //в даному прикладі це буде типу ->sync([2,4,6,8])
        //кожен раз коли ми міняємо набір id тегів, то всі
        // видаляються а потім наново записуються
    }

    public function setDraft()
    { //статус публікації "чорновик"
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

    public function setPublic()
    { //статус публікації "публікувати"
        $this->status = Post::IS_PUBLIC;//можна через констатнти
        $this->save();
    }

    public function togleStatus($value)
    {//метод який по заданому параметру буде записувати статус публыкації
        if ($value == null) {
            return $this->setDraft();
        }
        return $this->setPublic();
    }

    public function setFutured()
    { //виведення публікації в рекомендованих
        $this->is_futured = 1;
        $this->save();
    }

    public function setStandart()
    {
        $this->is_futured = 0;
        $this->save();
    }

    public function togleFutured($value)
    {//метод який по заданому параметру буде виводити публікацію в рекомендованих
        if ($value == null) {
            return $this->setFutured();
        }
        return $this->setStandart();
    }

    public function getImage()
    {//метод який виведе зображення
        if ($this->image == null) {
            return '/img/no-image.png';//коли немає зображення
        }
        return "/uploads/" . $this->image;
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
