<header class="topbar">
    <input type="text" placeholder="Search">
    <div class="user">
        {{ auth()->user()->username ?? 'Admin' }}
    </div>
</header>
