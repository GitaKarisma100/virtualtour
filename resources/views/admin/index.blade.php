@extends('admin.master')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-sm pb-sm">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary tracking-tight">Dashboard</h2>
            <p class="text-body-md font-body-md text-secondary">Control system access and assign granular
                permissions.</p>
        </div>
        <button
            class="flex items-center gap-sm px-lg py-sm bg-primary text-on-primary rounded-lg hover:opacity-90 active:scale-[0.98] transition-all">
            <span class="material-symbols-outlined text-[18px]" data-icon="add">add</span>
            <span class="text-label-md font-label-md">Add User</span>
        </button>
    </div>

    <!-- Dashboard Stats (Subtle Grid) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-lg">
        <div class="p-lg bg-surface-container-lowest border border-outline-variant rounded-xl">
            <p class="text-label-md font-label-md text-secondary">Total Users</p>
            <p class="text-headline-lg font-headline-lg text-primary mt-base">1,284</p>
        </div>
        <div class="p-lg bg-surface-container-lowest border border-outline-variant rounded-xl">
            <p class="text-label-md font-label-md text-secondary">Active Now</p>
            <div class="flex items-center gap-sm mt-base">
                <span class="h-2 w-2 rounded-full bg-primary"></span>
                <p class="text-headline-lg font-headline-lg text-primary">42</p>
            </div>
        </div>
        <div class="p-lg bg-surface-container-lowest border border-outline-variant rounded-xl">
            <p class="text-label-md font-label-md text-secondary">Pending Invites</p>
            <p class="text-headline-lg font-headline-lg text-primary mt-base">18</p>
        </div>
        <div class="p-lg bg-surface-container-lowest border border-outline-variant rounded-xl">
            <p class="text-label-md font-label-md text-secondary">System Health</p>
            <p class="text-headline-lg font-headline-lg text-primary mt-base">99.9%</p>
        </div>
    </div>

    <!-- Main Data Table Component -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[640px]">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">
                        Name</th>
                    <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">
                        Role</th>
                    <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">
                        Status</th>
                    <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider">
                        Last Login</th>
                    <th class="px-lg py-md text-label-md font-label-md text-secondary uppercase tracking-wider text-right">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-container">
                <!-- Row 1 -->
                <tr class="hover:bg-surface-container-low transition-colors group">
                    <td class="px-lg py-md">
                        <div class="flex items-center gap-md">
                            <div class="h-10 w-10 bg-primary text-on-primary rounded flex items-center justify-center font-bold text-label-md">
                                JD</div>
                            <div>
                                <p class="text-body-md font-bold text-primary">Jane Doe</p>
                                <p class="text-label-md text-secondary">jane.doe@enterprise.com</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-lg py-md">
                        <span class="px-sm py-base bg-primary text-on-primary rounded text-label-md font-bold">Admin</span>
                    </td>
                    <td class="px-lg py-md">
                        <div class="flex items-center gap-sm">
                            <div class="h-2 w-2 rounded-full bg-primary"></div>
                            <span class="text-body-md text-primary">Active</span>
                        </div>
                    </td>
                    <td class="px-lg py-md text-body-md text-secondary">2 mins ago</td>
                    <td class="px-lg py-md text-right">
                        <button class="p-sm text-secondary hover:text-primary transition-colors">
                            <span class="material-symbols-outlined" data-icon="more_vert">more_vert</span>
                        </button>
                    </td>
                </tr>
                <!-- Row 2 -->
                <tr class="hover:bg-surface-container-low transition-colors group">
                    <td class="px-lg py-md">
                        <div class="flex items-center gap-md">
                            <div class="h-10 w-10 bg-surface-container-highest text-primary rounded flex items-center justify-center font-bold text-label-md">
                                MS</div>
                            <div>
                                <p class="text-body-md font-bold text-primary">Marcus Sterling</p>
                                <p class="text-label-md text-secondary">m.sterling@agency.io</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-lg py-md">
                        <span class="px-sm py-base border border-outline-variant text-primary rounded text-label-md">Editor</span>
                    </td>
                    <td class="px-lg py-md">
                        <div class="flex items-center gap-sm">
                            <div class="h-2 w-2 rounded-full bg-primary"></div>
                            <span class="text-body-md text-primary">Active</span>
                        </div>
                    </td>
                    <td class="px-lg py-md text-body-md text-secondary">1 hour ago</td>
                    <td class="px-lg py-md text-right">
                        <button class="p-sm text-secondary hover:text-primary transition-colors">
                            <span class="material-symbols-outlined" data-icon="more_vert">more_vert</span>
                        </button>
                    </td>
                </tr>
                <!-- Row 3 -->
                <tr class="hover:bg-surface-container-low transition-colors group">
                    <td class="px-lg py-md">
                        <div class="flex items-center gap-md">
                            <div class="h-10 w-10 bg-surface-container-highest text-primary rounded flex items-center justify-center font-bold text-label-md">
                                AL</div>
                            <div>
                                <p class="text-body-md font-bold text-primary">Avery Lowen</p>
                                <p class="text-label-md text-secondary">lowen@client.net</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-lg py-md">
                        <span class="px-sm py-base border border-outline-variant text-primary rounded text-label-md">Viewer</span>
                    </td>
                    <td class="px-lg py-md">
                        <div class="flex items-center gap-sm">
                            <div class="h-2 w-2 rounded-full bg-outline"></div>
                            <span class="text-body-md text-secondary">Suspended</span>
                        </div>
                    </td>
                    <td class="px-lg py-md text-body-md text-secondary">3 days ago</td>
                    <td class="px-lg py-md text-right">
                        <button class="p-sm text-secondary hover:text-primary transition-colors">
                            <span class="material-symbols-outlined" data-icon="more_vert">more_vert</span>
                        </button>
                    </td>
                </tr>
                <!-- Row 4 -->
                <tr class="hover:bg-surface-container-low transition-colors group">
                    <td class="px-lg py-md">
                        <div class="flex items-center gap-md">
                            <div class="h-10 w-10 bg-surface-container-highest text-primary rounded flex items-center justify-center font-bold text-label-md">
                                RT</div>
                            <div>
                                <p class="text-body-md font-bold text-primary">Riley Thorne</p>
                                <p class="text-label-md text-secondary">riley.thorne@enterprise.com</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-lg py-md">
                        <span class="px-sm py-base border border-outline-variant text-primary rounded text-label-md">Editor</span>
                    </td>
                    <td class="px-lg py-md">
                        <div class="flex items-center gap-sm">
                            <div class="h-2 w-2 rounded-full bg-primary"></div>
                            <span class="text-body-md text-primary">Active</span>
                        </div>
                    </td>
                    <td class="px-lg py-md text-body-md text-secondary">5 hours ago</td>
                    <td class="px-lg py-md text-right">
                        <button class="p-sm text-secondary hover:text-primary transition-colors">
                            <span class="material-symbols-outlined" data-icon="more_vert">more_vert</span>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
        <!-- Pagination -->
        <div class="px-lg py-md flex flex-col sm:flex-row items-center justify-between gap-sm border-t border-outline-variant bg-surface-container-low">
            <p class="text-label-md text-secondary">Showing 1 to 4 of 1,284 users</p>
            <div class="flex items-center gap-sm">
                <button class="px-md py-base border border-outline-variant rounded hover:bg-surface-container text-label-md transition-colors">Previous</button>
                <button class="px-md py-base bg-primary text-on-primary rounded text-label-md font-bold">1</button>
                <button class="px-md py-base border border-outline-variant rounded hover:bg-surface-container text-label-md transition-colors">2</button>
                <button class="px-md py-base border border-outline-variant rounded hover:bg-surface-container text-label-md transition-colors">3</button>
                <span class="text-secondary px-base">...</span>
                <button class="px-md py-base border border-outline-variant rounded hover:bg-surface-container text-label-md transition-colors">Next</button>
            </div>
        </div>
    </div>

    <!-- Bento Grid Section: Advanced Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg pt-xl">
        <div class="col-span-12 lg:col-span-8 bg-surface-container-lowest border border-outline-variant rounded-xl p-lg h-[300px] flex flex-col justify-between">
            <div>
                <h3 class="text-headline-md font-headline-md text-primary">User Activity Over Time</h3>
                <p class="text-body-md text-secondary">Daily logins and administrative actions.</p>
            </div>
            <div class="h-32 w-full flex items-end gap-base">
                <div class="flex-1 bg-surface-container rounded-t h-[40%]"></div>
                <div class="flex-1 bg-surface-container rounded-t h-[60%]"></div>
                <div class="flex-1 bg-surface-container rounded-t h-[30%]"></div>
                <div class="flex-1 bg-primary rounded-t h-[80%]"></div>
                <div class="flex-1 bg-surface-container rounded-t h-[50%]"></div>
                <div class="flex-1 bg-surface-container rounded-t h-[70%]"></div>
                <div class="flex-1 bg-surface-container rounded-t h-[90%]"></div>
                <div class="flex-1 bg-primary rounded-t h-[40%]"></div>
                <div class="flex-1 bg-surface-container rounded-t h-[60%]"></div>
                <div class="flex-1 bg-surface-container rounded-t h-[30%]"></div>
                <div class="flex-1 bg-surface-container rounded-t h-[80%]"></div>
                <div class="flex-1 bg-surface-container rounded-t h-[50%]"></div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-4 bg-primary text-on-primary rounded-xl p-lg flex flex-col justify-between overflow-hidden relative">
            <div class="z-10">
                <h3 class="text-headline-md font-headline-md">Security Audit</h3>
                <p class="text-body-md opacity-80 mt-base">Your system passed the last 12 scheduled security
                    checks with zero vulnerabilities.</p>
            </div>
            <div class="mt-lg z-10">
                <button class="w-full py-md bg-on-primary text-primary font-bold rounded-lg hover:bg-opacity-90 transition-all text-label-md">Run
                    Full Audit</button>
            </div>
            <div class="absolute -right-8 -bottom-8 opacity-10">
                <span class="material-symbols-outlined text-[160px]" data-icon="verified_user">verified_user</span>
            </div>
        </div>
    </div>
@endsection
