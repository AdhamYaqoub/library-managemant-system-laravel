<?php

namespace App\Repositories;

use App\Interfaces\BorrowingRepositoryInterface;
use App\Models\Book;
use App\Models\Borrowing;

class BorrowingRepository implements BorrowingRepositoryInterface
{
    public function getAll(array $filters = [])
    {
        return Borrowing::with(['book', 'member'])
            ->paginate($filters['per_page'] ?? 10);
    }

    public function getById($id)
    {
        return Borrowing::with(['book', 'member'])
            ->findOrFail($id);
    }

    public function store(array $data)
    {
        $book = Book::findOrFail($data['book_id']);

        if (!$book->is_available) {
            throw new \Exception('Book is not available.');
        }

        $borrowing = Borrowing::create([
            'book_id'     => $data['book_id'],
            'member_id'   => $data['member_id'],
            'borrowed_at' => now(),
            'due_date'    => now()->addDays(14),
        ]);

        $book->update([
            'is_available' => false,
        ]);

        return $borrowing->load(['book', 'member']);
    }

    public function update($id, array $data)
    {
        $borrowing = Borrowing::findOrFail($id);

        $borrowing->update($data);

        return $borrowing->load(['book', 'member']);
    }

    public function destroy($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if (is_null($borrowing->returned_at)) {
            $borrowing->book->update([
                'is_available' => true,
            ]);
        }

        $borrowing->delete();

        return true;
    }

    public function returnBook($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        $borrowing->update([
            'returned_at' => now(),
        ]);

        $borrowing->book->update([
            'is_available' => true,
        ]);

        return $borrowing->load(['book', 'member']);
    }
}