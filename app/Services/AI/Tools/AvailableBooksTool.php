<?php

namespace App\Services\AI\Tools;

use App\Models\Book;

class AvailableBooksTool
{
    public function execute(): array
    {
        return Book::query()
            ->where('is_available', true)
            ->orderBy('title')
            ->get([
                'id',
                'title',
                'author',
                'category',
                'publish_year',
            ])
            ->toArray();
    }
}