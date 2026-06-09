@extends('admin.master')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-sm pb-sm">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary tracking-tight">
                {{ isset($building) ? 'Edit Building' : 'Add Building' }}
            </h2>
            <p class="text-body-md font-body-md text-secondary">
                {{ isset($building) ? 'Update building details.' : 'Add a new campus building or area.' }}
            </p>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm p-lg">
        <form action="{{ isset($building) ? route('admin.buildings.update', $building) : route('admin.buildings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($building))
                @method('PUT')
            @endif

            <div class="space-y-lg max-w-xl">
                <div>
                    <label class="text-label-md font-label-md text-primary mb-base block">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $building->name ?? '') }}"
                        class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none"
                        required />
                    @error('name')
                        <p class="text-label-md text-error mt-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-label-md font-label-md text-primary mb-base block">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none">{{ old('description', $building->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-label-md text-error mt-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-md">
                    <div>
                        <label class="text-label-md font-label-md text-primary mb-base block">Latitude</label>
                        <input type="number" name="latitude" value="{{ old('latitude', $building->latitude ?? '') }}" step="any" min="-90" max="90"
                            class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none" placeholder="-8.2186" />
                        @error('latitude')
                            <p class="text-label-md text-error mt-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-label-md font-label-md text-primary mb-base block">Longitude</label>
                        <input type="number" name="longitude" value="{{ old('longitude', $building->longitude ?? '') }}" step="any" min="-180" max="180"
                            class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none" placeholder="114.3667" />
                        @error('longitude')
                            <p class="text-label-md text-error mt-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="text-label-md font-label-md text-primary mb-base block">Thumbnail</label>
                    @if(isset($building) && $building->thumbnail_path)
                        <div class="mb-sm">
                            <img src="{{ asset('storage/'.$building->thumbnail_path) }}" alt="Current thumbnail" class="h-20 w-20 rounded object-cover">
                        </div>
                    @endif
                    <input type="file" name="thumbnail" accept="image/*"
                        class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all" />
                    @error('thumbnail')
                        <p class="text-label-md text-error mt-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-label-md font-label-md text-primary mb-base block">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $building->sort_order ?? 0) }}" min="0"
                        class="w-32 px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none" />
                </div>

                <div class="flex items-center gap-sm">
                    <input type="hidden" name="is_active" value="0" />
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        {{ old('is_active', $building->is_active ?? true) ? 'checked' : '' }}
                        class="h-5 w-5 accent-primary" />
                    <label for="is_active" class="text-label-md font-label-md text-primary">Active</label>
                </div>

                <div class="flex items-center gap-sm pt-md">
                    <button type="submit"
                        class="flex items-center gap-sm px-lg py-sm bg-primary text-on-primary rounded-lg hover:opacity-90 active:scale-[0.98] transition-all">
                        <span class="text-label-md font-label-md">{{ isset($building) ? 'Update' : 'Create' }}</span>
                    </button>
                 
                    <a href="{{ route('admin.buildings.index') }}"
                        class="px-lg py-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-label-md transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
