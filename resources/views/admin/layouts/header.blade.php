<header class="docked full-width top-0 sticky z-40 bg-surface-container-lowest border-b border-outline-variant flex justify-between items-center px-lg py-md">
    <div class="flex items-center gap-lg flex-1">
        <button class="lg:hidden p-sm text-secondary hover:text-primary transition-colors" @click="sidebarOpen = !sidebarOpen">
            <span class="material-symbols-outlined" data-icon="menu">menu</span>
        </button>
        <div class="relative w-full max-w-md">
            <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-secondary text-[20px]"
                data-icon="search">search</span>
            <input class="w-full pl-xl pr-md py-sm bg-surface-container-low border border-outline-variant focus:border-primary focus:ring-0 rounded-lg text-body-md transition-all outline-none"
                placeholder="Search users..." type="text" />
        </div>
    </div>
    <div class="flex items-center gap-md">
        <div class="h-8 w-8 rounded-full bg-surface-container-highest flex items-center justify-center overflow-hidden border border-outline-variant">
            <img alt="Enterprise Admin"
                data-alt="A professional, high-contrast studio portrait of a corporate executive for a profile avatar."
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuAS7HaVrHeSdVMLN_BmDyGP0u9xo20tW6RJU7qQbgLuua7mjobhbRpYWehYOabZ4SR0O7MXGLMaz9PUlzmnJuEJdXkf4ptbnGS07QefXbH5-sFLIxhqLm7MxS_zMJddYMOvvaQ2udg3BblMcATtbMQYPaVmYcZX5r1GepThYQh3JDe3EhkYUtog6VQ-67dvGGmJcFAWTkiS23zAPQIfeeUBcmkYhwXtA8OjJYsm0-jqqL5MSx-XWm20ADZIJferGCIjDFDnlLW_3vI" />
        </div>
    </div>
</header>
