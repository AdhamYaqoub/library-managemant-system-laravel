<?php

namespace App\Interfaces;

interface BorrowingRepositoryInterface
{
    public function getAll(array $filters = []);

    public function getById($id);

    public function store(array $data);

    public function update($id, array $data);

    public function destroy($id);

    public function returnBook($id);
}