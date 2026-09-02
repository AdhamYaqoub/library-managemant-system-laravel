<?php

namespace App\Services\AI\Tools;

use App\Models\Book;

class GetBookTool
{
    public function definition(): array
    {
        return [
            'type' => 'function',

            'function' => [
                'name' => 'get_book',

                'description' => 'Get detailed information about a book using its ID.',

                'parameters' => [
                    'type' => 'object',

                    'properties' => [
                        'book_id' => [
                            'type' => 'integer',
                            'description' => 'The book ID.',
                        ],
                    ],

                    'required' => [
                        'book_id',
                    ],
                ],
            ],
        ];
    }

    public function execute(array $arguments): array
    {
        $book = Book::findOrFail(
            $arguments['book_id']
        );

        return [
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'category' => $book->category,
            'publish_year' => $book->publish_year,
            'is_available' => $book->is_available,
        ];
    }
}
