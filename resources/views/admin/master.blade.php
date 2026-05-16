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
                        "surface-tint": "#5e5e5e",
                        "tertiary-container": "#1b1b1b",
                        "inverse-primary": "#c6c6c6",
                        "surface-container": "#eeeeee",
                        "secondary-fixed": "#e3e1ec",
                        "surface-container-highest": "#e2e2e2",
                        "on-secondary-fixed": "#1a1b22",
                        "inverse-surface": "#2f3131",
                        "tertiary": "#000000",
                        "outline-variant": "#cfc4c5",
                        "secondary-container": "#e3e1ec",
                        "surface-bright": "#f9f9f9",
                        "surface-container-low": "#f3f3f3",
                        "error-container": "#ffdad6",
                        "surface": "#f9f9f9",
                        "on-tertiary-fixed-variant": "#474747",
                        "error": "#ba1a1a",
                        "tertiary-fixed-dim": "#c6c6c6",
                        "background": "#f9f9f9",
                        "primary-fixed": "#e2e2e2",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary": "#ffffff",
                        "secondary": "#5d5e66",
                        "on-tertiary": "#ffffff",
                        "on-background": "#1a1c1c",
                        "on-primary-container": "#848484",
                        "primary-fixed-dim": "#c6c6c6",
                        "on-primary-fixed": "#1b1b1b",
                        "secondary-fixed-dim": "#c6c5cf",
                        "on-secondary-container": "#63646c",
                        "on-primary-fixed-variant": "#474747",
                        "primary": "#000000",
                        "tertiary-fixed": "#e2e2e2",
                        "surface-container-high": "#e8e8e8",
                        "surface-variant": "#e2e2e2",
                        "on-tertiary-container": "#848484",
                        "on-surface-variant": "#4c4546",
                        "on-surface": "#1a1c1c",
                        "on-error": "#ffffff",
                        "on-error-container": "#93000a",
                        "on-primary": "#ffffff",
                        "on-tertiary-fixed": "#1b1b1b",
                        "primary-container": "#1b1b1b",
                        "surface-dim": "#dadada",
                        "inverse-on-surface": "#f0f1f1",
                        "outline": "#7e7576"
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
</body>

</html>
