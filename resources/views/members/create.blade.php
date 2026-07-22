<h1>Add New Member</h1>

<form action="/members" method="POST">

    @csrf

    <div>
        <label>Name</label>
        <input type="text" name="name">
    </div>

    <br>

    <div>
        <label>Email</label>
        <input type="email" name="email">
    </div>

    <br>

    <button type="submit">
        Save Member
    </button>

</form>