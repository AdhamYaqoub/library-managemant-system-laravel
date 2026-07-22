<h1>Edit Member</h1>

<form action="/members/{{ $member->id }}" method="POST">

    @csrf
    @method('PUT')

    <div>
        <label>Name</label>
        <input
            type="text"
            name="name"
            value="{{ $member->name }}"
        >
    </div>

    <br>

    <div>
        <label>Email</label>
        <input
            type="email"
            name="email"
            value="{{ $member->email }}"
        >
    </div>

    <br>

    <button type="submit">
        Update Member
    </button>

</form>