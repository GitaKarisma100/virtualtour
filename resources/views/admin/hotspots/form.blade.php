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
        <form action="{{ isset($hotspot) ? route('admin.buildings.locations.hotspots.update', [$building, $location, $hotspot]) : route('admin.buildings.locations.hotspots.store', [$building, $location]) }}" method="POST" enctype="multipart/form-data">
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

                <div id="info-fields" class="space-y-lg {{ old('type', $hotspot->type ?? '') !== 'info' ? 'hidden' : '' }}">
                    <div>
                        <label class="text-label-md font-label-md text-primary mb-base block">Thumbnail</label>
                        @if(isset($hotspot) && $hotspot->thumbnail_path)
                            <div class="mb-sm">
                                <img src="{{ asset('storage/'.$hotspot->thumbnail_path) }}" alt="Current thumbnail" class="h-24 rounded object-cover">
                            </div>
                        @endif
                        <input type="file" name="thumbnail" accept="image/*"
                            class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all" />
                        @error('thumbnail')
                            <p class="text-label-md text-error mt-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-label-md font-label-md text-primary mb-base block">URL</label>
                        <input type="url" name="url" id="hotspot-url" value="{{ old('url', $hotspot->url ?? '') }}"
                            class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none" />
                        <div id="yt-preview" class="mt-md hidden"></div>
                    </div>
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
                        class="px-lg py-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-label-md transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        const typeEl = document.getElementById('type');
        const urlEl  = document.getElementById('hotspot-url');
        const ytPrev = document.getElementById('yt-preview');

        typeEl.addEventListener('change', function() {
            document.getElementById('target-field').classList.toggle('hidden', this.value !== 'navigation');
            document.getElementById('info-fields').classList.toggle('hidden', this.value !== 'info');
            updateYtPreview();
        });

        function getYtId(url) {
            if (!url) return null;
            const patterns = [
                /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/,
                /^([a-zA-Z0-9_-]{11})$/
            ];
            for (const p of patterns) {
                const m = url.match(p);
                if (m) return m[1];
            }
            return null;
        }

        function updateYtPreview() {
            if (typeEl.value !== 'info') {
                ytPrev.classList.add('hidden');
                ytPrev.innerHTML = '';
                return;
            }
            const id = getYtId(urlEl.value.trim());
            if (id) {
                ytPrev.innerHTML = `
                    <p class="text-label-md text-secondary mb-sm">YouTube Preview</p>
                    <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:8px;background:#000">
                        <iframe src="https://www.youtube.com/embed/${id}" frameborder="0"
                            allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture"
                            allowfullscreen
                            style="position:absolute;top:0;left:0;width:100%;height:100%">
                        </iframe>
                    </div>
                `;
                ytPrev.classList.remove('hidden');
            } else {
                ytPrev.classList.add('hidden');
                ytPrev.innerHTML = '';
            }
        }

        urlEl.addEventListener('input', updateYtPreview);
        updateYtPreview();
    </script>
@endsection
