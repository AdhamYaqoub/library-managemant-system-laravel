<?php

namespace App\Services;

use App\Interfaces\BorrowingRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BorrowingService
{
    protected $borrowingRepository;

    public function __construct(BorrowingRepositoryInterface $borrowingRepository)
    {
        $this->borrowingRepository = $borrowingRepository;
    }

    public function getAll(array $filters = [])
    {
        return $this->borrowingRepository->getAll($filters);
    }

    public function getById($id)
    {
        return $this->borrowingRepository->getById($id);
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {

            $borrowing = $this->borrowingRepository->store($data);

            DB::commit();

            Log::info('Book borrowed successfully.', [
                'borrowing_id' => $borrowing->id,
                'book_id' => $borrowing->book_id,
                'member_id' => $borrowing->member_id,
            ]);

            return $borrowing;

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Failed to borrow book.', [
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();

        try {

            $borrowing = $this->borrowingRepository->update($id, $data);

            DB::commit();

            Log::info('Borrowing updated.', [
                'borrowing_id' => $borrowing->id,
            ]);

            return $borrowing;

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Failed to update borrowing.', [
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $this->borrowingRepository->destroy($id);

            DB::commit();

            Log::info('Borrowing deleted.', [
                'borrowing_id' => $id,
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Failed to delete borrowing.', [
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function returnBook($id)
    {
        DB::beginTransaction();

        try {

            $borrowing = $this->borrowingRepository->returnBook($id);

            DB::commit();

            Log::info('Book returned.', [
                'borrowing_id' => $borrowing->id,
            ]);

            return $borrowing;

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Failed to return book.', [
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}