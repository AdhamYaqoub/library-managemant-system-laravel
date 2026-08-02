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
    Due Date:
    {{ $borrowing->due_date }}
</p>

@if(!$borrowing->returned_at && now()->gt($borrowing->due_date))
    <p style="color:red;">
        Overdue
    </p>
@endif

@if(!$borrowing->returned_at && now()->gt($borrowing->due_date))

<p style="color:red;">
    Overdue by
    {{ \Carbon\Carbon::parse($borrowing->due_date)->diffInDays(now()) }}
    days
</p>

@endif

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