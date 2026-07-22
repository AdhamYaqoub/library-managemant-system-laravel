<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'category',
        'publish_year',
        'is_available'
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}