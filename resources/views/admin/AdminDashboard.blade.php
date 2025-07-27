<x-app-layout>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Log out</button>
        </form>
    </div>
</x-app-layout>