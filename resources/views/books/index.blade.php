<h1>Library Books</h1>

<a href="/books/create">Add New Book</a>
<hr>

<a href="/statistics">Statistics</a>

<hr>


<a href="/books/sort/title">
    Sort By Title
</a>
<hr>
<a href="/books/sort/year">
    Sort By Year
</a>
<hr>

<form action="/books/category" method="GET">

    <input
        type="text"
        name="category"
        placeholder="Category"
    >

    <button type="submit">
        Search Category
    </button>

</form>


<form action="/books/search" method="GET">

    <input
        type="text"
        name="title"
        placeholder="Search by title"
    >

    <button type="submit">
        Search
    </button>

</form>

<hr>

@foreach($books as $book)
    <hr>

    <h3>{{ $book->title }}</h3>

    <p>Author: {{ $book->author }}</p>

    <p>Category: {{ $book->category }}</p>

    <p>Year: {{ $book->publish_year }}</p>

    <a href="/books/{{ $book->id }}/edit">Edit</a>

    <form action="/books/{{ $book->id }}" method="POST">
    @csrf
    @method('DELETE')

    <button type="submit">Delete</button></form>
    <hr>
@endforeach

{{ $books->links() }}