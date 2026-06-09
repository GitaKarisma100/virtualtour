<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $building->name }} - Virtual Tour</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@photo-sphere-viewer/core@5.4.4/index.min.css">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { background: #0a0a0a; font-family: 'Segoe UI', sans-serif; overflow: hidden; }
  #viewer { width: 100vw; height: 100vh; position: fixed; top: 0; left: 0; z-index: 1; }

  #hud {
    position: fixed; top: 24px; left: 24px; z-index: 999;
    background: rgba(0,0,0,0.6); backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.12); border-radius: 12px;
    padding: 12px 18px; color: #fff; pointer-events: none;
  }
  #hud .name  { font-size: 17px; font-weight: 600; }
  #hud .desc  { font-size: 12px; color: rgba(255,255,255,0.45); margin-top: 3px; }

  #loading {
    position: fixed; inset: 0; z-index: 9999; background: #0a0a0a;
    display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 14px;
    transition: opacity .5s;
  }
  #loading.hidden { opacity: 0; pointer-events: none; }
  .spin { width: 34px; height: 34px; border: 3px solid rgba(255,255,255,0.1); border-top-color: #4fc3f7; border-radius: 50%; animation: spin .8s linear infinite; }
  @keyframes spin { to { transform: rotate(360deg); } }
  #loading p { color: rgba(255,255,255,0.35); font-size: 13px; }

  #back-btn {
    position: fixed; top: 24px; right: 24px; z-index: 999;
    background: rgba(0,0,0,0.6); backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.12); border-radius: 12px;
    padding: 10px 16px; color: #fff; text-decoration: none; font-size: 13px; font-family: inherit;
    cursor: pointer;
  }
  #back-btn:hover { background: rgba(0,0,0,0.8); }

  /* ── Bottom Panel ── */
  #bottom-panel {
    position: fixed; bottom: 0; left: 0; right: 0; z-index: 999;
    background: rgba(0,0,0,0.45); backdrop-filter: blur(10px);
    border-top: 1px solid rgba(255,255,255,0.07);
    display: flex; flex-direction: column;
    transition: transform .3s cubic-bezier(.22,1,.36,1), opacity .3s;
  }
  #bottom-panel.hidden {
    transform: translateY(100%); opacity: 0; pointer-events: none;
  }

  #toggle-panel-btn {
    position: fixed; bottom: 0; left: 50%; transform: translateX(-50%);
    z-index: 1000; background: rgba(0,0,0,0.45); backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1); border-bottom: none;
    border-radius: 8px 8px 0 0; padding: 4px 14px 2px;
    color: rgba(255,255,255,0.5); cursor: pointer; font-size: 10px;
    letter-spacing: 1px; transition: color .2s, background .2s;
    display: flex; align-items: center; gap: 5px; white-space: nowrap;
  }
  #toggle-panel-btn:hover { color: #fff; background: rgba(0,0,0,0.6); }
  #toggle-panel-btn.panel-hidden { bottom: 0; border-radius: 8px 8px 0 0; }
  #toggle-panel-btn svg { transition: transform .3s; }
  #toggle-panel-btn.panel-hidden svg { transform: rotate(180deg); }

  /* Thumbnail Strip */
  #thumbnail-strip {
    display: flex; gap: 6px; overflow-x: auto;
    padding: 10px 10px 6px;
    scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.18) transparent;
  }
  #thumbnail-strip::-webkit-scrollbar { height: 4px; }
  #thumbnail-strip::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 2px; }

  .thumb-item {
    flex-shrink: 0; position: relative; width: 130px; height: 74px;
    border-radius: 5px; overflow: hidden; cursor: pointer;
    border: 2px solid rgba(255,255,255,0.12);
    transition: border-color .2s, transform .15s;
  }
  .thumb-item:hover { border-color: rgba(255,255,255,0.5); transform: translateY(-2px); }
  .thumb-item.active { border-color: #fff; }
  .thumb-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .thumb-item .thumb-name {
    position: absolute; top: 0; left: 0; right: 0;
    padding: 5px 7px;
    background: linear-gradient(to bottom, rgba(0,0,0,0.75) 0%, transparent 100%);
    color: #fff; font-size: 10px; font-weight: 500;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    line-height: 1.3;
  }

  /* Controls Bar */
  #controls-bar {
    display: flex; align-items: center; gap: 2px;
    padding: 6px 12px 10px;
    border-top: 1px solid rgba(255,255,255,0.06);
  }

  .ctrl-btn {
    background: transparent; border: none;
    color: rgba(255,255,255,0.65);
    cursor: pointer; width: 30px; height: 30px;
    border-radius: 6px; display: flex; align-items: center;
    justify-content: center; font-size: 14px;
    transition: background .15s, color .15s; flex-shrink: 0;
  }
  .ctrl-btn:hover { background: rgba(255,255,255,0.12); color: #fff; }
  .ctrl-btn:active { background: rgba(255,255,255,0.2); }
  .ctrl-btn svg { pointer-events: none; }

  .ctrl-sep { width: 1px; height: 18px; background: rgba(255,255,255,0.12); margin: 0 6px; flex-shrink: 0; }

  #zoom-slider {
    -webkit-appearance: none; appearance: none;
    width: 80px; height: 3px;
    background: rgba(255,255,255,0.22); border-radius: 2px;
    outline: none; cursor: pointer; flex-shrink: 0;
  }
  #zoom-slider::-webkit-slider-thumb {
    -webkit-appearance: none; width: 13px; height: 13px;
    border-radius: 50%; background: #fff; cursor: pointer;
    box-shadow: 0 0 4px rgba(0,0,0,0.5);
  }
  #zoom-slider::-moz-range-thumb {
    width: 13px; height: 13px; border: none;
    border-radius: 50%; background: #fff; cursor: pointer;
  }

  #fullscreen-btn { margin-left: auto; }

  /* Sidebar */
  #sidebar {
    position: fixed; top: 0; right: 0; z-index: 9998;
    width: 420px; max-width: 100vw; height: 100vh;
    background: rgba(16,16,18,0.94); backdrop-filter: blur(20px);
    border-left: 1px solid rgba(255,255,255,0.08);
    transform: translateX(100%); transition: transform .35s cubic-bezier(.22,1,.36,1);
    display: flex; flex-direction: column;
  }
  #sidebar.open { transform: translateX(0); }
  #sidebar-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 24px 28px 0; flex-shrink: 0;
  }
  #sidebar-header h2 {
    font-size: 11px; letter-spacing: 2px; text-transform: uppercase;
    color: rgba(255,255,255,0.35); font-weight: 500; margin: 0;
  }
  #sidebar-close {
    background: none; border: none; color: rgba(255,255,255,0.4);
    font-size: 24px; cursor: pointer; padding: 4px; line-height: 1;
  }
  #sidebar-close:hover { color: #fff; }
  #sidebar-body { padding: 20px 28px 28px; overflow-y: auto; flex: 1; }
  #sidebar-body h3 { font-size: 22px; font-weight: 600; color: #fff; margin: 0 0 6px; }
  #sidebar-body hr { border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 16px 0; }
  #sidebar-body p { font-size: 15px; color: rgba(255,255,255,0.55); line-height: 1.7; margin: 0; }
  #sidebar-thumb { width: 100%; border-radius: 8px; margin-bottom: 12px; display: none; }
  #sidebar-yt {
    width: 100%; margin-top: 16px;
    position: relative; padding-bottom: 56.25%; height: 0;
    overflow: hidden; border-radius: 8px; background: #000;
  }
  #sidebar-yt iframe {
    position: absolute; top: 0; left: 0;
    width: 100%; height: 100%; border: 0;
  }
  #sidebar-link {
    display: inline-flex; align-items: center; gap: 6px; margin-top: 16px;
    padding: 8px 20px; background: rgba(79, 195, 247, 0.15); color: #4fc3f7;
    border: 1px solid rgba(79, 195, 247, 0.3); border-radius: 8px;
    font-size: 13px; text-decoration: none; transition: all .2s;
  }
  #sidebar-link:hover { background: rgba(79, 195, 247, 0.25); }
