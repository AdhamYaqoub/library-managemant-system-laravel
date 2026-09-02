<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Book extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'author',
        'category',
        'publish_year',
        'is_available',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'author',
                'category',
                'publish_year',
                'is_available',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Book {$eventName}");
        // ->useLogName('book');
    }
}
