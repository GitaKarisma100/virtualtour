@extends('admin.master')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-sm pb-sm">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary tracking-tight">
                {{ isset($location) ? 'Edit Location' : 'Add Location' }}
            </h2>
            <p class="text-body-md font-body-md text-secondary">
                {{ isset($location) ? 'Update location details.' : 'Add a new 360° panorama location.' }}
            </p>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm p-lg">
        <form action="{{ isset($location) ? route('admin.buildings.locations.update', [$building, $location]) : route('admin.buildings.locations.store', $building) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($location))
                @method('PUT')
            @endif

            <div class="space-y-lg max-w-xl">
                <div>
                    <label class="text-label-md font-label-md text-primary mb-base block">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $location->name ?? '') }}"
                        class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none"
                        required />
                    @error('name')
                        <p class="text-label-md text-error mt-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-label-md font-label-md text-primary mb-base block">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none">{{ old('description', $location->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="text-label-md font-label-md text-primary mb-base block">360° Image *</label>
                    @if(isset($location) && $location->image_path)
                        <div class="mb-sm">
                            <img src="{{ asset('storage/'.$location->image_path) }}" alt="Current image" class="h-32 w-48 rounded object-cover">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                        class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all"
                        {{ isset($location) ? '' : 'required' }} />
                    @error('image')
                        <p class="text-label-md text-error mt-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-3 gap-md">
                    <div>
                        <label class="text-label-md font-label-md text-primary mb-base block">HFOV (°)</label>
                        <input type="number" name="hfov" value="{{ old('hfov', $location->hfov ?? 90) }}" step="any" min="1" max="360"
                            class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none" />
                    </div>
                    <div>
                        <label class="text-label-md font-label-md text-primary mb-base block">Yaw (°)</label>
                        <input type="number" name="yaw" value="{{ old('yaw', $location->yaw ?? 0) }}" step="any" min="-180" max="180"
                            class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none" />
                    </div>
                    <div>
                        <label class="text-label-md font-label-md text-primary mb-base block">Pitch (°)</label>
                        <input type="number" name="pitch" value="{{ old('pitch', $location->pitch ?? 0) }}" step="any" min="-90" max="90"
                            class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none" />
                    </div>
                </div>

                <div>
                    <label class="text-label-md font-label-md text-primary mb-base block">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $location->sort_order ?? 0) }}" min="0"
                        class="w-32 px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none" />
                </div>

                <div class="flex items-center gap-sm">
                    <input type="hidden" name="is_active" value="0" />
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        {{ old('is_active', $location->is_active ?? true) ? 'checked' : '' }}
                        class="h-5 w-5 accent-primary" />
                    <label for="is_active" class="text-label-md font-label-md text-primary">Active</label>
                </div>

                <div class="flex items-center gap-sm pt-md">
                    <button type="submit"
                        class="flex items-center gap-sm px-lg py-sm bg-primary text-on-primary rounded-lg hover:opacity-90 active:scale-[0.98] transition-all">
                        <span class="text-label-md font-label-md">{{ isset($location) ? 'Update' : 'Create' }}</span>
                    </button>
                    <a href="{{ route('admin.buildings.locations.index', $building) }}"
                        class="px-lg py-sm border border-outline-variant rounded-lg hover:bg-surface-container text-label-md transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
