<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $building->name }} - Virtual Tour</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@photo-sphere-viewer/core@5.4.4/index.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0a0a0a;
            font-family: 'Segoe UI', sans-serif;
            overflow: hidden;
        }

        #viewer {
            width: 100vw;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1;
        }

        #map-container {
            position: fixed;
            top: 24px;
            left: 24px;
            z-index: 999;
            width: 220px;
            height: 220px;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        #leaflet-map {
            width: 100%;
            flex: 1;
        }

        #leaflet-map .leaflet-control-zoom {
            display: none;
        }

        #leaflet-map .leaflet-control-attribution {
            font-size: 8px;
        }

        #map-label {
            padding: 5px 10px 7px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            color: #fff;
            pointer-events: none;
            flex-shrink: 0;
        }

        #map-label .name {
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #map-label .desc {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.4);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #loading {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #0a0a0a;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 14px;
            transition: opacity .5s;
        }

        #loading.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .spin {
            width: 34px;
            height: 34px;
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-top-color: #4fc3f7;
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        #loading p {
            color: rgba(255, 255, 255, 0.35);
            font-size: 13px;
        }

        .mm-tooltip {
            background: rgba(0, 0, 0, 0.85) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #fff !important;
            font-size: 11px !important;
            font-weight: 500 !important;
            padding: 3px 8px !important;
            border-radius: 6px !important;
            box-shadow: none !important;
        }

        .mm-tooltip::before {
            border-top-color: rgba(0, 0, 0, 0.85) !important;
        }

        #back-btn {
            position: fixed;
            top: 24px;
            right: 150px;
            z-index: 999;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 10px 16px;
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-family: inherit;
            cursor: pointer;
        }

        #back-btn:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        /* ── Bottom Panel ── */
        #bottom-panel {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 999;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            display: flex;
            flex-direction: column;
            transition: transform .3s cubic-bezier(.22, 1, .36, 1), opacity .3s;
        }

        #bottom-panel.hidden {
            transform: translateY(100%);
            opacity: 0;
            pointer-events: none;
        }

        #toggle-panel-btn {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-bottom: none;
            border-radius: 8px 8px 0 0;
            padding: 4px 14px 2px;
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            font-size: 10px;
            letter-spacing: 1px;
            transition: color .2s, background .2s;
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        #toggle-panel-btn:hover {
            color: #fff;
            background: rgba(0, 0, 0, 0.6);
        }

        #toggle-panel-btn.panel-hidden {
            bottom: 0;
            border-radius: 8px 8px 0 0;
        }

        #toggle-panel-btn svg {
            transition: transform .3s;
        }

        #toggle-panel-btn.panel-hidden svg {
            transform: rotate(180deg);
        }

        /* Thumbnail Strip */
        #thumbnail-strip {
            display: flex;
            gap: 6px;
            overflow-x: auto;
            padding: 10px 10px 6px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.18) transparent;
        }

        #thumbnail-strip::-webkit-scrollbar {
            height: 4px;
        }

        #thumbnail-strip::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }

        .thumb-item {
            flex-shrink: 0;
            position: relative;
            width: 130px;
            height: 74px;
            border-radius: 5px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid rgba(255, 255, 255, 0.12);
            transition: border-color .2s, transform .15s;
        }

        .thumb-item:hover {
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        .thumb-item.active {
            border-color: #fff;
        }

        .thumb-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .thumb-item .thumb-name {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 5px 7px;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.75) 0%, transparent 100%);
            color: #fff;
            font-size: 10px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
        }

        /* Controls Bar */
        #controls-bar {
            display: flex;
            align-items: center;
            gap: 2px;
            padding: 6px 12px 10px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .ctrl-btn {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.65);
            cursor: pointer;
            width: 30px;
            height: 30px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: background .15s, color .15s;
            flex-shrink: 0;
        }

        .ctrl-btn:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .ctrl-btn:active {
            background: rgba(255, 255, 255, 0.2);
        }

        .ctrl-btn svg {
            pointer-events: none;
        }

        .ctrl-sep {
            width: 1px;
            height: 18px;
            background: rgba(255, 255, 255, 0.12);
            margin: 0 6px;
            flex-shrink: 0;
        }

        #zoom-slider {
            -webkit-appearance: none;
            appearance: none;
            width: 80px;
            height: 3px;
            background: rgba(255, 255, 255, 0.22);
            border-radius: 2px;
            outline: none;
            cursor: pointer;
            flex-shrink: 0;
        }

        #zoom-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: #fff;
            cursor: pointer;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.5);
        }

        #zoom-slider::-moz-range-thumb {
            width: 13px;
            height: 13px;
            border: none;
            border-radius: 50%;
            background: #fff;
            cursor: pointer;
        }

        #fullscreen-btn {
            margin-left: auto;
        }

        /* Sidebar */
        #sidebar {
            position: fixed;
            top: 0;
            right: 0;
            z-index: 9998;
            width: 420px;
            max-width: 100vw;
            height: 100vh;
            background: rgba(16, 16, 18, 0.94);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(255, 255, 255, 0.08);
            transform: translateX(100%);
            transition: transform .35s cubic-bezier(.22, 1, .36, 1);
            display: flex;
            flex-direction: column;
        }

        #sidebar.open {
            transform: translateX(0);
        }

        #sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 28px 0;
            flex-shrink: 0;
        }

        #sidebar-header h2 {
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.35);
            font-weight: 500;
            margin: 0;
        }

        #sidebar-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.4);
            font-size: 24px;
            cursor: pointer;
            padding: 4px;
            line-height: 1;
        }

        #sidebar-close:hover {
            color: #fff;
        }

        #sidebar-body {
            padding: 20px 28px 28px;
            overflow-y: auto;
            flex: 1;
        }

        #sidebar-body h3 {
            font-size: 22px;
            font-weight: 600;
            color: #fff;
            margin: 0 0 6px;
        }

        #sidebar-body hr {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            margin: 16px 0;
        }

        #sidebar-body p {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.55);
            line-height: 1.7;
            margin: 0;
        }

        #sidebar-thumb {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 12px;
            display: none;
        }

        #sidebar-yt {
            width: 100%;
            margin-top: 16px;
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 8px;
            background: #000;
        }

        #sidebar-yt iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        #sidebar-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 16px;
            padding: 8px 20px;
            background: rgba(79, 195, 247, 0.15);
            color: #4fc3f7;
            border: 1px solid rgba(79, 195, 247, 0.3);
            border-radius: 8px;
            font-size: 13px;
            text-decoration: none;
            transition: all .2s;
        }

        #sidebar-link:hover {
            background: rgba(79, 195, 247, 0.25);
        }

        /* ── Building Panel (Right) ── */
        #building-panel {
            position: fixed;
            top: 0;
            right: 0;
            z-index: 1001;
            width: 300px;
            height: 100vh;
            background: rgba(16, 16, 18, 0.92);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(255, 255, 255, 0.08);
            transform: translateX(100%);
            transition: transform .35s cubic-bezier(.22, 1, .36, 1), opacity .3s;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        #building-panel.open {
            transform: translateX(0);
        }

        #building-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 20px 14px;
            flex-shrink: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        #building-panel-header h2 {
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.35);
            font-weight: 500;
            margin: 0;
        }

        #building-panel-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.4);
            font-size: 20px;
            cursor: pointer;
            padding: 2px 4px;
            line-height: 1;
        }

        #building-panel-close:hover {
            color: #fff;
        }

        #building-panel-body {
            padding: 14px;
            overflow-y: auto;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .building-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
            color: #fff;
        }

        .building-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.12);
        }

        .building-card.active {
            background: rgba(79, 195, 247, 0.12);
            border-color: rgba(79, 195, 247, 0.35);
        }

        .building-card-thumb {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.08);
        }

        .building-card-thumb-placeholder {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .building-card-info {
            flex: 1;
            min-width: 0;
        }

        .building-card-name {
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .building-card-meta {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 2px;
        }

        #toggle-building-btn {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 1000;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 10px 16px;
            color: #fff;
            font-size: 13px;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background .2s;
        }

        #toggle-building-btn:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        /* Ground Navigation Wrapper */
        #ground-nav-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 998;
        }

        /* Individual navigation buttons */
        .ground-nav-btn {
            position: fixed;
            pointer-events: auto;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            width: 80px;
            height: 44px;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            opacity: 0;
            visibility: hidden;
            transform: scale(0.8) scaleY(0);
        }

        .ground-nav-btn:hover {
            background: rgba(0, 0, 0, 0.8);
            border-color: rgba(255, 255, 255, 0.4);
            transform: scale(1.1) scaleY(1);
        }

        .ground-nav-btn:active {
            transform: scale(0.95) scaleY(1);
        }

        .ground-nav-btn svg {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        /* State: VISIBLE di ground */
        .ground-nav-btn.visible {
            opacity: 1;
            visibility: visible;
            transform: scale(1) scaleY(1);
        }

        /* Disable state - saat di ujung (first/last location) */
        .ground-nav-btn.disabled {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
</head>

<body>

    <div id="loading">
        <div class="spin"></div>
        <p>Memuat virtual tour…</p>
    </div>
    <div id="viewer"></div>

    <!-- Ground Navigation Container -->
    <div id="ground-nav-container" class="ground-nav-wrapper">
        <button id="ground-nav-next" class="ground-nav-btn ground-nav-btn-next" title="Lokasi Selanjutnya">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z" />
            </svg>
        </button>

        <button id="ground-nav-prev" class="ground-nav-btn ground-nav-btn-prev" title="Lokasi Sebelumnya">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6z" />
            </svg>
        </button>
    </div>

    <!-- Old nav arrows removed - replaced by ground navigation -->
    <!--
    <button id="nav-prev" class="nav-arrow hidden" title="Lokasi Sebelumnya">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="48" fill="rgba(255,255,255,0.95)" stroke="rgba(255,255,255,0.7)" stroke-width="1.5"/>
            <circle cx="50" cy="50" r="40" fill="none" stroke="rgba(100,100,100,0.5)" stroke-width="1.5"/>
            <circle cx="50" cy="50" r="32" fill="none" stroke="rgba(100,100,100,0.5)" stroke-width="1.5"/>
            <polyline points="30,50 50,30 70,50" fill="none" stroke="rgba(40,40,40,0.9)" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
    <button id="nav-next" class="nav-arrow hidden" title="Lokasi Selanjutnya">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="48" fill="rgba(255,255,255,0.95)" stroke="rgba(255,255,255,0.7)" stroke-width="1.5"/>
            <circle cx="50" cy="50" r="40" fill="none" stroke="rgba(100,100,100,0.5)" stroke-width="1.5"/>
            <circle cx="50" cy="50" r="32" fill="none" stroke="rgba(100,100,100,0.5)" stroke-width="1.5"/>
            <polyline points="30,50 50,70 70,50" fill="none" stroke="rgba(40,40,40,0.9)" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
    -->

    <div id="map-container">
        <div id="leaflet-map"></div>
        <div id="map-label">
            <div class="name" id="loc-name">—</div>
            <div class="desc" id="loc-desc"></div>
        </div>
    </div>

    <a href="{{ route('tour.index') }}" id="back-btn">← Kembali</a>

    <!-- Building Panel Toggle -->
    <button id="toggle-building-btn" title="Daftar Gedung">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
        GEDUNG
    </button>

    <!-- Building Panel -->
    <div id="building-panel">
        <div id="building-panel-header">
            <h2>Gedung</h2>
            <button id="building-panel-close">✕</button>
        </div>
        <div id="building-panel-body">
            @foreach($buildings as $b)
                <a href="{{ route('tour.show', $b) }}"
                   class="building-card {{ $b->id === $building->id ? 'active' : '' }}">
                    @if($b->thumbnail_path)
                        <img src="{{ asset('storage/' . $b->thumbnail_path) }}"
                             alt="{{ $b->name }}"
                             class="building-card-thumb">
                    @else
                        <div class="building-card-thumb-placeholder">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                        </div>
                    @endif
                    <div class="building-card-info">
                        <div class="building-card-name">{{ $b->name }}</div>
                        <div class="building-card-meta">{{ $b->locations_count }} lokasi</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Toggle Button -->
    <button id="toggle-panel-btn" title="Sembunyikan/tampilkan panel">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
            <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z" />
        </svg>
        PANEL
    </button>

    <!-- Bottom Panel: Thumbnail Strip + Controls -->
    <div id="bottom-panel">
        <div id="thumbnail-strip"></div>
        <div id="controls-bar">
            <!-- Zoom -->
            <button class="ctrl-btn" id="btn-zoom-out" title="Zoom out">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round">
                    <circle cx="11" cy="11" r="7" />
                    <line x1="16.5" y1="16.5" x2="22" y2="22" />
                    <line x1="8" y1="11" x2="14" y2="11" />
                </svg>
            </button>
            <input type="range" id="zoom-slider" min="0" max="100" value="0" title="Zoom">
            <button class="ctrl-btn" id="btn-zoom-in" title="Zoom in">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round">
                    <circle cx="11" cy="11" r="7" />
                    <line x1="16.5" y1="16.5" x2="22" y2="22" />
                    <line x1="11" y1="8" x2="11" y2="14" />
                    <line x1="8" y1="11" x2="14" y2="11" />
                </svg>
            </button>

            <div class="ctrl-sep"></div>

            <!-- Pan -->
            <button class="ctrl-btn" id="btn-pan-left" title="Putar kiri">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6z" />
                </svg>
            </button>
            <button class="ctrl-btn" id="btn-pan-right" title="Putar kanan">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z" />
                </svg>
            </button>
            <button class="ctrl-btn" id="btn-pan-up" title="Putar atas">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z" />
                </svg>
            </button>
            <button class="ctrl-btn" id="btn-pan-down" title="Putar bawah">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6z" />
                </svg>
            </button>

            <div class="ctrl-sep"></div>

            <!-- Download -->
            <button class="ctrl-btn" id="btn-download" title="Unduh panorama">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
            </button>

            <!-- Fullscreen -->
            <button class="ctrl-btn" id="fullscreen-btn" title="Layar penuh">
                <svg id="fs-icon-expand" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                    <polyline points="15 3 21 3 21 9" />
                    <polyline points="9 21 3 21 3 15" />
                    <line x1="21" y1="3" x2="14" y2="10" />
                    <line x1="3" y1="21" x2="10" y2="14" />
                </svg>
                <svg id="fs-icon-compress" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round" style="display:none">
                    <polyline points="4 14 10 14 10 20" />
                    <polyline points="20 10 14 10 14 4" />
                    <line x1="10" y1="14" x2="3" y2="21" />
                    <line x1="21" y1="3" x2="14" y2="10" />
                </svg>
            </button>
        </div>
    </div>

    <div id="sidebar">
        <div id="sidebar-header">
            <h2>Informasi</h2>
            <button id="sidebar-close" onclick="hideInfoPopup()">✕</button>
        </div>
        <div id="sidebar-body">
            <img id="sidebar-thumb" src="" alt="">
            <h3 id="sidebar-title"></h3>
            <hr>
            <p id="sidebar-desc"></p>
            <div id="sidebar-yt" class="hidden"></div>
            <a id="sidebar-link" href="#" target="_blank" rel="noopener">Buka Link</a>
        </div>
    </div>

    <script type="importmap">
{
  "imports": {
    "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
    "@photo-sphere-viewer/core": "https://cdn.jsdelivr.net/npm/@photo-sphere-viewer/core@5.4.4/index.module.js"
  }
}
</script>

    <script>
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

        function hideInfoPopup() {
            document.getElementById('sidebar').classList.remove('open');
        }

        function showInfoPopup(label, desc, thumb, url) {
            const img = document.getElementById('sidebar-thumb');
            if (thumb) {
                img.src = thumb;
                img.style.display = 'block';
            } else {
                img.style.display = 'none';
            }
            document.getElementById('sidebar-title').textContent = label;
            document.getElementById('sidebar-desc').textContent = desc || '';
            const link = document.getElementById('sidebar-link');
            const yt = document.getElementById('sidebar-yt');
            const ytId = getYtId(url);
            if (ytId) {
                link.style.display = 'none';
                yt.innerHTML =
                    `<iframe src="https://www.youtube.com/embed/${ytId}" allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" allowfullscreen></iframe>`;
                yt.classList.remove('hidden');
            } else {
                yt.classList.add('hidden');
                yt.innerHTML = '';
                if (url) {
                    link.href = url;
                    link.style.display = 'inline-flex';
                } else {
                    link.style.display = 'none';
                }
            }
            document.getElementById('sidebar').classList.add('open');
        }
    </script>

    <script type="module">
        import {
            Viewer
        } from '@photo-sphere-viewer/core';
        import * as THREE from 'three';

        const LOCATIONS = {!! $locationsJson !!};

        let viewer = null;
        let currentIdx = 0;
        let markerMeshes = [];
        const SPHERE_R = 1;
        const PAN_STEP = THREE.MathUtils.degToRad(15);

        function fovToZoomLevel(fov) {
            if (!fov) fov = 100;
            return ((120 - fov) / (120 - 30)) * 100;
        }

        function makeCircleCanvas(bgColor) {
            const S = 512;
            const c = document.createElement('canvas');
            c.width = c.height = S;
            const g = c.getContext('2d');
            const cx = S / 2,
                cy = S / 2;
            const r = S * 0.38;

            const grad = g.createRadialGradient(cx, cy + S * 0.18, 0, cx, cy + S * 0.18, S * 0.40);
            grad.addColorStop(0, 'rgba(0, 0, 0, 0.50)');
            grad.addColorStop(0.6, 'rgba(0, 0, 0, 0.22)');
            grad.addColorStop(1, 'rgba(0, 0, 0, 0)');
            g.fillStyle = grad;
            g.beginPath();
            g.ellipse(cx, cy + S * 0.18, S * 0.40, S * 0.12, 0, 0, Math.PI * 2);
            g.fill();

            g.beginPath();
            g.arc(cx, cy, r, 0, Math.PI * 2);
            g.fillStyle = bgColor;
            g.fill();

            return {
                canvas: c,
                cx,
                cy,
                r
            };
        }

        function makeArrowTexture() {
            const {
                canvas: c,
                cx,
                cy,
                r
            } = makeCircleCanvas('rgba(255,255,255,0.9)');
            const g = c.getContext('2d');
            const s = r * 0.55;
            const tipY = cy - s * 0.30;
            const baseY = cy + s * 0.55;

            g.beginPath();
            g.moveTo(cx - s * 0.65, baseY);
            g.lineTo(cx, tipY);
            g.lineTo(cx + s * 0.65, baseY);
            g.lineTo(cx + s * 0.25, cy + s * 0.15);
            g.lineTo(cx, cy);
            g.lineTo(cx - s * 0.25, cy + s * 0.15);
            g.closePath();
            g.fillStyle = 'rgba(40, 40, 40, 0.9)';
            g.fill();
            g.beginPath();
            g.arc(cx, cy - s * 0.08, s * 0.16, 0, Math.PI * 2);
            g.fillStyle = 'rgba(40, 40, 40, 0.9)';
            g.fill();

            return new THREE.CanvasTexture(c);
        }

        function makeInfoTexture(label) {
            const text = (label || '').trim();
            const c = document.createElement('canvas');
            const g = c.getContext('2d');

            const fontSize = 18;
            g.font = `bold ${fontSize}px sans-serif`;

            const textWidth = g.measureText(text).width;
            const rectWidth = textWidth + 30;
            const rectHeight = fontSize + 15;

            c.width = rectWidth + 20;
            c.height = rectHeight + 30;

            const cx = c.width / 2;
            const cy = c.height / 2 - 5;

            g.beginPath();
            g.roundRect(cx - rectWidth / 2, cy - rectHeight / 2, rectWidth, rectHeight, rectHeight / 2);
            g.fillStyle = 'rgba(0, 51, 102, 0.75)';
            g.fill();

            g.fillStyle = '#ffffff';
            g.textAlign = 'center';
            g.textBaseline = 'middle';
            g.font = `bold ${fontSize}px system-ui`;
            g.fillText(text, cx, cy + 1);

            return new THREE.CanvasTexture(c);
        }

        function makeLinkTexture() {
            const {
                canvas: c,
                cx,
                cy,
                r
            } = makeCircleCanvas('rgba(162, 130, 255, 0.9)');
            const g = c.getContext('2d');
            const s = r * 0.45;
            g.strokeStyle = '#fff';
            g.lineWidth = r * 0.22;
            g.lineCap = 'round';
            g.beginPath();
            g.moveTo(cx - s, cy + s * 0.3);
            g.lineTo(cx - s * 0.45, cy + s * 0.3);
            g.lineTo(cx - s * 0.45, cy - s * 0.3);
            g.lineTo(cx + s, cy - s * 0.3);
            g.stroke();
            g.beginPath();
            g.moveTo(cx + s, cy - s * 0.3);
            g.lineTo(cx + s, cy + s * 0.3);
            g.lineTo(cx + s * 0.45, cy + s * 0.3);
            g.stroke();
            return new THREE.CanvasTexture(c);
        }

        function makePrevTexture() {
            const {
                canvas: c,
                cx,
                cy,
                r
            } = makeCircleCanvas('rgba(255,255,255,0.9)');
            const g = c.getContext('2d');
            const s = r * 0.35;
            g.fillStyle = 'rgba(40, 40, 40, 0.9)';
            g.beginPath();
            g.moveTo(cx - s * 0.3, cy);
            g.lineTo(cx + s * 0.5, cy - s * 0.8);
            g.lineTo(cx + s * 0.5, cy + s * 0.8);
            g.closePath();
            g.fill();
            return new THREE.CanvasTexture(c);
        }

        function makeNextTexture() {
            const {
                canvas: c,
                cx,
                cy,
                r
            } = makeCircleCanvas('rgba(255,255,255,0.9)');
            const g = c.getContext('2d');
            const s = r * 0.35;
            g.fillStyle = 'rgba(40, 40, 40, 0.9)';
            g.beginPath();
            g.moveTo(cx + s * 0.3, cy);
            g.lineTo(cx - s * 0.5, cy - s * 0.8);
            g.lineTo(cx - s * 0.5, cy + s * 0.8);
            g.closePath();
            g.fill();
            return new THREE.CanvasTexture(c);
        }

        function clearMarkers() {
            const scene = viewer.renderer.scene;
            markerMeshes.forEach(m => {
                scene.remove(m);
                m.geometry.dispose();
                if (m.material.map) m.material.map.dispose();
                m.material.dispose();
            });
            markerMeshes = [];
        }

        function addMarkerMesh(yawDeg, pitchDeg, texture, sizeMul, userData) {
            const scene = viewer.renderer.scene;
            const yaw = THREE.MathUtils.degToRad(yawDeg);
            const pitch = THREE.MathUtils.degToRad(pitchDeg);
            const r = SPHERE_R;

            const x = r * Math.cos(pitch) * Math.sin(yaw);
            const y = r * Math.sin(pitch);
            const z = -r * Math.cos(pitch) * Math.cos(yaw);

            const size = r * 0.22 * sizeMul;

            const mesh = new THREE.Mesh(
                new THREE.PlaneGeometry(size, size),
                new THREE.MeshBasicMaterial({
                    map: texture,
                    transparent: true,
                    depthWrite: false,
                    depthTest: false,
                    side: THREE.DoubleSide,
                    alphaTest: 0.01,
                })
            );

            mesh.position.set(x, y, z);
            mesh.lookAt(0, 0, 0);
            mesh.scale.z = -1;
            mesh.renderOrder = 999;
            mesh.userData = {
                ...userData,
                phase: Math.random() * Math.PI * 2,
                position: new THREE.Vector3(x, y, z)
            };

            scene.add(mesh);
            markerMeshes.push(mesh);
            return mesh;
        }

        const arrowTex = makeArrowTexture();
        const linkTex = makeLinkTexture();

        // ════════════════════════════════════════════════════════════════
        // GROUND NAVIGATION - CURSOR TRACKING
        // ════════════════════════════════════════════════════════════════

        class GroundNavigation {
            constructor(viewer, canvas) {
                this.viewer = viewer;
                this.canvas = canvas;
                this.raycaster = new THREE.Raycaster();
                this.mouse = new THREE.Vector2();
                this.isOnGround = false;
                this.groundThreshold = -15;

                this.prevBtn = document.getElementById('ground-nav-prev');
                this.nextBtn = document.getElementById('ground-nav-next');

                this.lastMouseX = 0;
                this.lastMouseY = 0;
                this.animationFrameId = null;
                this.positionUpdateTimer = null;
                this.pendingX = 0;
                this.pendingY = 0;

                this.init();
            }

            init() {
                document.addEventListener('mousemove', (e) => this.onMouseMove(e));
                document.addEventListener('touchmove', (e) => this.onTouchMove(e), { passive: true });

                this.prevBtn.addEventListener('click', () => {
                    if (currentIdx > 0) {
                        animatedLoadLocation(currentIdx - 1, 'prev');
                    }
                });

                this.nextBtn.addEventListener('click', () => {
                    if (currentIdx < LOCATIONS.length - 1) {
                        animatedLoadLocation(currentIdx + 1, 'next');
                    }
                });

                this.startUpdateLoop();
            }

            onMouseMove(event) {
                this.lastMouseX = event.clientX;
                this.lastMouseY = event.clientY;
                this.schedulePositionUpdate(event.clientX, event.clientY);
            }

            onTouchMove(event) {
                if (event.touches.length > 0) {
                    this.lastMouseX = event.touches[0].clientX;
                    this.lastMouseY = event.touches[0].clientY;
                    this.schedulePositionUpdate(event.touches[0].clientX, event.touches[0].clientY);
                }
            }

            schedulePositionUpdate(x, y) {
                if (this.prevBtn.matches(':hover') || this.nextBtn.matches(':hover')) return;
                this.pendingX = x;
                this.pendingY = y;
                clearTimeout(this.positionUpdateTimer);
                this.positionUpdateTimer = setTimeout(() => {
                    this.updateButtonPosition(this.pendingX, this.pendingY);
                }, 200);
            }

            updateButtonPosition(x, y) {
                this.prevBtn.style.left = (x - 40) + 'px';
                this.prevBtn.style.top = (y + 12) + 'px';

                this.nextBtn.style.left = (x - 40) + 'px';
                this.nextBtn.style.top = (y - 56) + 'px';
            }

            checkIfOnGround(mouseX, mouseY) {
                const rect = this.canvas.getBoundingClientRect();

                this.mouse.x = ((mouseX - rect.left) / rect.width) * 2 - 1;
                this.mouse.y = -((mouseY - rect.top) / rect.height) * 2 + 1;

                this.raycaster.setFromCamera(this.mouse, this.viewer.renderer.camera);

                const sphere = new THREE.Sphere(new THREE.Vector3(0, 0, 0), 1);
                const point = new THREE.Vector3();
                this.raycaster.ray.intersectSphere(sphere, point);

                if (point) {
                    const spherical = new THREE.Spherical();
                    spherical.setFromVector3(point);

                    let pitch = 90 - THREE.MathUtils.radToDeg(spherical.phi);

                    return pitch < this.groundThreshold;
                }

                return false;
            }

            updateNavigation() {
                if (this.prevBtn.matches(':hover') || this.nextBtn.matches(':hover')) {
                    return;
                }

                const isGroundNow = this.checkIfOnGround(this.lastMouseX, this.lastMouseY);

                if (isGroundNow !== this.isOnGround) {
                    this.isOnGround = isGroundNow;

                    if (this.isOnGround) {
                        this.prevBtn.classList.add('visible');
                        this.nextBtn.classList.add('visible');
                        this.updateButtonPosition(this.lastMouseX, this.lastMouseY);
                    } else {
                        this.prevBtn.classList.remove('visible');
                        this.nextBtn.classList.remove('visible');
                    }
                }

                const canGoPrev = currentIdx > 0;
                const canGoNext = currentIdx < LOCATIONS.length - 1;

                this.prevBtn.classList.toggle('disabled', !canGoPrev);
                this.nextBtn.classList.toggle('disabled', !canGoNext);
            }

            startUpdateLoop() {
                const loop = () => {
                    this.updateNavigation();
                    this.animationFrameId = requestAnimationFrame(loop);
                };
                loop();
            }
        }

        let groundNav = null;
        let isAnimating = false;
        let fadeOverlay = null;

        function createFadeOverlay() {
            if (!fadeOverlay) {
                fadeOverlay = document.createElement('div');
                fadeOverlay.id = 'fade-overlay';
                fadeOverlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0);
                    pointer-events: none;
                    z-index: 5000;
                    transition: background 0.4s ease-in-out;
                `;
                document.body.appendChild(fadeOverlay);
            }
            return fadeOverlay;
        }

        async function animatedLoadLocation(idx, direction, fromYaw, fromPitch) {
            if (isAnimating || idx < 0 || idx >= LOCATIONS.length) return;
            isAnimating = true;

            const fromLoc = LOCATIONS[currentIdx];
            const toLoc = LOCATIONS[idx];
            const overlay = createFadeOverlay();

            const zoomDuration = 600;
            const fadeDuration = 400;
            const startZoom = viewer.getZoomLevel();
            const maxZoom = 85;

            try {
                // ──── PHASE 1: ROTATE 180° jika PREV ────
                if (direction === 'prev') {
                    const startPos = viewer.getPosition();
                    const targetYaw = startPos.yaw + Math.PI;
                    await new Promise((resolve) => {
                        const startTime = performance.now();
                        const rotDuration = 400;
                        const animate = (currentTime) => {
                            const elapsed = currentTime - startTime;
                            const progress = Math.min(elapsed / rotDuration, 1);
                            const eased = progress < 0.5
                                ? 2 * progress * progress
                                : -1 + (4 - 2 * progress) * progress;
                            viewer.rotate({
                                yaw: (startPos.yaw + (targetYaw - startPos.yaw) * eased) + 'rad',
                                pitch: startPos.pitch + 'rad'
                            });
                            if (progress < 1) {
                                requestAnimationFrame(animate);
                            } else {
                                resolve();
                            }
                        };
                        requestAnimationFrame(animate);
                    });
                }

                // ──── PHASE 2: ZOOM IN + FADE OUT ────
                await new Promise((resolve) => {
                    const startTime = performance.now();
                    const animate = (currentTime) => {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / zoomDuration, 1);
                        const eased = progress < 0.5
                            ? 2 * progress * progress
                            : -1 + (4 - 2 * progress) * progress;

                        const newZoom = startZoom + (maxZoom - startZoom) * eased;
                        viewer.zoom(newZoom);

                        if (progress >= 0.6) {
                            overlay.style.background = `rgba(0, 0, 0, ${(progress - 0.6) / 0.4})`;
                        }

                        if (progress < 1) {
                            requestAnimationFrame(animate);
                        } else {
                            overlay.style.background = 'rgba(0, 0, 0, 1)';
                            resolve();
                        }
                    };
                    requestAnimationFrame(animate);
                });

                // ──── PHASE 3: LOAD PANORAMA BARU ────
                currentIdx = idx;
                document.getElementById('loc-name').textContent = toLoc.name;
                document.getElementById('loc-desc').textContent = toLoc.description || '';
                updateThumbnails();
                updateMap(idx);

                await viewer.setPanorama(toLoc.image);
                
                // Tentukan POV setelah panorama dimuat
                let finalYaw = (toLoc.yaw || 0);
                let finalPitch = (toLoc.pitch || 0);
                
                if (fromYaw !== undefined) {
                    // Jika ada fromYaw yang diteruskan, gunakan itu
                    finalYaw = (fromYaw || 0);
                    finalPitch = (fromPitch || 0);
                } else if (direction === 'prev') {
                    // Jika mundur, tambahkan 180° ke yaw default
                    finalYaw = (toLoc.yaw || 0) + 180;
                }
                
                viewer.rotate({
                    yaw: finalYaw + 'deg',
                    pitch: finalPitch + 'deg'
                });
                
                clearMarkers();
                (toLoc.hotspots || []).forEach(h => {
                    if (h.type === 'info' || h.type === 'external_link') {
                        addMarkerMesh(h.yaw, h.pitch ?? -15, makeInfoTexture(h.label), 1.1, {
                            type: 'info',
                            label: h.label,
                            description: h.description,
                            thumbnail: h.thumbnail,
                            url: h.url
                        });
                    }
                });

                // ──── PHASE 4: ZOOM OUT + FADE IN ────
                await new Promise((resolve) => {
                    const startTime = performance.now();
                    const animate = (currentTime) => {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / zoomDuration, 1);
                        const eased = progress < 0.5
                            ? 2 * progress * progress
                            : -1 + (4 - 2 * progress) * progress;

                        const newZoom = maxZoom - (maxZoom - startZoom) * eased;
                        viewer.zoom(newZoom);

                        overlay.style.background = `rgba(0, 0, 0, ${1 - progress})`;

                        if (progress < 1) {
                            requestAnimationFrame(animate);
                        } else {
                            overlay.style.background = 'rgba(0, 0, 0, 0)';
                            resolve();
                        }
                    };
                    requestAnimationFrame(animate);
                });

                if (groundNav) {
                    groundNav.updateNavigation();
                }

            } finally {
                isAnimating = false;
            }
        }

        function startAnim() {
            const loop = (t) => {
                requestAnimationFrame(loop);
                markerMeshes.forEach(m => {
                    m.scale.setScalar(1 + 0.05 * Math.sin(t / 800 + m.userData.phase));
                });
            };
            loop(0);
        }

        function setupClick() {
            const canvas = document.querySelector('#viewer canvas');
            if (!canvas) return;

            const ray = new THREE.Raycaster();
            const mouse = new THREE.Vector2();
            ray.far = SPHERE_R * 4;

            const toNDC = (e) => {
                const b = canvas.getBoundingClientRect();
                mouse.set(
                    ((e.clientX - b.left) / b.width) * 2 - 1,
                    ((e.clientY - b.top) / b.height) * -2 + 1
                );
            };

            canvas.addEventListener('click', (e) => {
                if (!markerMeshes.length) return;
                toNDC(e);
                ray.setFromCamera(mouse, viewer.renderer.camera);
                const hits = ray.intersectObjects(markerMeshes, false);
                if (!hits.length) return;
                const data = hits[0].object.userData;
                if (data.type === 'navigation') {
                    const dir = data.targetIdx > currentIdx ? 'next' : 'prev';
                    animatedLoadLocation(data.targetIdx, dir, data.yaw, data.pitch);
                } else if (data.type === 'prev') {
                    animatedLoadLocation(currentIdx - 1, 'prev', data.yaw, data.pitch);
                } else if (data.type === 'next') {
                    animatedLoadLocation(currentIdx + 1, 'next', data.yaw, data.pitch);
                } else if (data.type === 'info') {
                    showInfoPopup(data.label, data.description, data.thumbnail, data.url);
                } else if (data.type === 'external_link' && data.url) {
                    showInfoPopup(data.label, '', null, data.url);
                }
            });

            canvas.addEventListener('mousemove', (e) => {
                if (!markerMeshes.length) return;
                toNDC(e);
                ray.setFromCamera(mouse, viewer.renderer.camera);
                canvas.style.cursor = ray.intersectObjects(markerMeshes, false).length ? 'pointer' : '';
            });
        }

        function updateThumbnails() {
            const strip = document.getElementById('thumbnail-strip');
            strip.innerHTML = '';
            LOCATIONS.forEach((loc, i) => {
                const item = document.createElement('div');
                item.className = 'thumb-item' + (i === currentIdx ? ' active' : '');
                item.title = loc.name;

                const img = document.createElement('img');
                img.src = loc.image;
                img.alt = loc.name;
                img.loading = 'lazy';

                const label = document.createElement('div');
                label.className = 'thumb-name';
                label.textContent = loc.name;

                item.appendChild(img);
                item.appendChild(label);
                item.addEventListener('click', () => {
                    if (i !== currentIdx) {
                        const dir = i > currentIdx ? 'next' : 'prev';
                        animatedLoadLocation(i, dir);
                    }
                });
                strip.appendChild(item);

                if (i === currentIdx) {
                    setTimeout(() => item.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest',
                        inline: 'center'
                    }), 50);
                }
            });
        }

        function loadLocation(idx, fromYaw, fromPitch) {
            const loc = LOCATIONS[idx];
            if (!loc) return;
            currentIdx = idx;

            document.getElementById('loc-name').textContent = loc.name;
            document.getElementById('loc-desc').textContent = loc.description || '';
            updateThumbnails();
            updateMap(idx);

            viewer.setPanorama(loc.image).then(() => {
                if (fromYaw !== undefined) {
                    viewer.rotate({
                        yaw: (fromYaw || 0) + 'deg',
                        pitch: (fromPitch || 0) + 'deg'
                    });
                } else {
                    viewer.rotate({
                        yaw: (loc.yaw || 0) + 'deg',
                        pitch: (loc.pitch || 0) + 'deg'
                    });
                }
                if (loc.hfov) {
                    viewer.zoom(fovToZoomLevel(loc.hfov));
                }
                clearMarkers();
                (loc.hotspots || []).forEach(h => {
                    if (h.type === 'info' || h.type === 'external_link') {
                        addMarkerMesh(h.yaw, h.pitch ?? -15, makeInfoTexture(h.label), 1.1, {
                            type: 'info',
                            label: h.label,
                            description: h.description,
                            thumbnail: h.thumbnail,
                            url: h.url
                        });
                    }
                });
            });

            if (groundNav) {
                groundNav.updateNavigation();
            }
        }

        /* ── Controls ── */
        function zoomIn() {
            viewer.zoom(viewer.getZoomLevel() + 10);
        }

        function zoomOut() {
            viewer.zoom(viewer.getZoomLevel() - 10);
        }

        function pan(dyaw, dpitch) {
            const pos = viewer.getPosition();
            viewer.rotate({
                yaw: pos.yaw + dyaw,
                pitch: pos.pitch + dpitch
            });
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }

        function downloadPanorama() {
            const loc = LOCATIONS[currentIdx];
            const a = document.createElement('a');
            a.href = loc.image;
            a.download = loc.name + '.jpg';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        /* ── Wiring up controls after viewer ready ── */
        function initControls() {
            document.getElementById('btn-zoom-in').addEventListener('click', zoomIn);
            document.getElementById('btn-zoom-out').addEventListener('click', zoomOut);
            document.getElementById('btn-pan-left').addEventListener('click', () => pan(-PAN_STEP, 0));
            document.getElementById('btn-pan-right').addEventListener('click', () => pan(PAN_STEP, 0));
            document.getElementById('btn-pan-up').addEventListener('click', () => pan(0, PAN_STEP));
            document.getElementById('btn-pan-down').addEventListener('click', () => pan(0, -PAN_STEP));
            document.getElementById('btn-download').addEventListener('click', downloadPanorama);
            document.getElementById('fullscreen-btn').addEventListener('click', toggleFullscreen);

            const slider = document.getElementById('zoom-slider');
            slider.addEventListener('input', () => viewer.zoom(parseInt(slider.value)));

            viewer.addEventListener('zoom-updated', (e) => {
                slider.value = e.zoomLevel;
            });

            document.addEventListener('fullscreenchange', () => {
                const isFs = !!document.fullscreenElement;
                document.getElementById('fs-icon-expand').style.display = isFs ? 'none' : '';
                document.getElementById('fs-icon-compress').style.display = isFs ? '' : 'none';
            });

            const toggleBtn = document.getElementById('toggle-panel-btn');
            const panel = document.getElementById('bottom-panel');
            toggleBtn.addEventListener('click', () => {
                const hidden = panel.classList.toggle('hidden');
                toggleBtn.classList.toggle('panel-hidden', hidden);
                toggleBtn.querySelector('svg').style.transform = hidden ? 'rotate(180deg)' : '';
            });

            // Building Panel toggle
            const buildingToggleBtn = document.getElementById('toggle-building-btn');
            const buildingPanel = document.getElementById('building-panel');
            const buildingPanelClose = document.getElementById('building-panel-close');
            buildingToggleBtn.addEventListener('click', () => {
                buildingPanel.classList.toggle('open');
            });
            buildingPanelClose.addEventListener('click', () => {
                buildingPanel.classList.remove('open');
            });
        }

        /* ── Leaflet Mini Map ── */
        let leafletMap = null;
        let leafletLayer = null;
        const mmMarkers = [];
        const mmLines = [];

        function initLeafletMap() {
            const first = LOCATIONS.find(l => l.map_x != null && l.map_y != null);
            if (!first) return;

            leafletMap = L.map('leaflet-map', {
                center: [first.map_x, first.map_y],
                zoom: 17,
                zoomControl: false,
                attributionControl: true,
                dragging: false,
                scrollWheelZoom: false,
                doubleClickZoom: false,
                touchZoom: false,
                keyboard: false,
            });
            leafletLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap',
            }).addTo(leafletMap);
            updateMap(0);
        }

        function computeConnections(locs) {
            const pairs = [];
            locs.forEach((loc, i) => {
                (loc.hotspots || []).forEach(h => {
                    if (h.type !== 'navigation') return;
                    const j = locs.findIndex(l => l.id === h.targetId);
                    if (j === -1 || j === i) return;
                    const key = Math.min(i, j) + '-' + Math.max(i, j);
                    if (!pairs.find(p => p.key === key)) pairs.push({
                        key,
                        from: i,
                        to: j
                    });
                });
            });
            return pairs;
        }

        function updateMap(activeIdx) {
            if (!leafletMap) return;
            mmMarkers.forEach(m => leafletMap.removeLayer(m));
            mmLines.forEach(m => leafletMap.removeLayer(m));
            mmMarkers.length = 0;
            mmLines.length = 0;

            const coords = LOCATIONS.map(l => l.map_x != null && l.map_y != null ? [l.map_x, l.map_y] : null);

            // fit bounds
            const valid = coords.filter(c => c);
            if (valid.length) {
                const bounds = L.latLngBounds(valid);
                leafletMap.fitBounds(bounds, {
                    padding: [30, 30],
                    maxZoom: 18
                });
            }

            // connection lines
            const conns = computeConnections(LOCATIONS);
            conns.forEach(c => {
                const a = coords[c.from],
                    b = coords[c.to];
                if (!a || !b) return;
                const line = L.polyline([a, b], {
                    color: 'rgba(255,255,255,0.35)',
                    weight: 2,
                    dashArray: '4 6',
                }).addTo(leafletMap);
                mmLines.push(line);
            });

            // markers
            LOCATIONS.forEach((loc, i) => {
                const c = coords[i];
                if (!c) return;
                const isActive = i === activeIdx;
                const marker = L.circleMarker(c, {
                    radius: isActive ? 8 : 5,
                    color: isActive ? '#fff' : 'rgba(255,255,255,0.5)',
                    fillColor: isActive ? '#4fc3f7' : 'rgba(255,255,255,0.7)',
                    fillOpacity: 1,
                    weight: isActive ? 2 : 1,
                }).addTo(leafletMap);
                marker.bindTooltip(loc.name, {
                    direction: 'top',
                    offset: [0, -6],
                    className: 'mm-tooltip'
                });
                marker.on('click', () => {
                    if (i !== currentIdx) {
                        const dir = i > currentIdx ? 'next' : 'prev';
                        animatedLoadLocation(i, dir);
                    }
                });
                mmMarkers.push(marker);
            });
        }

        viewer = new Viewer({
            container: document.getElementById('viewer'),
            panorama: LOCATIONS[0].image,
            defaultYaw: '0deg',
            defaultPitch: '0deg',
            defaultZoomLvl: fovToZoomLevel(LOCATIONS[0].hfov || 90),
            minFov: 30,
            maxFov: 120,
            navbar: false,
        });

        viewer.addEventListener('ready', () => {
            document.getElementById('loading').classList.add('hidden');

            const canvas = document.querySelector('#viewer canvas');
            groundNav = new GroundNavigation(viewer, canvas);

            initLeafletMap();
            loadLocation(0);
            startAnim();
            setTimeout(setupClick, 400);
            initControls();
        });
    </script>
</body>

</html>