</style>
</head>
<body>

<div id="loading"><div class="spin"></div><p>Memuat virtual tour…</p></div>
<div id="viewer"></div>

<div id="hud">
  <div class="name" id="loc-name">—</div>
  <div class="desc" id="loc-desc"></div>
</div>

<a href="{{ route('tour.index') }}" id="back-btn">← Kembali</a>

<!-- Toggle Button -->
<button id="toggle-panel-btn" title="Sembunyikan/tampilkan panel">
  <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
    <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/>
  </svg>
  PANEL
</button>

<!-- Bottom Panel: Thumbnail Strip + Controls -->
<div id="bottom-panel">
  <div id="thumbnail-strip"></div>
  <div id="controls-bar">
    <!-- Zoom -->
    <button class="ctrl-btn" id="btn-zoom-out" title="Zoom out">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
        <circle cx="11" cy="11" r="7"/><line x1="16.5" y1="16.5" x2="22" y2="22"/>
        <line x1="8" y1="11" x2="14" y2="11"/>
      </svg>
    </button>
    <input type="range" id="zoom-slider" min="0" max="100" value="0" title="Zoom">
    <button class="ctrl-btn" id="btn-zoom-in" title="Zoom in">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
        <circle cx="11" cy="11" r="7"/><line x1="16.5" y1="16.5" x2="22" y2="22"/>
        <line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/>
      </svg>
    </button>

    <div class="ctrl-sep"></div>

    <!-- Pan -->
    <button class="ctrl-btn" id="btn-pan-left" title="Putar kiri">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
        <path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6z"/>
      </svg>
    </button>
    <button class="ctrl-btn" id="btn-pan-right" title="Putar kanan">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
        <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/>
      </svg>
    </button>
    <button class="ctrl-btn" id="btn-pan-up" title="Putar atas">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
        <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/>
      </svg>
    </button>
    <button class="ctrl-btn" id="btn-pan-down" title="Putar bawah">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
        <path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6z"/>
      </svg>
    </button>

    <div class="ctrl-sep"></div>

    <!-- Download -->
    <button class="ctrl-btn" id="btn-download" title="Unduh panorama">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
        <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
      </svg>
    </button>

    <!-- Fullscreen -->
    <button class="ctrl-btn" id="fullscreen-btn" title="Layar penuh">
      <svg id="fs-icon-expand" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
        <polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/>
        <line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/>
      </svg>
      <svg id="fs-icon-compress" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" style="display:none">
        <polyline points="4 14 10 14 10 20"/><polyline points="20 10 14 10 14 4"/>
        <line x1="10" y1="14" x2="3" y2="21"/><line x1="21" y1="3" x2="14" y2="10"/>
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
  if (thumb) { img.src = thumb; img.style.display = 'block'; }
  else { img.style.display = 'none'; }
  document.getElementById('sidebar-title').textContent = label;
  document.getElementById('sidebar-desc').textContent = desc || '';
  const link = document.getElementById('sidebar-link');
  const yt   = document.getElementById('sidebar-yt');
  const ytId = getYtId(url);
  if (ytId) {
    link.style.display = 'none';
    yt.innerHTML = `<iframe src="https://www.youtube.com/embed/${ytId}" allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" allowfullscreen></iframe>`;
    yt.classList.remove('hidden');
  } else {
    yt.classList.add('hidden');
    yt.innerHTML = '';
    if (url) { link.href = url; link.style.display = 'inline-flex'; }
    else { link.style.display = 'none'; }
  }
  document.getElementById('sidebar').classList.add('open');
}
</script>

