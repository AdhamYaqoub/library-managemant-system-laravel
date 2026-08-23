<?php

namespace App\Services\AI\Tools;

use App\Models\Book;
use Spatie\Activitylog\Models\Activity;

class BookHistoryTool
{
    public function definition(): array
    {
        return [
            'type' => 'function',

            'function' => [
                'name' => 'get_book_history',

                'description' =>
                    'Get the activity history of a book.',

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

        return Activity::query()
            ->where('subject_type', Book::class)
            ->where('subject_id', $book->id)
            ->latest()
            ->get()
            ->map(function ($activity) {

                return [
                    'event' => $activity->event,
                    'description' => $activity->description,
                    'properties' => $activity->properties,
                    'created_at' => $activity->created_at,
                ];
            })
            ->toArray();
    }
}