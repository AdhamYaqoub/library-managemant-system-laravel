<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::paginate(5);

        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'category' => 'required|max:255',
            'publish_year' => 'required|integer|min:1800|max:' . date('Y'),
        ]);

        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'category' => $request->category,
            'publish_year' => $request->publish_year,
            'is_available' => true,
        ]);

        return redirect('/books');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'category' => 'required|max:255',
            'publish_year' => 'required|integer|min:1800|max:' . date('Y'),
        ]);

        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'category' => $request->category,
            'publish_year' => $request->publish_year,
        ]);

        return redirect('/books');
    }

    public function destroy(Book $book)
{
    $isBorrowed = $book->borrowings()
        ->whereNull('returned_at')
        ->exists();

    if ($isBorrowed) {
        return redirect('/books')
            ->with('error', 'Cannot delete this book because it is currently borrowed.');
    }

    $book->delete();

    return redirect('/books')
        ->with('success', 'Book deleted successfully.');
}

    public function search(Request $request)
    {
        $books = Book::where(
            'title',
            'like',
            '%' . $request->title . '%'
        )->paginate(5);

        return view('books.index', compact('books'));
    }

    public function searchCategory(Request $request)
    {
        $books = Book::where(
            'category',
            'like',
            '%' . $request->category . '%'
        )->paginate(5);

        return view('books.index', compact('books'));
    }

    public function sortTitle()
    {
        $books = Book::orderBy('title')
            ->paginate(5);

        return view('books.index', compact('books'));
    }

    public function sortYear()
    {
        $books = Book::orderBy('publish_year')
            ->paginate(5);

        return view('books.index', compact('books'));
    }

    public function statistics()
    {
        return view('statistics', [
            'totalBooks' => Book::count(),

            'availableBooks' => Book::where(
                'is_available',
                true
            )->count(),

            'borrowedBooks' => Book::where(
                'is_available',
                false
            )->count(),

            'totalMembers' => Member::count(),
        ]);
    }
}