<script type="module">
import { Viewer } from '@photo-sphere-viewer/core';
import * as THREE from 'three';

const LOCATIONS = {!! $locationsJson !!};

let viewer      = null;
let currentIdx  = 0;
let markerMeshes = [];
const SPHERE_R  = 1;
const PAN_STEP  = THREE.MathUtils.degToRad(15);

function makeCircleCanvas(bgColor) {
  const S = 512;
  const c = document.createElement('canvas');
  c.width = c.height = S;
  const g = c.getContext('2d');
  const cx = S / 2, cy = S / 2;
  const r = S * 0.38;

  const grad = g.createRadialGradient(cx, cy + S * 0.18, 0, cx, cy + S * 0.18, S * 0.40);
  grad.addColorStop(0,   'rgba(0, 0, 0, 0.50)');
  grad.addColorStop(0.6, 'rgba(0, 0, 0, 0.22)');
  grad.addColorStop(1,   'rgba(0, 0, 0, 0)');
  g.fillStyle = grad;
  g.beginPath();
  g.ellipse(cx, cy + S * 0.18, S * 0.40, S * 0.12, 0, 0, Math.PI * 2);
  g.fill();

  g.beginPath();
  g.arc(cx, cy, r, 0, Math.PI * 2);
  g.fillStyle = bgColor;
  g.fill();

  return { canvas: c, cx, cy, r };
}

