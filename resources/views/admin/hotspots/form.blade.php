@extends('admin.master')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-sm pb-sm">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary tracking-tight">
                {{ isset($hotspot) ? 'Edit Hotspot' : 'Add Hotspot' }}
            </h2>
            <p class="text-body-md font-body-md text-secondary">
                {{ isset($hotspot) ? 'Update hotspot details.' : 'Add a new navigation or info hotspot.' }}
            </p>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm p-lg">
        <form action="{{ isset($hotspot) ? route('admin.buildings.locations.hotspots.update', [$building, $location, $hotspot]) : route('admin.buildings.locations.hotspots.store', [$building, $location]) }}" method="POST">
            @csrf
            @if(isset($hotspot))
                @method('PUT')
            @endif

            <div class="space-y-lg max-w-xl">
                <div>
                    <label class="text-label-md font-label-md text-primary mb-base block">Type *</label>
                    <select name="type" id="type"
                        class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none">
                        <option value="navigation" {{ old('type', $hotspot->type ?? '') === 'navigation' ? 'selected' : '' }}>Navigation</option>
                        <option value="info" {{ old('type', $hotspot->type ?? '') === 'info' ? 'selected' : '' }}>Info</option>
                        <option value="external_link" {{ old('type', $hotspot->type ?? '') === 'external_link' ? 'selected' : '' }}>External Link</option>
                    </select>
                    @error('type')
                        <p class="text-label-md text-error mt-xs">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-label-md font-label-md text-primary mb-base block">Label</label>
                    <input type="text" name="label" value="{{ old('label', $hotspot->label ?? '') }}"
                        class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none" />
                </div>

                <div>
                    <label class="text-label-md font-label-md text-primary mb-base block">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none">{{ old('description', $hotspot->description ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-md">
                    <div>
                        <label class="text-label-md font-label-md text-primary mb-base block">Yaw (°) *</label>
                        <input type="number" name="yaw" value="{{ old('yaw', $hotspot->yaw ?? 0) }}" step="any" min="-180" max="180"
                            class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none"
                            required />
                    </div>
                    <div>
                        <label class="text-label-md font-label-md text-primary mb-base block">Pitch (°) *</label>
                        <input type="number" name="pitch" value="{{ old('pitch', $hotspot->pitch ?? 0) }}" step="any" min="-90" max="90"
                            class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none"
                            required />
                    </div>
                </div>

                <div id="target-field" class="{{ old('type', $hotspot->type ?? '') !== 'navigation' ? 'hidden' : '' }}">
                    <label class="text-label-md font-label-md text-primary mb-base block">Target Location</label>
                    <select name="target_location_id"
                        class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none">
                        <option value="">— Select —</option>
                        @foreach($otherLocations as $loc)
                            <option value="{{ $loc->id }}" {{ old('target_location_id', $hotspot->target_location_id ?? '') == $loc->id ? 'selected' : '' }}>
                                {{ $loc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="url-field" class="{{ old('type', $hotspot->type ?? '') !== 'external_link' ? 'hidden' : '' }}">
                    <label class="text-label-md font-label-md text-primary mb-base block">URL</label>
                    <input type="url" name="url" value="{{ old('url', $hotspot->url ?? '') }}"
                        class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none" />
                </div>

                <div>
                    <label class="text-label-md font-label-md text-primary mb-base block">Icon (Material Symbol name)</label>
                    <input type="text" name="icon" value="{{ old('icon', $hotspot->icon ?? '') }}"
                        placeholder="e.g. info, location_on"
                        class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none" />
                </div>

                <div class="flex items-center gap-sm pt-md">
                    <button type="submit"
                        class="flex items-center gap-sm px-lg py-sm bg-primary text-on-primary rounded-lg hover:opacity-90 active:scale-[0.98] transition-all">
                        <span class="text-label-md font-label-md">{{ isset($hotspot) ? 'Update' : 'Create' }}</span>
                    </button>
                    <a href="{{ route('admin.buildings.locations.hotspots.index', [$building, $location]) }}"
                        class="px-lg py-sm border border-outline-variant rounded-lg hover:bg-surface-container text-label-md transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('type').addEventListener('change', function() {
            document.getElementById('target-field').classList.toggle('hidden', this.value !== 'navigation');
            document.getElementById('url-field').classList.toggle('hidden', this.value !== 'external_link');
        });
    </script>
@endsection
