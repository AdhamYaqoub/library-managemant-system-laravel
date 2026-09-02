<?php

namespace App\Interfaces;

interface BookRepositoryInterface
{
    public function getAll(array $filters = []);

    public function getById($id);

    public function store(array $data);

    public function update($id, array $data);

    public function destroy($id);

    public function restore($id);

    public function statistics();
}
