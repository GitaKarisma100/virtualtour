@extends('admin.master')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-sm pb-sm">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary tracking-tight">
                Hotspots — {{ $location->name }}
            </h2>
            <p class="text-body-md font-body-md text-secondary">Manage navigation and info points.</p>
        </div>
        <a href="{{ route('admin.buildings.locations.hotspots.create', [$building, $location]) }}"
            class="flex items-center gap-sm px-lg py-sm bg-primary text-on-primary rounded-lg hover:opacity-90 active:scale-[0.98] transition-all">
            <span class="material-symbols-outlined text-[18px]" data-icon="add">add</span>
            <span class="text-label-md font-label-md">Add Hotspot</span>
        </a>
    </div>


    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[640px]">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Label</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Type</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Yaw</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Pitch</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Thumbnail</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Target</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container">
                    @forelse($hotspots as $hotspot)
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="px-lg py-md">
                                <p class="text-body-md font-bold text-primary">{{ $hotspot->label ?? '—' }}</p>
                                @if($hotspot->description)
                                    <p class="text-label-md text-secondary mt-xs max-w-xs truncate">{{ $hotspot->description }}</p>
                                @endif
                            </td>
                            <td class="px-lg py-md">
                                <span class="px-sm py-base rounded text-label-md font-bold
                                    {{ $hotspot->type === 'navigation' ? 'bg-primary text-on-primary' : 'bg-surface-container text-primary' }}">
                                    {{ $hotspot->type === 'external_link' ? 'Info' : ucfirst(str_replace('_', ' ', $hotspot->type)) }}
                                </span>
                            </td>
                            <td class="px-lg py-md text-body-md text-secondary">{{ $hotspot->yaw }}°</td>
                            <td class="px-lg py-md text-body-md text-secondary">{{ $hotspot->pitch }}°</td>
                            <td class="px-lg py-md">
                                @if($hotspot->thumbnail_path)
                                    <img src="{{ asset('storage/'.$hotspot->thumbnail_path) }}" alt="" class="h-10 w-14 rounded object-cover">
                                @else
                                    <span class="text-body-md text-secondary">—</span>
                                @endif
                            </td>
                            <td class="px-lg py-md text-body-md text-secondary">
                                {{ $hotspot->targetLocation ? $hotspot->targetLocation->name : ($hotspot->url ?? '—') }}
                            </td>
                            <td class="px-lg py-md text-right">
                                <div class="flex items-center justify-end gap-sm">
                                    <a href="{{ route('admin.buildings.locations.hotspots.edit', [$building, $location, $hotspot]) }}" class="p-sm rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Edit">
                                        <span class="material-symbols-outlined" data-icon="edit">edit</span>
                                    </a>
                                    <form action="{{ route('admin.buildings.locations.hotspots.destroy', [$building, $location, $hotspot]) }}" method="POST" class="inline" data-confirm="Yakin ingin menghapus hotspot ini?">
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
                            <td colspan="7" class="px-lg py-xl text-center text-body-md text-secondary">No hotspots found. Add one to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pt-lg flex items-center gap-sm">
        <a href="{{ route('admin.buildings.locations.index', $building) }}"
            class="px-lg py-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-label-md transition-colors">
            ← Back to Locations
        </a>
    </div>
@endsection
