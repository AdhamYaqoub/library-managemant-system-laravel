<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Borrowing;
use App\Models\User;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'name',
    'email',
];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function activeBorrowings()
{
    return $this->hasMany(Borrowing::class)
        ->whereNull('returned_at');
}

public function user()
{
    return $this->belongsTo(User::class);
}

}