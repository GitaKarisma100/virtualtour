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

            <div class="space-y-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                    <div class="space-y-lg">
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
                    </div>
                    <div>
                        <label class="text-label-md font-label-md text-primary mb-base block">360° Image *</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all"
                            {{ isset($location) ? '' : 'required' }} />
                        @error('image')
                            <p class="text-label-md text-error mt-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="border-outline-variant">

                <div id="preview-section" class="{{ isset($location) && $location->image_path ? '' : 'hidden' }}">
                    <label class="text-label-md font-label-md text-primary mb-base block">Preview</label>
                    <div id="preview-container" class="w-full rounded-lg overflow-hidden bg-surface-container-low border border-outline-variant" style="height: 360px;"></div>
                    <p class="text-label-md text-secondary mt-xs">Drag to rotate &bull; Scroll to zoom &bull; Adjust view then save</p>
                </div>

                <hr class="border-outline-variant">

                <div class="grid grid-cols-3 gap-md">
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
                    <div>
                        <label class="text-label-md font-label-md text-primary mb-base block">HFOV (°)</label>
                        <input type="number" name="hfov" value="{{ old('hfov', $location->hfov ?? 90) }}" step="any" min="1" max="360"
                            class="w-full px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none" />
                    </div>
                </div>

                <div class="flex items-center gap-lg flex-wrap">
                    <div>
                        <label class="text-label-md font-label-md text-primary mb-base block">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $location->sort_order ?? 0) }}" min="0"
                            class="w-24 px-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none" />
                    </div>
                    <div class="flex items-center gap-sm pt-lg">
                        <input type="hidden" name="is_active" value="0" />
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $location->is_active ?? true) ? 'checked' : '' }}
                            class="h-5 w-5 accent-primary" />
                        <label for="is_active" class="text-label-md font-label-md text-primary">Active</label>
                    </div>
                </div>

                <div class="flex items-center gap-sm pt-md">
                    <button type="submit"
                        class="flex items-center gap-sm px-lg py-sm bg-primary text-on-primary rounded-lg hover:opacity-90 active:scale-[0.98] transition-all">
                        <span class="text-label-md font-label-md">{{ isset($location) ? 'Update' : 'Create' }}</span>
                    </button>
                    <a href="{{ route('admin.buildings.locations.index', $building) }}"
                        class="px-lg py-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-label-md transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@photo-sphere-viewer/core@5.4.4/index.min.css">
<style>
  #preview-container .psv-container { border-radius: 0.5rem; }
  #preview-container .psv-loader-container { background: transparent; }
</style>
@endpush

@push('scripts')
<script type="importmap">
{
  "imports": {
    "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
    "@photo-sphere-viewer/core": "https://cdn.jsdelivr.net/npm/@photo-sphere-viewer/core@5.4.4/index.module.js"
  }
}
</script>
<script type="module">
import { Viewer } from '@photo-sphere-viewer/core';

let viewer = null;
const container = document.getElementById('preview-container');
const previewSection = document.getElementById('preview-section');

function toDeg(rad) {
  return rad * 180 / Math.PI;
}

function initPreview(imageSrc) {
  if (viewer) {
    viewer.destroy();
    viewer = null;
  }

  const yaw = parseFloat(document.querySelector('[name="yaw"]').value) || 0;
  const pitch = parseFloat(document.querySelector('[name="pitch"]').value) || 0;

  viewer = new Viewer({
    container,
    panorama: imageSrc,
    defaultYaw: yaw + 'deg',
    defaultPitch: pitch + 'deg',
    defaultZoomLvl: 0,
    minFov: 30,
    maxFov: 120,
    navbar: false,
    caption: '',
  });

  viewer.addEventListener('position-change', (e) => {
    document.querySelector('[name="yaw"]').value = toDeg(e.yaw).toFixed(1);
    document.querySelector('[name="pitch"]').value = toDeg(e.pitch).toFixed(1);
  });
}

['yaw', 'pitch'].forEach(name => {
  const input = document.querySelector(`[name="${name}"]`);
  if (input) {
    input.addEventListener('input', () => {
      if (!viewer) return;
      const yaw = parseFloat(document.querySelector('[name="yaw"]').value) || 0;
      const pitch = parseFloat(document.querySelector('[name="pitch"]').value) || 0;
      viewer.rotate({ yaw: yaw + 'deg', pitch: pitch + 'deg' });
    });
  }
});

@if(isset($location) && $location->image_path)
  initPreview('{{ asset("storage/".$location->image_path) }}');
@endif

const fileInput = document.querySelector('[name="image"]');
if (fileInput) {
  fileInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
      previewSection.classList.remove('hidden');
      initPreview(ev.target.result);
    };
    reader.readAsDataURL(file);
  });
}
</script>
@endpush

@endsection
