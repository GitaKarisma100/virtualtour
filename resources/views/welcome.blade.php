<!DOCTYPE html>

<html class="scroll-smooth" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Politeknik Negeri Banyuwangi | Virtual Tour</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700&amp;family=Manrope:wght@600;700;800&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "inverse-surface": "#2d3133",
                        "surface-container": "#eceef0",
                        "surface-container-low": "#f2f4f6",
                        "primary-fixed-dim": "#9ccaff",
                        "on-tertiary-container": "#88b4ff",
                        "surface-white": "#FFFFFF",
                        "on-surface": "#191c1e",
                        "inverse-primary": "#9ccaff",
                        "tech-blue": "#046BD2",
                        "surface-container-highest": "#e0e3e5",
                        "on-error-container": "#93000a",
                        "error": "#ba1a1a",
                        "outline": "#727780",
                        "on-background": "#191c1e",
                        "surface-dim": "#d8dadc",
                        "primary": "#003154",
                        "outline-variant": "#c2c7d0",
                        "on-primary-container": "#85b7ee",
                        "on-tertiary": "#ffffff",
                        "on-tertiary-fixed": "#001b3e",
                        "tertiary-fixed": "#d6e3ff",
                        "secondary": "#745b00",
                        "tertiary-fixed-dim": "#aac7ff",
                        "on-secondary-fixed": "#241a00",
                        "on-surface-variant": "#42474f",
                        "on-secondary-container": "#6e5700",
                        "inverse-on-surface": "#eff1f3",
                        "on-secondary-fixed-variant": "#584400",
                        "secondary-container": "#fecb00",
                        "tertiary-container": "#00448a",
                        "vibrant-gold": "#FFCC00",
                        "surface-tint": "#2b6193",
                        "on-primary-fixed-variant": "#03497a",
                        "secondary-fixed-dim": "#f1c100",
                        "surface-container-lowest": "#ffffff",
                        "background": "#f7f9fb",
                        "on-primary": "#ffffff",
                        "surface": "#f7f9fb",
                        "secondary-fixed": "#ffe08b",
                        "on-tertiary-fixed-variant": "#00458d",
                        "tertiary": "#002e61",
                        "deep-navy": "#004878",
                        "surface-container-high": "#e6e8ea",
                        "primary-fixed": "#d0e4ff",
                        "primary-container": "#004878",
                        "surface-bright": "#f7f9fb",
                        "surface-variant": "#e0e3e5",
                        "on-secondary": "#ffffff",
                        "on-primary-fixed": "#001d35",
                        "on-error": "#ffffff",
                        "error-container": "#ffdad6"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "base": "8px",
                        "margin-mobile": "20px",
                        "gutter": "24px",
                        "container-max": "1280px",
                        "margin-desktop": "64px"
                    },
                    "fontFamily": {
                        "body-md": ["Hanken Grotesk"],
                        "body-lg": ["Hanken Grotesk"],
                        "label-caps": ["Hanken Grotesk"],
                        "headline-sm": ["Manrope"],
                        "button": ["Hanken Grotesk"],
                        "display-lg-mobile": ["Manrope"],
                        "headline-md": ["Manrope"],
                        "display-lg": ["Manrope"]
                    },
                    "fontSize": {
                        "body-md": ["16px", {
                            "lineHeight": "1.5",
                            "fontWeight": "400"
                        }],
                        "body-lg": ["18px", {
                            "lineHeight": "1.6",
                            "fontWeight": "400"
                        }],
                        "label-caps": ["12px", {
                            "lineHeight": "1",
                            "letterSpacing": "0.1em",
                            "fontWeight": "700"
                        }],
                        "headline-sm": ["24px", {
                            "lineHeight": "1.4",
                            "fontWeight": "600"
                        }],
                        "button": ["14px", {
                            "lineHeight": "1",
                            "fontWeight": "600"
                        }],
                        "display-lg-mobile": ["36px", {
                            "lineHeight": "1.2",
                            "fontWeight": "800"
                        }],
                        "headline-md": ["32px", {
                            "lineHeight": "1.3",
                            "fontWeight": "700"
                        }],
                        "display-lg": ["48px", {
                            "lineHeight": "1.1",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "800"
                        }]
                    }
                },
            },
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .hotspot-pulse {
            box-shadow: 0 0 0 0 rgba(4, 107, 210, 0.7);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(4, 107, 210, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(4, 107, 210, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(4, 107, 210, 0);
            }
        }

        .ambient-shadow {
            box-shadow: 0 10px 32px -5px rgba(0, 72, 120, 0.15);
        }

        .text-gradient {
            background: linear-gradient(135deg, #003154 0%, #046BD2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="bg-background font-body-md text-on-surface antialiased">
    <!-- TopNavBar -->
    <nav class="fixed top-0 w-full z-50 glass border-b border-outline-variant/30 shadow-sm">
        <div class="flex justify-between items-center px-4 md:px-margin-desktop py-4 max-w-container-max mx-auto">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-primary flex items-center justify-center rounded-xl">
                    <span class="material-symbols-outlined text-white"
                        style="font-variation-settings: 'FILL' 1;">school</span>
                </div>
                <span class="font-headline-sm text-headline-sm font-extrabold text-primary">Politeknik Negeri
                    Banyuwangi</span>
            </div>
            <div class="hidden md:flex items-center gap-8">
                <a class="nav-link text-tech-blue font-bold border-b-2 border-tech-blue pb-1 font-body-md text-body-md transition-colors duration-300"
                    href="#hero" data-section="hero">Tour</a>
                <a class="nav-link text-on-surface-variant font-body-md text-body-md hover:text-tech-blue transition-colors duration-300"
                    href="#features" data-section="features">Facilities</a>
                <a class="nav-link text-on-surface-variant font-body-md text-body-md hover:text-tech-blue transition-colors duration-300"
                    href="#gallery" data-section="gallery">Maps</a>
                <a href="{{ $firstTourUrl ?? route('tour.index') }}"
                    class="bg-primary text-on-primary px-6 py-2.5 rounded-full font-button text-button active:scale-95 transition-transform hover:bg-deep-navy shadow-sm inline-block">
                    Start Tour
                </a>
            </div>
            <button class="md:hidden text-primary">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden" id="hero">
        <div class="absolute inset-0 z-0">
            <img class="w-full h-full object-cover"
                data-alt="A panoramic wide-angle view of a modern university campus with sleek architecture and green landscaping under a bright blue sky. The scene is illuminated with professional, high-key natural lighting that emphasizes the contemporary feel of the academic buildings. The aesthetic is clean and academic, utilizing a palette of deep navy blues and vibrant accents. The mood is inspiring and forward-thinking, capturing the essence of a leading technological institute."
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuD9LqDLa7AcAiYL1FdoHpUeLkjlTmf_wRSW_LXMLtZq_M69TX0v9Dd5yAadslNGPjmzbYtNszYDuDKKXDhq3swfljhlBq9nMNAfwQJFPw06CeqS4gyVXuI_xqToAIxF__R-ISr417vOY1KzmFgDfX3BdmJQ5xr_Q1wIYIWoEtdrF45PqhZmXLcn8H4Cjdgo7TREb5ZgjnlHgZwbN1JIbxPk_5adgNA1PTvlfAZZiAj9Tip0SBz5P7YR-Uh83-_eiuJrkdikRV6t0DI" />
            <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/40 to-transparent"></div>
        </div>
        <div class="relative z-10 px-4 md:px-margin-desktop max-w-container-max mx-auto w-full">
            <div class="max-w-2xl">
                <span
                    class="inline-block bg-secondary-container text-on-secondary-container px-4 py-1.5 rounded-full font-label-caps text-label-caps mb-6">
                    CAMPUS VIRTUAL EXPERIENCE
                </span>
                <h1
                    class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-white mb-6">
                    Eksplorasi Kampus Jinggo Tanpa Batas
                </h1>
                <p class="font-body-lg text-body-lg text-white/90 mb-10 leading-relaxed">
                    Nikmati pengalaman mendalam menjelajahi setiap sudut Politeknik Negeri Banyuwangi melalui teknologi
                    360°. Temukan fasilitas kelas dunia dan lingkungan belajar inspiratif kami dari mana saja.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ $firstTourUrl ?? route('tour.index') }}"
                        class="bg-vibrant-gold text-primary font-button text-button px-8 py-4 rounded-full inline-flex items-center gap-2 hover:bg-secondary-fixed transition-all active:scale-95 shadow-lg">
                        <span class="material-symbols-outlined"
                            style="font-variation-settings: 'FILL' 1;">explore</span>
                        Mulai Tour 360°
                    </a>
                </div>
            </div>
        </div>
        
    </section>
    <!-- Features Section -->
    <section class="py-24 bg-surface-white" id="features">
        <div class="px-4 md:px-margin-desktop max-w-container-max mx-auto">
            <div class="text-center mb-16">
                <h2 class="font-headline-md text-headline-md text-primary mb-4">Fitur Utama Tur</h2>
                <div class="w-20 h-1 bg-vibrant-gold mx-auto"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                <!-- Card 1 -->
                <div
                    class="bg-surface-container-low p-8 rounded-xl border border-outline-variant/30 hover:border-tech-blue transition-all group ambient-shadow">
                    <div
                        class="w-14 h-14 bg-tech-blue/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-tech-blue transition-colors">
                        <span
                            class="material-symbols-outlined text-tech-blue group-hover:text-white text-3xl">360</span>
                    </div>
                    <h3 class="font-headline-sm text-headline-sm text-primary mb-3">Virtual Tour 360°</h3>
                    <p class="text-on-surface-variant font-body-md text-body-md">Pandangan panorama lengkap yang
                        memungkinkan Anda berpindah antar ruangan dan gedung dengan lancar.</p>
                </div>
                <!-- Card 2 -->
                <div
                    class="bg-surface-container-low p-8 rounded-xl border border-outline-variant/30 hover:border-tech-blue transition-all group ambient-shadow">
                    <div
                        class="w-14 h-14 bg-tech-blue/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-tech-blue transition-colors">
                        <span
                            class="material-symbols-outlined text-tech-blue group-hover:text-white text-3xl">map</span>
                    </div>
                    <h3 class="font-headline-sm text-headline-sm text-primary mb-3">Peta Interaktif</h3>
                    <p class="text-on-surface-variant font-body-md text-body-md">Navigasi mudah melalui peta digital
                        yang terintegrasi untuk menemukan lokasi fakultas dan unit penunjang.</p>
                </div>
                <!-- Card 3 -->
                <div
                    class="bg-surface-container-low p-8 rounded-xl border border-outline-variant/30 hover:border-tech-blue transition-all group ambient-shadow">
                    <div
                        class="w-14 h-14 bg-tech-blue/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-tech-blue transition-colors">
                        <span
                            class="material-symbols-outlined text-tech-blue group-hover:text-white text-3xl">home_work</span>
                    </div>
                    <h3 class="font-headline-sm text-headline-sm text-primary mb-3">Fasilitas Unggulan</h3>
                    <p class="text-on-surface-variant font-body-md text-body-md">Detail mendalam tentang Lab, Workshop,
                        dan Studio modern yang mendukung pembelajaran vokasi berkualitas.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section -->
    <section class="py-24 bg-surface overflow-hidden">
        <div class="px-4 md:px-margin-desktop max-w-container-max mx-auto">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2 relative">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-vibrant-gold/10 rounded-full blur-3xl"></div>
                    <div class="relative z-10 rounded-2xl overflow-hidden shadow-2xl">
                        <img class="w-full aspect-[4/3] object-cover"
                            data-alt="A cinematic capture of enthusiastic students walking through the modern, open-air corridor of a contemporary university building. The architecture features clean lines, large glass windows, and a palette of warm wood and cool gray concrete. Sunlight filters through the structure, creating dynamic shadows and a bright, energetic atmosphere. The scene represents academic vibrancy and the modern spirit of a state polytechnic institute."
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuA3Xot4lXIE-uFVZpjoAmreW_g3zwbPXrrhH9xxkG3gcV6ruAeIYg4tXJNZPkXdGccLIovbFBaHrbbYGsIhG57y2veqjedmL7VRH5T8G4q-yOum1ptNgJ9FbrLdiq3v9IpB8rzDPZHef3p-Xc5o7VkjQM44sNb5pd6egjeK1faVfhaxgp-jcO6MmMSV0ROZOe_4qaoZiKgLH7LTkL83Ze5xSOSuqunYUadd3SFK--73MXKYiahXgfLibS-1-uwQSAkwx4Z_AOK2mH8" />
                    </div>
                    <div class="absolute -bottom-6 -right-6 bg-primary p-6 rounded-xl shadow-xl hidden md:block">
                        <div class="text-vibrant-gold font-display-lg text-4xl mb-1">#1</div>
                        <div class="text-white font-label-caps text-xs">Pilihan Vokasi<br />Banyuwangi</div>
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <h2 class="font-headline-md text-headline-md text-primary mb-6">Membangun Masa Depan di Bumi
                        Blambangan</h2>
                    <p class="font-body-lg text-body-lg text-on-surface-variant mb-6 leading-relaxed">
                        Politeknik Negeri Banyuwangi (Poliwangi) adalah lembaga pendidikan tinggi vokasi yang
                        berkomitmen mencetak tenaga kerja terampil dan inovatif. Terletak di ujung timur Pulau Jawa,
                        kampus kami menggabungkan kearifan lokal dengan teknologi modern.
                    </p>
                    <p class="font-body-md text-body-md text-on-surface-variant mb-8">
                        Tur virtual ini dirancang untuk memberikan transparansi dan aksesibilitas bagi calon mahasiswa,
                        orang tua, dan mitra industri untuk melihat langsung infrastruktur pendidikan yang kami
                        banggakan tanpa batasan jarak.
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-tech-blue">verified</span>
                            <span class="font-button text-primary">Akreditasi Unggul</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-tech-blue">groups</span>
                            <span class="font-button text-primary">Kemitraan Industri</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-tech-blue">science</span>
                            <span class="font-button text-primary">Laboratorium Modern</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-tech-blue">stars</span>
                            <span class="font-button text-primary">Prestasi Nasional</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Highlights Gallery (Bento Grid) -->
    <section class="py-24 bg-surface-white" id="gallery">
        <div class="px-4 md:px-margin-desktop max-w-container-max mx-auto">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="font-headline-md text-headline-md text-primary mb-2">Spot Terpopuler</h2>
                    <p class="text-on-surface-variant">Jelajahi landmark kampus yang paling sering dikunjungi</p>
                </div>
                <a href="{{ route('tour.index') }}"
                    class="text-tech-blue font-bold flex items-center gap-2 hover:underline">
                    Lihat Semua <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-gutter h-[800px] md:h-[600px]">
                @foreach($buildings as $i => $building)
                    @php
                        $img = $building->thumbnail_path ? asset('storage/'.$building->thumbnail_path) : null;
                        $isLarge = $i === 0;
                        $isMedium = $i === 1;
                        $colSpan = $isLarge ? 'md:col-span-2' : ($isMedium ? 'md:col-span-2' : '');
                        $rowSpan = $isLarge ? 'md:row-span-2' : '';
                    @endphp
                    <a href="{{ route('tour.show', $building) }}"
                       class="{{ $colSpan }} {{ $rowSpan }} group relative overflow-hidden rounded-2xl cursor-pointer">
                        @if($img)
                            <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="{{ $img }}" alt="{{ $building->name }}" />
                        @else
                            <div class="w-full h-full bg-surface-container-high flex items-center justify-center">
                                <span class="material-symbols-outlined text-6xl text-outline">image</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            @if($isLarge)
                                <span class="bg-vibrant-gold text-primary text-[10px] font-bold px-2 py-0.5 rounded mb-2 inline-block">POPULER</span>
                            @endif
                            <h4 class="font-headline-sm {{ $isLarge ? 'text-2xl' : 'text-xl' }}">{{ $building->name }}</h4>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="bg-white/20 backdrop-blur-md px-6 py-3 rounded-full border border-white/30 text-white font-button">Lihat 360°</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Call to Action Section -->
    {{-- <section class="py-24 px-4 md:px-margin-desktop">
        <div class="max-w-container-max mx-auto bg-primary rounded-[2rem] overflow-hidden relative">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-full h-full"
                    style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;">
                </div>
            </div>
            <div class="relative z-10 p-8 md:p-20 text-center">
                <h2
                    class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-white mb-6">
                    Siap Bergabung Bersama Kami?</h2>
                <p class="text-white/80 font-body-lg text-body-lg mb-10 max-w-2xl mx-auto">
                    Setelah menjelajahi kampus kami secara virtual, saatnya Anda merasakan atmosfer Poliwangi secara
                    langsung. Pendaftaran mahasiswa baru telah dibuka.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <button
                        class="bg-vibrant-gold text-primary font-button text-button px-10 py-4 rounded-full hover:bg-secondary-fixed transition-all active:scale-95">
                        Daftar Sekarang
                    </button>
                    <button
                        class="bg-transparent border border-white/30 text-white font-button text-button px-10 py-4 rounded-full hover:bg-white/10 transition-all active:scale-95">
                        Unduh Brosur
                    </button>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- Footer -->
    <footer class="bg-primary pt-20 pb-10" id="footer">
        <div class="max-w-container-max mx-auto px-4 md:px-margin-desktop">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-gutter mb-16">
                <!-- Brand/Contact -->
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 bg-white flex items-center justify-center rounded-lg">
                            <span class="material-symbols-outlined text-primary text-sm"
                                style="font-variation-settings: 'FILL' 1;">school</span>
                        </div>
                        <span class="font-headline-sm text-headline-sm font-bold text-on-primary">Poliwangi</span>
                    </div>
                    <p class="text-on-primary/80 font-body-md text-sm mb-6">
                        Jl. Raya Jember No.KM13, Kawang, Labanasem, Kec. Kabat, Kabupaten Banyuwangi, Jawa Timur 68461
                    </p>
                    <div class="flex gap-4">
                        <a class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-vibrant-gold hover:text-primary transition-colors"
                            href="#">
                            <span class="material-symbols-outlined text-xl">language</span>
                        </a>
                        <a class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-vibrant-gold hover:text-primary transition-colors"
                            href="#">
                            <span class="material-symbols-outlined text-xl">alternate_email</span>
                        </a>
                        <a class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-vibrant-gold hover:text-primary transition-colors"
                            href="#">
                            <span class="material-symbols-outlined text-xl">call</span>
                        </a>
                    </div>
                </div>
                <!-- Academic -->
                <div>
                    <h5 class="text-white font-bold mb-6">Akademik</h5>
                    <ul class="space-y-4">
                        <li><a class="text-on-primary/80 hover:text-vibrant-gold transition-colors font-body-md text-sm"
                                href="#">Admission</a></li>
                        <li><a class="text-on-primary/80 hover:text-vibrant-gold transition-colors font-body-md text-sm"
                                href="#">Fakultas Teknik</a></li>
                        <li><a class="text-on-primary/80 hover:text-vibrant-gold transition-colors font-body-md text-sm"
                                href="#">Fakultas Pariwisata</a></li>
                        <li><a class="text-on-primary/80 hover:text-vibrant-gold transition-colors font-body-md text-sm"
                                href="#">Fakultas Pertanian</a></li>
                    </ul>
                </div>
                <!-- Information -->
                <div>
                    <h5 class="text-white font-bold mb-6">Informasi</h5>
                    <ul class="space-y-4">
                        <li><a class="text-on-primary/80 hover:text-vibrant-gold transition-colors font-body-md text-sm"
                                href="#">Campus Directory</a></li>
                        <li><a class="text-on-primary/80 hover:text-vibrant-gold transition-colors font-body-md text-sm"
                                href="#">Berita Kampus</a></li>
                        <li><a class="text-on-primary/80 hover:text-vibrant-gold transition-colors font-body-md text-sm"
                                href="#">Event</a></li>
                        <li><a class="text-on-primary/80 hover:text-vibrant-gold transition-colors font-body-md text-sm"
                                href="#">Karir</a></li>
                    </ul>
                </div>
                <!-- Legal -->
                <div>
                    <h5 class="text-white font-bold mb-6">Legalitas</h5>
                    <ul class="space-y-4">
                        <li><a class="text-on-primary/80 hover:text-vibrant-gold transition-colors font-body-md text-sm"
                                href="#">Privacy Policy</a></li>
                        <li><a class="text-on-primary/80 hover:text-vibrant-gold transition-colors font-body-md text-sm"
                                href="#">Terms of Service</a></li>
                        <li><a class="text-on-primary/80 hover:text-vibrant-gold transition-colors font-body-md text-sm"
                                href="#">Statuta</a></li>
                        <li><a class="text-on-primary/80 hover:text-vibrant-gold transition-colors font-body-md text-sm"
                                href="#">Akreditasi</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-on-primary/60 text-xs">© 2024 Politeknik Negeri Banyuwangi. All rights reserved.</p>
                <div class="flex gap-6">
                    <a class="text-on-primary/60 hover:text-white text-xs" href="#">Sitemap</a>
                    <a class="text-on-primary/60 hover:text-white text-xs" href="#">Cookies</a>
                    <a class="text-on-primary/60 hover:text-white text-xs" href="#">Accessibility</a>
                </div>
            </div>
        </div>
    </footer>
    <script>
        // Scroll spy – active nav link
        const NAV_LINKS = document.querySelectorAll('.nav-link');
        const SECTIONS = ['hero', 'features', 'gallery', 'footer'];

        function updateActiveNav() {
            let active = 'hero';
            SECTIONS.forEach(id => {
                const el = document.getElementById(id);
                if (el && el.getBoundingClientRect().top <= 120) active = id;
            });
            NAV_LINKS.forEach(a => {
                const isActive = a.dataset.section === active;
                a.classList.toggle('text-tech-blue', isActive);
                a.classList.toggle('font-bold', isActive);
                a.classList.toggle('border-b-2', isActive);
                a.classList.toggle('border-tech-blue', isActive);
                a.classList.toggle('pb-1', isActive);
                a.classList.toggle('text-on-surface-variant', !isActive);
            });
        }

        // Smooth scroll implementation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Sticky header + scroll spy
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('py-2');
                nav.classList.remove('py-4');
            } else {
                nav.classList.add('py-4');
                nav.classList.remove('py-2');
            }
            updateActiveNav();
        });

        updateActiveNav();
    </script>
</body>

</html>
