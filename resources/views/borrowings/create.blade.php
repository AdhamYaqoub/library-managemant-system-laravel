<h1>Borrow Book</h1>

<form action="/borrowings" method="POST">

    @csrf

    <div>
        <label>Member</label>

        <select name="member_id">

            @foreach($members as $member)

                <option value="{{ $member->id }}">
                    {{ $member->name }}
                </option>

            @endforeach

        </select>
    </div>

    <br>

    <div>
        <label>Book</label>

        <select name="book_id">

            @foreach($books as $book)

                <option value="{{ $book->id }}">
                    {{ $book->title }}
                </option>

            @endforeach

        </select>
    </div>

    <br>

    <button type="submit">
        Borrow
    </button>

</form>