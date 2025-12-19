
<!-- Header -->
<div class="header">
    <h2>Selamat Datang, {{ auth()->user()->name }}</h2>
    <div class="user-info">
        <form method="POST" action="/logout">
            @csrf
            <button class="button" type="submit">Logout</button>
        </form>
    </div>
</div>
