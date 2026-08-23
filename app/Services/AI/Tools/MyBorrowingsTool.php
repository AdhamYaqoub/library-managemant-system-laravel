<?php

namespace App\Services\AI\Tools;

use Illuminate\Support\Facades\Auth;

class MyBorrowingsTool
{
    public function definition(): array
    {
        return [
            'type' => 'function',

            'function' => [
                'name' => 'get_my_borrowings',

                'description' =>
                    'Get the current authenticated member borrowings.',

                'parameters' => [
                    'type' => 'object',
                    'properties' => [],
                ],
            ],
        ];
    }

    public function execute(): array
    {
        $user = Auth::user();

        $member = $user->member;

        if (!$member) {
            return [];
        }

        return $member->borrowings()
            ->with('book')
            ->latest()
            ->get()
            ->map(function ($borrowing) {

                return [
                    'id' => $borrowing->id,
                    'book' => $borrowing->book?->title,
                    'borrowed_at' => $borrowing->borrowed_at,
                    'due_date' => $borrowing->due_date,
                    'returned_at' => $borrowing->returned_at,
                ];
            })
            ->toArray();
    }
}