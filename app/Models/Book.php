<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
class Book extends Model
{
    use HasFactory;

    protected $fillable = ['author','title','body'];

    public function rating()
    {
        return $this->hasMany(Rating::class,'book_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class,'book_id');
    }

    public function summaries()
    {
        return $this->belongsToMany(Rating::class, 'book_id');
    }
}
