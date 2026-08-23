<?php

namespace App\Repositories;

use App\Models\Book;
use App\Interfaces\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public function getAll(array $filters = [])
    {
        $books = Book::query();

        if (!empty($filters['search'])) {
            $books->where(function ($query) use ($filters) {
                $query->where('title', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('author', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('category', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['category'])) {
            $books->where('category', $filters['category']);
        }

        if (!empty($filters['order_by'])) {
            $allowedColumns = [
                'title',
                'author',
                'category',
                'publish_year',
            ];

            if (in_array($filters['order_by'], $allowedColumns)) {
                $direction = $filters['direction'] ?? 'asc';

                if (!in_array($direction, ['asc', 'desc'])) {
                    $direction = 'asc';
                }

                $books->orderBy(
                    $filters['order_by'],
                    $direction
                );
            }
        }

        return $books->paginate(
            $filters['per_page'] ?? 10
        );
    }

    public function getById($id)
    {
        return Book::findOrFail($id);
    }

    public function store(array $data)
    {
        return Book::create($data);
    }

    public function update($id, array $data)
    {
        $book = Book::findOrFail($id);

        $book->update($data);

        return $book->fresh();
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        return $book->delete();
    }

    public function restore($id)
    {
        $book = Book::withTrashed()->findOrFail($id);

        $book->restore();

        return $book->fresh();
    }

    public function statistics()
    {
        return [
            'total_books' => Book::count(),

            'available_books' => Book::where(
                'is_available',
                true
            )->count(),

            'borrowed_books' => Book::where(
                'is_available',
                false
            )->count(),
        ];
    }
}