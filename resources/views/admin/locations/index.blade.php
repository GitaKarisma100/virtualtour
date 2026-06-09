@extends('admin.master')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-sm pb-sm">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary tracking-tight">
                Locations — {{ $building->name }}
            </h2>
            <p class="text-body-md font-body-md text-secondary">Kelola lokasi panorama 360°</p>
        </div>
        <a href="{{ route('admin.buildings.locations.create', $building) }}"
            class="flex items-center gap-sm px-lg py-sm bg-primary text-on-primary rounded-lg hover:opacity-90 active:scale-[0.98] transition-all">
            <span class="material-symbols-outlined text-[18px]" data-icon="add">add</span>
            <span class="text-label-md font-label-md">Tambah Lokasi</span>
        </a>
    </div>


    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[640px]">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Gambar</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Nama</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">HFOV</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Yaw</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Pitch</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Status</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container">
                    @forelse($locations as $location)
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="px-lg py-md">
                                <img src="{{ asset('storage/'.$location->image_path) }}" alt="{{ $location->name }}" class="h-10 w-10 rounded object-cover">
                            </td>
                            <td class="px-lg py-md">
                                <p class="text-body-md font-bold text-primary">{{ $location->name }}</p>
                            </td>
                            <td class="px-lg py-md text-body-md text-secondary">{{ $location->hfov }}°</td>
                            <td class="px-lg py-md text-body-md text-secondary">{{ $location->yaw }}°</td>
                            <td class="px-lg py-md text-body-md text-secondary">{{ $location->pitch }}°</td>
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-sm">
                                    <div class="h-2 w-2 rounded-full {{ $location->is_active ? 'bg-primary' : 'bg-outline' }}"></div>
                                    <span class="text-body-md {{ $location->is_active ? 'text-primary' : 'text-secondary' }}">
                                        {{ $location->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-lg py-md text-right">
                                <div class="flex items-center justify-end gap-sm">
                                    <a href="{{ route('admin.buildings.locations.hotspots.index', [$building, $location]) }}" class="p-sm rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors" title="Manage Hotspots">
                                        <span class="material-symbols-outlined" data-icon="pin_drop">pin_drop</span>
                                    </a>
                                    <a href="{{ route('admin.buildings.locations.edit', [$building, $location]) }}" class="p-sm rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Edit">
                                        <span class="material-symbols-outlined" data-icon="edit">edit</span>
                                    </a>
                                    <form action="{{ route('admin.buildings.locations.destroy', [$building, $location]) }}" method="POST" class="inline" data-confirm="Yakin ingin menghapus lokasi ini? Semua hotspot di dalamnya juga akan terhapus.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-sm rounded-lg bg-red-50 text-red-500 hover:bg-red-100 transition-colors" title="Delete">
                                            <span class="material-symbols-outlined" data-icon="delete">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-lg py-xl text-center text-body-md text-secondary">No locations found. Add one to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($locations->hasPages())
            <div class="px-lg py-md flex flex-col sm:flex-row items-center justify-between gap-sm border-t border-outline-variant bg-surface-container-low">
                <p class="text-label-md text-secondary">Showing {{ $locations->firstItem() }} to {{ $locations->lastItem() }} of {{ $locations->total() }} locations</p>
                <div class="flex items-center gap-sm">
                    {{ $locations->links() }}
                </div>
            </div>
        @endif
    </div>

    <div class="pt-lg">
        <a href="{{ route('admin.buildings.index') }}"
            class="px-lg py-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-label-md transition-colors">
            ← Back to Buildings
        </a>
    </div>
@endsection
