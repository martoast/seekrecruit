<?php
    $user = auth()->user();
    $initials = $user
        ? collect(explode(' ', $user->name))->take(2)->map(fn ($p) => mb_substr($p, 0, 1))->implode('')
        : '?';

    // Each nav item can declare a 'role' key: 'super' means Super-Admin only.
    // No 'role' key = visible to both HR Admin and Super Admin.
    $navItems = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['label' => 'Candidates', 'route' => 'admin.candidates.index', 'active' => 'admin.candidates.*', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
        ['label' => 'Applications', 'route' => 'admin.applications.index', 'active' => 'admin.applications.*', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['label' => 'Interviews', 'route' => 'admin.interviews.index', 'active' => 'admin.interviews.*', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label' => 'Positions', 'route' => 'admin.positions.index', 'active' => 'admin.positions.*', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        ['label' => 'Clients', 'route' => 'admin.clients.index', 'active' => 'admin.clients.*', 'role' => 'super', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
        ['label' => 'Admins', 'route' => 'admin.admins.index', 'active' => 'admin.admins.*', 'role' => 'super', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
    ];

    $visibleItems = collect($navItems)->filter(function ($item) use ($user) {
        if (($item['role'] ?? null) === 'super') {
            return $user?->isSuperAdmin();
        }
        return true;
    });
?>
<aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-white border-r border-gray-200">
    <div class="flex flex-col flex-1 min-h-0 pt-5 pb-4">
        <div class="flex items-center flex-shrink-0 px-6 mb-5">
            <div class="w-10 h-10 bg-linear-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center">
                <span class="text-white font-bold text-xl">S&R</span>
            </div>
            <span class="ml-3 text-xl font-bold text-gray-900">Admin</span>
        </div>

        <nav class="flex-1 px-3 space-y-1 overflow-y-auto">
            <?php $__currentLoopData = $visibleItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $isActive = request()->routeIs($item['active']); ?>
                <a href="<?php echo e(route($item['route'])); ?>"
                    class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?php echo e($isActive ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50'); ?>">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($item['icon']); ?>"/>
                    </svg>
                    <?php echo e($item['label']); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>

        <div class="flex-shrink-0 px-3 py-3 border-t border-gray-200">
            <div class="flex items-center px-3 py-2 mb-2">
                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-primary-700 font-semibold text-sm"><?php echo e($initials); ?></span>
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate"><?php echo e($user?->name); ?></p>
                    <p class="text-xs text-gray-500 truncate"><?php echo e($user?->email); ?></p>
                    <?php if($user?->isHrAdmin() && $user->client): ?>
                        <p class="text-xs text-primary-600 font-medium truncate mt-0.5"><?php echo e($user->client->name); ?></p>
                    <?php elseif($user?->isSuperAdmin()): ?>
                        <p class="text-xs text-violet-600 font-medium truncate mt-0.5">Super Admin</p>
                    <?php endif; ?>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full flex items-center px-3 py-2.5 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200">
                    <svg class="mr-3 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>
<?php /**PATH /var/www/html/resources/views/partials/sidebar.blade.php ENDPATH**/ ?>