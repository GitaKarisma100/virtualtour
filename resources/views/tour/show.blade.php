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
</style>
</head>
<body>

<div id="loading"><div class="spin"></div><p>Memuat virtual tour…</p></div>
<div id="viewer"></div>

<div id="hud">
  <div class="label">Lokasi saat ini</div>
  <div class="name" id="loc-name">—</div>
  <div class="desc" id="loc-desc"></div>
</div>

<a href="{{ route('tour.index') }}" id="back-btn">← Kembali</a>

<div id="nav"></div>
<div id="hint">Klik panah di lantai untuk berpindah</div>

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
import * as THREE from 'three';

const LOCATIONS = {!! $locationsJson !!};

let viewer      = null;
let currentIdx  = 0;
let arrowMeshes = [];
const SPHERE_R  = 1;

function makeArrowTexture() {
  const S = 512;
  const c = document.createElement('canvas');
  c.width = c.height = S;
  const g = c.getContext('2d');
  const cx = S / 2, cy = S / 2;

  g.save();
  g.translate(cx, cy + S * 0.18);
  g.scale(1, 0.28);
  g.beginPath();
  g.arc(0, 0, S * 0.30, 0, Math.PI * 2);
  const grad = g.createRadialGradient(0, 0, 0, 0, 0, S * 0.30);
  grad.addColorStop(0,   'rgba(0, 0, 0, 0.45)');
  grad.addColorStop(0.6, 'rgba(0, 0, 0, 0.20)');
  grad.addColorStop(1,   'rgba(0, 0, 0, 0)');
  g.fillStyle = grad;
  g.fill();
  g.restore();

  const circleR = S * 0.22;
  g.beginPath();
  g.arc(cx, cy - S * 0.04, circleR, 0, Math.PI * 2);
  g.fillStyle = 'rgba(255, 255, 255, 0.88)';
  g.fill();

  const arrowSize = circleR * 0.65;
  const tipY  = cy - S * 0.04 + arrowSize * 0.70;
  const baseY = cy - S * 0.04 - arrowSize * 0.45;
  const midY  = cy - S * 0.04;

  g.beginPath();
  g.moveTo(cx - arrowSize * 0.75, baseY);
  g.lineTo(cx,                    tipY);
  g.lineTo(cx + arrowSize * 0.75, baseY);
  g.lineTo(cx + arrowSize * 0.30, midY);
  g.lineTo(cx,                    midY - arrowSize * 0.28);
  g.lineTo(cx - arrowSize * 0.30, midY);
  g.closePath();
  g.fillStyle = 'rgba(55, 55, 55, 0.90)';
  g.fill();

  return new THREE.CanvasTexture(c);
}

function clearArrows() {
  const scene = viewer.renderer.scene;
  arrowMeshes.forEach(m => {
    scene.remove(m);
    m.geometry.dispose();
    if (m.material.map) m.material.map.dispose();
    m.material.dispose();
  });
  arrowMeshes = [];
}

function addArrow(yawDeg, pitchDeg, targetIdx) {
  const scene = viewer.renderer.scene;
  const yaw   = THREE.MathUtils.degToRad(yawDeg);
  const pitch = THREE.MathUtils.degToRad(pitchDeg);
  const r     = SPHERE_R;

  const x =  r * Math.cos(pitch) * Math.sin(yaw);
  const y =  r * Math.sin(pitch);
  const z = -r * Math.cos(pitch) * Math.cos(yaw);

  const size = r * 0.22;

  const mesh = new THREE.Mesh(
    new THREE.PlaneGeometry(size, size),
    new THREE.MeshBasicMaterial({
      map:         makeArrowTexture(),
      transparent: true,
      depthWrite:  false,
      depthTest:   false,
      side:        THREE.DoubleSide,
      alphaTest:   0.01,
    })
  );

  mesh.position.set(x, y, z);
  mesh.lookAt(0, 0, 0);
  mesh.rotateX(Math.PI);
  mesh.renderOrder = 999;
  mesh.userData    = { targetIdx, phase: Math.random() * Math.PI * 2 };

  scene.add(mesh);
  arrowMeshes.push(mesh);
}

function startAnim() {
  const loop = (t) => {
    requestAnimationFrame(loop);
    arrowMeshes.forEach(m => {
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
    if (!arrowMeshes.length) return;
    toNDC(e);
    ray.setFromCamera(mouse, viewer.renderer.camera);
    const hits = ray.intersectObjects(arrowMeshes, false);
    if (hits.length) loadLocation(hits[0].object.userData.targetIdx);
  });

  canvas.addEventListener('mousemove', (e) => {
    if (!arrowMeshes.length) return;
    toNDC(e);
    ray.setFromCamera(mouse, viewer.renderer.camera);
    canvas.style.cursor = ray.intersectObjects(arrowMeshes, false).length ? 'pointer' : '';
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
    clearArrows();
    (loc.hotspots || []).forEach(h => {
      addArrow(h.yaw, h.pitch ?? -30, LOCATIONS.findIndex(l => l.id === h.targetId));
    });
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