function makeArrowTexture() {
  const { canvas: c, cx, cy, r } = makeCircleCanvas('rgba(255,255,255,0.9)');
  const g = c.getContext('2d');
  const s = r * 0.55;
  const tipY  = cy - s * 0.30;
  const baseY = cy + s * 0.55;

  g.beginPath();
  g.moveTo(cx - s * 0.65, baseY);
  g.lineTo(cx,            tipY);
  g.lineTo(cx + s * 0.65, baseY);
  g.lineTo(cx + s * 0.25, cy + s * 0.15);
  g.lineTo(cx,            cy);
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
  const { canvas: c, cx, cy, r } = makeCircleCanvas('rgba(162, 130, 255, 0.9)');
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
  const { canvas: c, cx, cy, r } = makeCircleCanvas('rgba(255,255,255,0.9)');
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
  const { canvas: c, cx, cy, r } = makeCircleCanvas('rgba(255,255,255,0.9)');
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
  const yaw   = THREE.MathUtils.degToRad(yawDeg);
  const pitch = THREE.MathUtils.degToRad(pitchDeg);
  const r     = SPHERE_R;

  const x =  r * Math.cos(pitch) * Math.sin(yaw);
  const y =  r * Math.sin(pitch);
  const z = -r * Math.cos(pitch) * Math.cos(yaw);

  const size = r * 0.22 * sizeMul;

  const mesh = new THREE.Mesh(
    new THREE.PlaneGeometry(size, size),
    new THREE.MeshBasicMaterial({
      map:         texture,
      transparent: true,
      depthWrite:  false,
      depthTest:   false,
      side:        THREE.DoubleSide,
      alphaTest:   0.01,
    })
  );

  mesh.position.set(x, y, z);
  mesh.lookAt(0, 0, 0);
  mesh.scale.z = -1;
  mesh.renderOrder = 999;
  mesh.userData    = { ...userData, phase: Math.random() * Math.PI * 2, position: new THREE.Vector3(x, y, z) };

  scene.add(mesh);
  markerMeshes.push(mesh);
  return mesh;
}

const arrowTex = makeArrowTexture();
const linkTex  = makeLinkTexture();
const prevTex  = makePrevTexture();
const nextTex  = makeNextTexture();

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

  const ray   = new THREE.Raycaster();
  const mouse = new THREE.Vector2();
  ray.far     = SPHERE_R * 4;

  const toNDC = (e) => {
    const b = canvas.getBoundingClientRect();
    mouse.set(
      ((e.clientX - b.left) / b.width)  *  2 - 1,
      ((e.clientY - b.top)  / b.height) * -2 + 1
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
      loadLocation(data.targetIdx);
    } else if (data.type === 'prev') {
      loadLocation(currentIdx - 1);
    } else if (data.type === 'next') {
      loadLocation(currentIdx + 1);
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
    item.addEventListener('click', () => loadLocation(i));
    strip.appendChild(item);

    if (i === currentIdx) {
      setTimeout(() => item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' }), 50);
    }
  });
}

