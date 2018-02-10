<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Tag extends Model
{
    use Sluggable;

    protected $fillable = ['title'];

    public function posts(){
        return $this->belongsToMany(//звязки багато до багатьох
            Post::class,//модель
            'post_tags',//назва таблиці
            'tag_id',//id мітки
            'post_id'//id запису
        );
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
