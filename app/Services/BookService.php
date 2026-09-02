<?php

namespace App\Services;

use App\Interfaces\BookRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookService
{
    protected $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    // public function getAll($request)
    // {
    //     return $this->bookRepository->getAll($request);
    // }
    public function getAll($request)
    {
        return $this->bookRepository->getAll(
            $request->only([
                'search',
                'category',
                'order_by',
                'direction',
                'per_page',
            ])
        );
    }

    public function getById($id)
    {
        return $this->bookRepository->getById($id);
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {

            $book = $this->bookRepository->store($data);

            DB::commit();

            Log::info('Book created successfully.', [
                'book_id' => $book->id,
                'title' => $book->title,
            ]);

            return $book;

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Failed to create book.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();

        try {

            $book = $this->bookRepository->update($id, $data);

            DB::commit();

            Log::info('Book updated.', [
                'book_id' => $book->id,
            ]);

            return $book;

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Failed to update book.', [
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $this->bookRepository->destroy($id);

            DB::commit();

            Log::info('Book deleted.', [
                'book_id' => $id,
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Failed to delete book.', [
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {

            $book = $this->bookRepository->restore($id);

            DB::commit();

            Log::info('Book restored.', [
                'book_id' => $book->id,
            ]);

            return $book;

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Failed to restore book.', [
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function statistics()
    {
        return $this->bookRepository->statistics();
    }
}
