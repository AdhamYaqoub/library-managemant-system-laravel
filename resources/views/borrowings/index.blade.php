<h1>Borrowings</h1>

<a href="/borrowings/create">
    Borrow Book
</a>

<hr>

@foreach($borrowings as $borrowing)

    <h3>{{ $borrowing->book->title }}</h3>

    <p>
        Member:
        {{ $borrowing->member->name }}
    </p>

    <p>
        Borrowed:
        {{ $borrowing->borrowed_at }}
    </p>

    <p>
        Returned:
        {{ $borrowing->returned_at ?? 'Not Returned' }}
    </p>

    @if(!$borrowing->returned_at)

        <form
            action="/borrowings/{{ $borrowing->id }}/return"
            method="POST"
        >
            @csrf
            @method('PUT')

            <button type="submit">
                Return Book
            </button>

        </form>

    @endif

    <hr>

@endforeach