function loadLocation(idx) {
  const loc = LOCATIONS[idx];
  if (!loc) return;
  currentIdx = idx;

  document.getElementById('loc-name').textContent = loc.name;
  document.getElementById('loc-desc').textContent = loc.description || '';
  updateThumbnails();

  viewer.setPanorama(loc.image).then(() => {
    viewer.rotate({ yaw: (loc.yaw || 0) + 'deg', pitch: (loc.pitch || 0) + 'deg' });
    clearMarkers();
    (loc.hotspots || []).forEach(h => {
      if (h.type === 'navigation') {
        const targetIdx = LOCATIONS.findIndex(l => l.id === h.targetId);
        if (targetIdx === -1) return;
        addMarkerMesh(h.yaw, h.pitch ?? -30, arrowTex, 1, { type: 'navigation', targetIdx });
      } else if (h.type === 'info' || h.type === 'external_link') {
        addMarkerMesh(h.yaw, h.pitch ?? -15, makeInfoTexture(h.label), 1.1, { type: 'info', label: h.label, description: h.description, thumbnail: h.thumbnail, url: h.url });
      }
    });

    if (LOCATIONS.length > 1) {
      if (idx > 0) {
        addMarkerMesh(-70, -15, prevTex, 0.9, { type: 'prev' });
      }
      if (idx < LOCATIONS.length - 1) {
        addMarkerMesh(70, -15, nextTex, 0.9, { type: 'next' });
      }
    }
  });
}

/* ── Controls ── */
function zoomIn()  { viewer.zoom(viewer.getZoomLevel() + 10); }
function zoomOut() { viewer.zoom(viewer.getZoomLevel() - 10); }

function pan(dyaw, dpitch) {
  const pos = viewer.getPosition();
  viewer.rotate({ yaw: pos.yaw + dyaw, pitch: pos.pitch + dpitch });
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
  document.getElementById('btn-pan-left').addEventListener('click',  () => pan(-PAN_STEP, 0));
  document.getElementById('btn-pan-right').addEventListener('click', () => pan(PAN_STEP,  0));
  document.getElementById('btn-pan-up').addEventListener('click',    () => pan(0, PAN_STEP));
  document.getElementById('btn-pan-down').addEventListener('click',  () => pan(0, -PAN_STEP));
  document.getElementById('btn-download').addEventListener('click', downloadPanorama);
  document.getElementById('fullscreen-btn').addEventListener('click', toggleFullscreen);

  const slider = document.getElementById('zoom-slider');
  slider.addEventListener('input', () => viewer.zoom(parseInt(slider.value)));

  viewer.addEventListener('zoom-updated', (e) => {
    slider.value = e.zoomLevel;
  });

  document.addEventListener('fullscreenchange', () => {
    const isFs = !!document.fullscreenElement;
    document.getElementById('fs-icon-expand').style.display  = isFs ? 'none' : '';
    document.getElementById('fs-icon-compress').style.display = isFs ? '' : 'none';
  });

  const toggleBtn = document.getElementById('toggle-panel-btn');
  const panel     = document.getElementById('bottom-panel');
  toggleBtn.addEventListener('click', () => {
    const hidden = panel.classList.toggle('hidden');
    toggleBtn.classList.toggle('panel-hidden', hidden);
    toggleBtn.querySelector('svg').style.transform = hidden ? 'rotate(180deg)' : '';
  });
}

viewer = new Viewer({
  container:      document.getElementById('viewer'),
  panorama:       LOCATIONS[0].image,
  defaultYaw:     '0deg',
  defaultPitch:   '0deg',
  defaultZoomLvl: 0,
  minFov:  30,
  maxFov:  90,
  navbar:  false,
});

viewer.addEventListener('ready', () => {
  document.getElementById('loading').classList.add('hidden');
  loadLocation(0);
  startAnim();
  setTimeout(setupClick, 400);
  initControls();
});
</script>
</body>
</html>
