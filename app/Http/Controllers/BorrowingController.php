<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['book', 'member'])->get();

        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $books = Book::where('is_available', true)->get();
        $members = Member::all();

        return view('borrowings.create', compact('books', 'members'));
    }

    public function store(Request $request)
    {
        $book = Book::findOrFail($request->book_id);

        if (!$book->is_available) {
            return back()->with('error', 'Book not available');
        }

        Borrowing::create([
            'book_id' => $request->book_id,
            'member_id' => $request->member_id,
            'borrowed_at' => now(),
        ]);

        $book->update([
            'is_available' => false
        ]);

        return redirect('/borrowings');
    }

    public function returnBook(Borrowing $borrowing)
    {
        $borrowing->update([
            'returned_at' => now()
        ]);

        $borrowing->book->update([
            'is_available' => true
        ]);

        return redirect('/borrowings');
    }
}