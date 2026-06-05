<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 z-40 lg:hidden" @click="sidebarOpen = false"></div>

<aside class="fixed left-0 top-0 h-full w-[260px] bg-surface-container-lowest border-r border-outline-variant flex flex-col py-lg z-50 -translate-x-full lg:translate-x-0 transition-transform duration-300" :class="{ 'translate-x-0': sidebarOpen }">
    <div class="px-lg mb-xl">
        <h1 class="text-headline-md font-headline-md font-extrabold text-primary">Virtual Tour</h1>
        <p class="text-label-md font-label-md text-secondary">Campus Admin Panel</p>
    </div>
    <nav class="flex-1 px-sm space-y-base">
        <a class="flex items-center gap-md px-md py-sm transition-all duration-100 rounded {{ request()->routeIs('admin') ? 'text-primary font-bold bg-surface-container border-r-2 border-primary rounded-sm' : 'text-secondary hover:bg-surface-container-low' }}"
            href="{{ route('admin') }}" @click="sidebarOpen = false">
            <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
            <span class="text-label-md font-label-md">Dashboard</span>
        </a>
        <a class="flex items-center gap-md px-md py-sm transition-all duration-100 rounded {{ request()->routeIs('admin.buildings*') ? 'text-primary font-bold bg-surface-container border-r-2 border-primary rounded-sm' : 'text-secondary hover:bg-surface-container-low' }}"
            href="{{ route('admin.buildings.index') }}" @click="sidebarOpen = false">
            <span class="material-symbols-outlined" data-icon="apartment">apartment</span>
            <span class="text-label-md font-label-md">Buildings</span>
        </a>
    </nav>
    <div class="px-sm mt-auto border-t border-outline-variant pt-lg">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-md px-md py-sm w-full text-secondary hover:bg-surface-container-low transition-colors duration-150 rounded">
                <span class="material-symbols-outlined" data-icon="logout">logout</span>
                <span class="text-label-md font-label-md">Logout</span>
            </button>
        </form>
    </div>
</aside>
