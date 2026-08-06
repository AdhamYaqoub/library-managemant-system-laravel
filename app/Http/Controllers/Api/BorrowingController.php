<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;

use App\Services\BorrowingService;

use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Requests\UpdateBorrowingRequest;

use App\Http\Resources\BorrowingResource;

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
        $borrowing = $this->borrowingService->store(
            $request->validated()
        );

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