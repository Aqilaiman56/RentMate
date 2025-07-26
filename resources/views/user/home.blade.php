@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

    <!-- Top nav with search -->
    <div class="flex justify-between items-center py-4">
        <h1 class="text-2xl font-bold">Item Listings</h1>
        <form method="GET" action="{{ route('user.home') }}" class="flex">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search items..." class="border rounded-l px-4 py-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r">Search</button>
        </form>
    </div>

    <!-- Item cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($items as $item)
            <div class="border rounded shadow p-4">
                <img src="{{ asset('storage/' . $item->ItemImage) }}" alt="{{ $item->ItemName }}" class="w-full h-40 object-cover mb-4">
                <h2 class="text-lg font-semibold">{{ $item->ItemName }}</h2>
                <p class="text-sm text-gray-600">📍 {{ $item->Location }}</p>
                <p class="text-blue-600 font-bold mt-2">RM {{ number_format($item->Price, 2) }}</p>
            </div>
        @empty
            <p>No items found.</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
@endsection