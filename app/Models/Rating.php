<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','book_id','like','mark'
    ];
    public function book()
    {
        return $this->hasMany(Book::class,'book_id');
    }
}
