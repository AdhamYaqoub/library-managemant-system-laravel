<h1>Add New Book</h1>

<form action="/books" method="POST">
    @csrf

    <div>
        <label>Title</label>
        <input type="text" name="title">
    </div>

    <br>

    <div>
        <label>Author</label>
        <input type="text" name="author">
    </div>

    <br>

    <div>
        <label>Category</label>
        <input type="text" name="category">
    </div>

    <br>

    <div>
        <label>Publish Year</label>
        <input type="number" name="publish_year">
    </div>

    <br>

    <button type="submit">
        Save Book
    </button>
</form>