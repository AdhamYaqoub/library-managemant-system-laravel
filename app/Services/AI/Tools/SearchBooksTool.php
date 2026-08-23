<?php

namespace App\Services\AI\Tools;

use App\Models\Book;

class SearchBooksTool
{
    public function execute(array $arguments): array
    {
        $search = $arguments['search'] ?? '';

        if ($search === '') {
            return [];
        }

        return Book::query()
            ->where(function ($query) use ($search) {

                $query->where(
                    'title',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'author',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'category',
                    'like',
                    "%{$search}%"
                );

            })
            ->get([
                'id',
                'title',
                'author',
                'category',
                'publish_year',
                'is_available',
            ])
            ->toArray();
    }
}