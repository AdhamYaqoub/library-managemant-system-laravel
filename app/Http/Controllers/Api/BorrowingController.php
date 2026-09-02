<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Requests\UpdateBorrowingRequest;
use App\Http\Resources\BorrowingResource;
use App\Models\Book;
use App\Models\Borrowing;
use App\Services\BorrowingService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    use ApiResponse;

    protected BorrowingService $borrowingService;

    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
    }

    public function index(Request $request)
    {
        $borrowings = $this->borrowingService->getAll($request);

        return $this->success(
            BorrowingResource::collection($borrowings),
            'Borrowings retrieved successfully.'
        );
    }

    public function show($id)
    {
        $borrowing = $this->borrowingService->getById($id);

        return $this->success(
            new BorrowingResource($borrowing),
            'Borrowing retrieved successfully.'
        );
    }

    public function store(StoreBorrowingRequest $request)
    {
        $data = $request->validated();

        $user = $request->user();

        $member = $user->member;

        if (! $member) {
            return $this->error(
                'Member profile not found.',
                404
            );
        }

        $book = Book::findOrFail($data['book_id']);

        if (! $book->is_available) {
            return $this->error(
                'Book is not available.',
                422
            );
        }

        $borrowing = Borrowing::create([
            'book_id' => $book->id,
            'member_id' => $member->id,
            'borrowed_at' => now(),
            'due_date' => now()->addDays(14),
        ]);

        $book->update([
            'is_available' => false,
        ]);

        return $this->success(
            new BorrowingResource($borrowing),
            'Book borrowed successfully.',
            201
        );
    }

    public function update(UpdateBorrowingRequest $request, $id)
    {
        $borrowing = $this->borrowingService->update(
            $id,
            $request->validated()
        );

        return $this->success(
            new BorrowingResource($borrowing),
            'Borrowing updated successfully.'
        );
    }

    public function destroy($id)
    {
        $this->borrowingService->destroy($id);

        return $this->success(
            null,
            'Borrowing deleted successfully.'
        );
    }

    public function returnBook($id)
    {
        $borrowing = $this->borrowingService->returnBook($id);

        return $this->success(
            new BorrowingResource($borrowing),
            'Book returned successfully.'
        );
    }
}
