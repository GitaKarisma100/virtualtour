<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-secondary-fixed-variant": "#46464e",
                        "surface-tint": "#1976D2",
                        "tertiary-container": "#1565C0",
                        "inverse-primary": "#90CAF9",
                        "surface-container": "#DDE6F5",
                        "secondary-fixed": "#D0E4F7",
                        "surface-container-highest": "#C6D4E8",
                        "on-secondary-fixed": "#1a1b22",
                        "inverse-surface": "#1a2a3a",
                        "tertiary": "#1565C0",
                        "outline-variant": "#BDD0E8",
                        "secondary-container": "#D0E4F7",
                        "surface-bright": "#F4F8FF",
                        "surface-container-low": "#E8EFF8",
                        "error-container": "#ffdad6",
                        "surface": "#EEF2FA",
                        "on-tertiary-fixed-variant": "#1A4B8C",
                        "error": "#ba1a1a",
                        "tertiary-fixed-dim": "#90CAF9",
                        "background": "#EEF2FA",
                        "primary-fixed": "#BBDEFB",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary": "#ffffff",
                        "secondary": "#4A6F9E",
                        "on-tertiary": "#ffffff",
                        "on-background": "#1a1c1c",
                        "on-primary-container": "#90CAF9",
                        "primary-fixed-dim": "#64B5F6",
                        "on-primary-fixed": "#0D1B3E",
                        "secondary-fixed-dim": "#5B8EC7",
                        "on-secondary-container": "#1A3D6E",
                        "on-primary-fixed-variant": "#1A3D6E",
                        "primary": "#1565C0",
                        "tertiary-fixed": "#BBDEFB",
                        "surface-container-high": "#D3DDED",
                        "surface-variant": "#D0E0F0",
                        "on-tertiary-container": "#90CAF9",
                        "on-surface-variant": "#3D5A80",
                        "on-surface": "#1a1c1c",
                        "on-error": "#ffffff",
                        "on-error-container": "#93000a",
                        "on-primary": "#ffffff",
                        "on-tertiary-fixed": "#0D1B3E",
                        "primary-container": "#1565C0",
                        "surface-dim": "#C5D5E8",
                        "inverse-on-surface": "#EEF2FA",
                        "outline": "#6B8CB8"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "sm": "8px",
                        "2xl": "64px",
                        "base": "4px",
                        "sidebar-width": "260px",
                        "md": "16px",
                        "xs": "4px",
                        "xl": "40px",
                        "container-max": "1440px",
                        "lg": "24px",
                        "gutter": "24px"
                    },
                    "fontFamily": {
                        "body-lg": ["Geist"],
                        "headline-lg": ["Geist"],
                        "code": ["Geist"],
                        "headline-md": ["Geist"],
                        "label-md": ["Geist"],
                        "display": ["Geist"],
                        "headline-lg-mobile": ["Geist"],
                        "body-md": ["Geist"]
                    },
                    "fontSize": {
                        "body-lg": ["16px", {
                            "lineHeight": "24px",
                            "fontWeight": "400"
                        }],
                        "headline-lg": ["30px", {
                            "lineHeight": "36px",
                            "letterSpacing": "-0.01em",
                            "fontWeight": "600"
                        }],
                        "code": ["13px", {
                            "lineHeight": "18px",
                            "fontWeight": "400"
                        }],
                        "headline-md": ["20px", {
                            "lineHeight": "28px",
                            "letterSpacing": "-0.005em",
                            "fontWeight": "500"
                        }],
                        "label-md": ["12px", {
                            "lineHeight": "16px",
                            "letterSpacing": "0.02em",
                            "fontWeight": "500"
                        }],
                        "display": ["48px", {
                            "lineHeight": "1.1",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "600"
                        }],
                        "headline-lg-mobile": ["24px", {
                            "lineHeight": "32px",
                            "letterSpacing": "-0.01em",
                            "fontWeight": "600"
                        }],
                        "body-md": ["14px", {
                            "lineHeight": "20px",
                            "fontWeight": "400"
                        }]
                    }
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Geist', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-background text-on-background min-h-screen" x-data="{ sidebarOpen: false }">
    @include('admin.layouts.sidebar')

    <main class="ml-0 lg:ml-[260px] min-h-screen flex flex-col">
        @include('admin.layouts.header')

        <div class="p-lg flex-1 space-y-lg max-w-[1440px]">
            @yield('content')
        </div>
    </main>

    @stack('scripts')

    <style>
      /* ── Toast ── */
      #toast-stack {
        position: fixed; top: 20px; right: 20px; z-index: 9999;
        display: flex; flex-direction: column; gap: 10px;
        pointer-events: none; width: 320px;
      }
      .toast {
        position: relative; overflow: hidden;
        display: flex; align-items: flex-start; gap: 12px;
        background: #fff; border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.10), 0 1px 4px rgba(0,0,0,0.06);
        padding: 14px 14px 18px 16px;
        border-left: 4px solid #ccc; pointer-events: all;
        transform: translateX(calc(100% + 28px)); opacity: 0;
        transition: transform .35s cubic-bezier(.22,1,.36,1), opacity .3s;
      }
      .toast.show { transform: translateX(0); opacity: 1; }
      .toast.toast-success { border-left-color: #16a34a; }
      .toast.toast-error   { border-left-color: #dc2626; }
      .toast-icon { flex-shrink:0; width:20px; height:20px; margin-top:1px; }
      .toast-icon.success { color: #16a34a; }
      .toast-icon.error   { color: #dc2626; }
      .toast-body { flex: 1; }
      .toast-title { font-size:13px; font-weight:600; color:#1a1c1c; line-height:1.4; }
      .toast-msg   { font-size:12px; color:#4c4546; margin-top:2px; line-height:1.5; }
      .toast-close {
        flex-shrink:0; background:none; border:none; cursor:pointer;
        color:#aaa; font-size:15px; line-height:1; padding:0;
        transition: color .15s;
      }
      .toast-close:hover { color: #1a1c1c; }
      .toast-progress { position:absolute; bottom:0; left:4px; right:0; height:3px; }
      .toast-progress-bar { height:100%; width:100%; border-radius:2px; animation: t-shrink 3.5s linear forwards; }
      .toast-success .toast-progress-bar { background: #16a34a; }
      .toast-error   .toast-progress-bar { background: #dc2626; }
      @keyframes t-shrink { from { width:100%; } to { width:0%; } }

      /* ── Confirm Modal ── */
      #confirm-overlay {
        position: fixed; inset: 0; z-index: 9998;
        background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center; padding: 20px;
        opacity: 0; pointer-events: none; transition: opacity .25s;
      }
      #confirm-overlay.show { opacity: 1; pointer-events: all; }
      #confirm-box {
        background: #fff; border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.18);
        padding: 32px 28px 24px; width: 100%; max-width: 380px;
        transform: scale(0.94) translateY(10px);
        transition: transform .3s cubic-bezier(.22,1,.36,1);
        text-align: center;
      }
      #confirm-overlay.show #confirm-box { transform: scale(1) translateY(0); }
      .confirm-icon-wrap {
        width: 52px; height: 52px; border-radius: 50%;
        background: #fef2f2; display: flex; align-items: center;
        justify-content: center; margin: 0 auto 16px;
        color: #dc2626;
      }
      #confirm-title { font-size:18px; font-weight:600; color:#1a1c1c; margin-bottom:8px; }
      #confirm-message { font-size:14px; color:#4c4546; line-height:1.6; }
      .confirm-actions { display:flex; gap:10px; margin-top:24px; }
      .confirm-btn-cancel {
        flex:1; padding:10px; border-radius:8px;
        background:transparent; border:1px solid #cfc4c5;
        color:#1a1c1c; font-size:14px; font-weight:500;
        cursor:pointer; font-family:inherit; transition:background .15s;
      }
      .confirm-btn-cancel:hover { background: #f3f3f3; }
      .confirm-btn-delete {
        flex:1; padding:10px; border-radius:8px;
        background:#dc2626; border:none;
        color:#fff; font-size:14px; font-weight:500;
        cursor:pointer; font-family:inherit; transition:background .15s;
      }
      .confirm-btn-delete:hover { background: #b91c1c; }
    </style>

    <!-- Toast Stack -->
    <div id="toast-stack"></div>

    <!-- Confirm Modal -->
    <div id="confirm-overlay">
      <div id="confirm-box">
        <div class="confirm-icon-wrap">
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"/>
            <path d="M19 6l-1 14H6L5 6"/>
            <path d="M10 11v6"/><path d="M14 11v6"/>
            <path d="M9 6V4h6v2"/>
          </svg>
        </div>
        <h3 id="confirm-title">Konfirmasi Hapus</h3>
        <p id="confirm-message">Apakah kamu yakin ingin menghapus data ini?</p>
        <div class="confirm-actions">
          <button class="confirm-btn-cancel" id="confirm-cancel">Batal</button>
          <button class="confirm-btn-delete" id="confirm-ok">Hapus</button>
        </div>
      </div>
    </div>

    <script>
      function showToast(message, type = 'success') {
        const stack = document.getElementById('toast-stack');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        const titles = { success: 'Berhasil', error: 'Terjadi Kesalahan' };
        const icons = {
          success: `<svg class="toast-icon success" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M20 6L9 17l-5-5"/></svg>`,
          error:   `<svg class="toast-icon error" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" width="20" height="20"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`
        };

        toast.innerHTML = `
          ${icons[type] || icons.success}
          <div class="toast-body">
            <div class="toast-title">${titles[type] || 'Notifikasi'}</div>
            <div class="toast-msg">${message}</div>
          </div>
          <button class="toast-close" onclick="dismissToast(this.closest('.toast'))">✕</button>
          <div class="toast-progress"><div class="toast-progress-bar"></div></div>
        `;

        stack.appendChild(toast);
        requestAnimationFrame(() => requestAnimationFrame(() => toast.classList.add('show')));
        setTimeout(() => dismissToast(toast), 3500);
      }

      function dismissToast(toast) {
        if (!toast || !toast.parentNode) return;
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
      }

      let _pendingForm = null;

      document.getElementById('confirm-cancel').addEventListener('click', () => {
        _pendingForm = null;
        document.getElementById('confirm-overlay').classList.remove('show');
      });

      document.getElementById('confirm-ok').addEventListener('click', () => {
        const form = _pendingForm;
        _pendingForm = null;
        document.getElementById('confirm-overlay').classList.remove('show');
        if (form) setTimeout(() => form.submit(), 150);
      });

      document.getElementById('confirm-overlay').addEventListener('click', e => {
        if (e.target === e.currentTarget) {
          _pendingForm = null;
          e.currentTarget.classList.remove('show');
        }
      });

      document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && document.getElementById('confirm-overlay').classList.contains('show')) {
          _pendingForm = null;
          document.getElementById('confirm-overlay').classList.remove('show');
        }
      });

      document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[data-confirm]').forEach(form => {
          form.addEventListener('submit', e => {
            e.preventDefault();
            _pendingForm = form;
            document.getElementById('confirm-message').textContent =
              form.dataset.confirm || 'Apakah kamu yakin ingin menghapus data ini?';
            document.getElementById('confirm-overlay').classList.add('show');
          });
        });

        @if(session('success'))
          showToast(@json(session('success')), 'success');
        @endif
        @if(session('error'))
          showToast(@json(session('error')), 'error');
        @endif
      });
    </script>
</body>

</html>
