<h1>Members</h1>


@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

@if(session('success'))
    <p style="color:green;">
        {{ session('success') }}
    </p>
@endif


<a href="/members/create">Add Member</a>

@foreach($members as $member)

<h3>{{ $member->name }}</h3>

<p>{{ $member->email }}</p>

<a href="/members/{{ $member->id }}/edit">
    Edit
</a>

<form action="/members/{{ $member->id }}" method="POST">
    @csrf
    @method('DELETE')

    <button type="submit">
        Delete
    </button>
</form>

<hr>

@endforeach