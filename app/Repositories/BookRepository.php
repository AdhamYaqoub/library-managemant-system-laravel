<?php

namespace App\Repositories;

use App\Models\Book;
use App\Interfaces\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public function getAll($request)
    {
        $books = Book::query();

        // Search by title or author
        if ($request->filled('search')) {
            $search = $request->search;

            $books->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $books->where('category', $request->category);
        }

        // Filter by availability
        if ($request->filled('is_available')) {
            $books->where('is_available', $request->is_available);
        }

        // Sorting
        $books->orderBy(
            $request->get('sort', 'id'),
            $request->get('direction', 'asc')
        );

        return $books->paginate(10);
    }

    public function getById($id)
    {
        return Book::findOrFail($id);
    }

    public function store(array $data)
    {
        $data['is_available'] = true;

        return Book::create($data);
    }

    public function update($id, array $data)
    {
        $book = Book::findOrFail($id);

        $book->update($data);

        return $book;
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        $book->delete();

        return true;
    }

    public function restore($id)
{
    $book = Book::withTrashed()->findOrFail($id);

    $book->restore();

    return $book;
}

public function statistics()
{
    return [
        'total_books' => Book::count(),
        'available_books' => Book::where('is_available', true)->count(),
        'borrowed_books' => Book::where('is_available', false)->count(),
    ];
}
}