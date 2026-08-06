<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;

use App\Http\Resources\BookResource;

use App\Services\BookService;

use App\Traits\ApiResponse;

class BookController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
{
    $books = $this->bookService->getAll($request);

return $this->success(
    BookResource::collection($books),
    'Books retrieved successfully.'
);}

   

  public function store(StoreBookRequest $request)
{
    $book = $this->bookService->store($request->validated());

    return $this->success(
    new BookResource($book),
    'Book created successfully.',
    201
);
}

  

    public function update(UpdateBookRequest $request, $id)
{
    $book = $this->bookService->update($id, $request->validated());

   return $this->success(
    new BookResource($book),
    'Book updated successfully.'
);
}

    
public function destroy($id)
{
    $this->bookService->destroy($id);

   return $this->success(
    null,
    'Book deleted successfully.'
);
}

    

  

   

    

 public function statistics()
{
    $statistics = $this->bookService->statistics();

    return $this->success(
        $statistics,
        'Statistics retrieved successfully.'
    );
}

public function show($id)
{
    $book = $this->bookService->getById($id);

    return $this->success(
        new BookResource($book),
        'Book retrieved successfully.'
    );
}


protected BookService $bookService;

public function __construct(BookService $bookService)
{
    $this->bookService = $bookService;
}

public function restore($id)
{
    $book = $this->bookService->restore($id);

    return $this->success(
        new BookResource($book),
        'Book restored successfully.'
    );
}
}