@extends('admin.master')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-sm pb-sm">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary tracking-tight">Buildings</h2>
            <p class="text-body-md font-body-md text-secondary">Manage campus buildings and areas.</p>
        </div>
        <a href="{{ route('admin.buildings.create') }}"
            class="flex items-center gap-sm px-lg py-sm bg-primary text-on-primary rounded-lg hover:opacity-90 active:scale-[0.98] transition-all">
            <span class="material-symbols-outlined text-[18px]" data-icon="add">add</span>
            <span class="text-label-md font-label-md">Add Building</span>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-lg p-md bg-surface-container-low border border-outline-variant rounded-xl text-body-md text-primary">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[640px]">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Thumbnail</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Name</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Locations</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">Status</th>
                        <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container">
                    @forelse($buildings as $building)
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="px-lg py-md">
                                @if($building->thumbnail_path)
                                    <img src="{{ asset('storage/'.$building->thumbnail_path) }}" alt="{{ $building->name }}" class="h-10 w-10 rounded object-cover">
                                @else
                                    <div class="h-10 w-10 bg-surface-container-highest text-primary rounded flex items-center justify-center font-bold text-label-md">
                                        {{ strtoupper(substr($building->name, 0, 2)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-lg py-md">
                                <p class="text-body-md font-bold text-primary">{{ $building->name }}</p>
                            </td>
                            <td class="px-lg py-md">
                                <span class="text-body-md text-secondary">{{ $building->locations->count() }}</span>
                            </td>
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-sm">
                                    <div class="h-2 w-2 rounded-full {{ $building->is_active ? 'bg-primary' : 'bg-outline' }}"></div>
                                    <span class="text-body-md {{ $building->is_active ? 'text-primary' : 'text-secondary' }}">
                                        {{ $building->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-lg py-md text-right">
                                <div class="flex items-center justify-end gap-sm">
                                    <a href="{{ route('admin.buildings.locations.index', $building) }}" class="p-sm text-secondary hover:text-primary transition-colors" title="Manage Locations">
                                        <span class="material-symbols-outlined" data-icon="photo_library">photo_library</span>
                                    </a>
                                    <a href="{{ route('admin.buildings.edit', $building) }}" class="p-sm text-secondary hover:text-primary transition-colors" title="Edit">
                                        <span class="material-symbols-outlined" data-icon="edit">edit</span>
                                    </a>
                                    <form action="{{ route('admin.buildings.destroy', $building) }}" method="POST" class="inline" onsubmit="return confirm('Delete this building?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-sm text-secondary hover:text-error transition-colors" title="Delete">
                                            <span class="material-symbols-outlined" data-icon="delete">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-lg py-xl text-center text-body-md text-secondary">No buildings found. Add one to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($buildings->hasPages())
            <div class="px-lg py-md flex flex-col sm:flex-row items-center justify-between gap-sm border-t border-outline-variant bg-surface-container-low">
                <p class="text-label-md text-secondary">Showing {{ $buildings->firstItem() }} to {{ $buildings->lastItem() }} of {{ $buildings->total() }} buildings</p>
                <div class="flex items-center gap-sm">
                    {{ $buildings->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
