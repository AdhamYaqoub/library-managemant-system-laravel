<h1>Edit Book</h1>

<form action="/books/{{ $book->id }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Title</label>
        <input type="text" name="title" value="{{ $book->title }}">
    </div>

    <br>

    <div>
        <label>Author</label>
        <input type="text" name="author" value="{{ $book->author }}">
    </div>

    <br>

    <div>
        <label>Category</label>
        <input type="text" name="category" value="{{ $book->category }}">
    </div>

    <br>

    <div>
        <label>Publish Year</label>
        <input type="number" name="publish_year" value="{{ $book->publish_year }}">
    </div>

    <br>

    <button type="submit">
        Update Book
    </button>
</form>