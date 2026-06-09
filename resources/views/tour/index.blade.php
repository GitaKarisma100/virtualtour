<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtual Tour</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#001829] text-white min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center mb-4">Siap Menjelajahi Poliwangi?</h1>
        <p class="text-center text-gray-400 mb-12">Pilih gedung untuk memulai Virtual</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($buildings as $building)
                <a href="{{ route('tour.show', $building) }}" class="block group">
                    <div class="bg-[#003154] rounded-lg overflow-hidden shadow-lg transition-transform group-hover:scale-105">
                        @if($building->thumbnail_path)
                            <img src="{{ asset('storage/' . $building->thumbnail_path) }}"
                                 alt="{{ $building->name }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-700 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="p-4">
                            <h2 class="text-xl font-semibold group-hover:text-[#F3B414] transition-colors">
                                {{ $building->name }}
                            </h2>
                            @if($building->description)
                                <p class="text-gray-400 mt-2 text-sm line-clamp-2">
                                    {{ $building->description }}
                                </p>
                            @endif
                            <div class="mt-3 text-sm text-gray-500">
                                {{ $building->locations->count() }} lokasi
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">Belum ada gedung tersedia</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>