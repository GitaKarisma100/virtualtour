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
  #hud .label { font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 4px; }
  #hud .name  { font-size: 17px; font-weight: 600; }
  #hud .desc  { font-size: 12px; color: rgba(255,255,255,0.45); margin-top: 3px; }

  #nav {
    position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%);
    z-index: 999; display: flex; gap: 8px;
    background: rgba(0,0,0,0.6); backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.12); border-radius: 50px; padding: 7px 12px;
  }
  .nbtn {
    display: flex; align-items: center; gap: 7px;
    background: transparent; border: none; color: rgba(255,255,255,0.5);
    font-size: 12px; font-family: inherit; padding: 6px 13px; border-radius: 40px;
    cursor: pointer; transition: all .2s; white-space: nowrap;
  }
  .nbtn .dot { width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,0.25); transition: all .2s; flex-shrink: 0; }
  .nbtn:hover { color: #fff; background: rgba(255,255,255,0.1); }
  .nbtn.active { color: #fff; background: rgba(255,255,255,0.14); }
  .nbtn.active .dot { background: #4fc3f7; box-shadow: 0 0 6px #4fc3f7aa; }

  #hint { position: fixed; bottom: 28px; right: 24px; z-index: 999; color: rgba(255,255,255,0.3); font-size: 11px; pointer-events: none; }

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
  #sidebar-body {
    padding: 20px 28px 28px; overflow-y: auto; flex: 1;
  }
  #sidebar-body h3 {
    font-size: 22px; font-weight: 600; color: #fff; margin: 0 0 6px;
  }
  #sidebar-body hr {
    border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 16px 0;
  }
  #sidebar-body p {
    font-size: 15px; color: rgba(255,255,255,0.55); line-height: 1.7; margin: 0;
  }
  #sidebar-thumb { width: 100%; border-radius: 8px; margin-bottom: 12px; display: none; }
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
  <!-- <div class="label">Lokasi saat ini</div> -->
  <div class="name" id="loc-name">—</div>
  <div class="desc" id="loc-desc"></div>
</div>

<a href="{{ route('tour.index') }}" id="back-btn">← Kembali</a>

<div id="nav"></div>
<div id="hint">Klik panah untuk navigasi • Klik marker info untuk detail</div>

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
  if (url) { link.href = url; link.style.display = 'inline-flex'; }
  else { link.style.display = 'none'; }
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
  
  // Kalkulasi lebar oval mengikuti panjang teks
  const textWidth = g.measureText(text).width;
  const rectWidth = textWidth + 30;  // Panjang oval otomatis
  const rectHeight = fontSize + 15;  // Tinggi oval
  
  c.width = rectWidth + 20;
  c.height = rectHeight + 30;
  
  const cx = c.width / 2;
  const cy = c.height / 2 - 5;

  // Gambar Background Oval (Biru Tua Poliwangi Semi-Transparan)
  g.beginPath();
  g.roundRect(cx - rectWidth / 2, cy - rectHeight / 2, rectWidth, rectHeight, rectHeight / 2);
  g.fillStyle = 'rgba(0, 51, 102, 0.75)'; // Ganti angka ini jika ingin lebih/kurang transparan
  g.fill();

  // Gambar Teks
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

function updateNav() {
  const nav = document.getElementById('nav');
  nav.innerHTML = '';
  LOCATIONS.forEach((loc, i) => {
    const b = document.createElement('button');
    b.className = 'nbtn' + (i === currentIdx ? ' active' : '');
    b.innerHTML = `<span class="dot"></span>${loc.name}`;
    b.onclick   = () => loadLocation(i);
    nav.appendChild(b);
  });
}

function loadLocation(idx) {
  const loc = LOCATIONS[idx];
  if (!loc) return;
  currentIdx = idx;

  document.getElementById('loc-name').textContent = loc.name;
  document.getElementById('loc-desc').textContent = loc.description || '';
  updateNav();

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
});
</script>
</body>
</html>