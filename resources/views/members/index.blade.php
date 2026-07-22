<h1>Members</h1>

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