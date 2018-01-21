<?php

namespace App;

use \Storage;
use Carbon\Carbon;//клас для роботи з датами
//use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;

    protected $fillable = ['title', 'content','date'];

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;

    public function category()
    {
        return $this->belongsTo(Category::class);
        //return $this->hasOne(Category::class);//звязки один до одного
    }

    public function author()
    {
         return $this->hasOne(User::class);//модель
    }

    public function tags()
    {
        return $this->belongsTo(User::class, 'user_id');
//        return $this->belongToMany(//звязки багато до багатьох
//            Tag::class,//модель
//            'post_tags',//назва таблиці
//            'post_id',//id запису
//            'tag_id'//id мітки
//        );
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
        $this->removeImage();
        $this->delete();
    }

    public function uploadThumb($image)
    {
        if ($image == null) {//якщо зображення не було вибрано
            return;
        }
        $this->removeImage();
        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);//директорія вказується відносно public
        $this->image = $filename;
        $this->save();
    }
    public function removeImage(){
        if($this->image != null){
            Storage::delete('uploads/' . $this->image);//видалення старого зображення
        }
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

    public function getCategoryTitle()
    {
        return ($this->category != null)
            ?   $this->category->title
            :   'Нет категории';
    }

    public function getTagsTitles()
    {
        return (!empty($this->tags))
            ?   implode(', ', $this->tags->pluck('title')->all())
            : 'Нет тегов';
    }
    public function setDateAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');//переводить в потрібний формат
        $this->attributes['date'] = $date;
    }

    public function getDateAttribute($value)
    {
        $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');

        return $date;
    }

}
