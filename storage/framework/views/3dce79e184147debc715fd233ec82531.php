<?php $__env->startSection('title', 'Admin Dashboard - Seek & Recruit Network'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
        $statusesByKey = $stats['applications_by_status'] ?? [];
        $totalApps = array_sum($statusesByKey);

        $statusMeta = [
            'registered' => ['label' => 'Registered', 'bar' => 'bg-blue-500'],
            'preselected' => ['label' => 'Pre-selected', 'bar' => 'bg-cyan-500'],
            'interview' => ['label' => 'Interview', 'bar' => 'bg-purple-500'],
            'evaluation' => ['label' => 'Evaluation', 'bar' => 'bg-amber-500'],
            'finalist' => ['label' => 'Finalist', 'bar' => 'bg-indigo-500'],
            'hired' => ['label' => 'Hired', 'bar' => 'bg-emerald-500'],
            'discarded' => ['label' => 'Discarded', 'bar' => 'bg-gray-400'],
        ];
    ?>

    <div class="min-h-screen">
        <?php if(!empty($activeClient)): ?>
            <div class="mb-6 flex items-center justify-between gap-3 p-4 bg-primary-50 border border-primary-100 rounded-xl">
                <div class="flex items-center gap-3">
                    <?php if($activeClient->logo_url): ?>
                        <img src="<?php echo e($activeClient->logo_url); ?>" alt="<?php echo e($activeClient->name); ?>" class="w-10 h-10 rounded-lg object-contain bg-white border border-gray-100 p-1" />
                    <?php else: ?>
                        <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center text-sm font-semibold">
                            <?php echo e(strtoupper(mb_substr($activeClient->name, 0, 2))); ?>

                        </div>
                    <?php endif; ?>
                    <div>
                        <p class="text-xs text-primary-700 uppercase tracking-wide font-medium">Viewing</p>
                        <p class="font-semibold text-gray-900"><?php echo e($activeClient->name); ?></p>
                    </div>
                </div>
                <?php if(auth()->user()->isSuperAdmin()): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-sm font-medium text-primary-700 hover:text-primary-800">
                        Show all clients →
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
                    <p class="mt-1 text-gray-500 text-sm sm:text-base"><?php echo e($greeting); ?>, here's your recruitment overview</p>
                </div>
                <a href="<?php echo e(route('admin.positions.create')); ?>">
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary']); ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        New Position
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                </a>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <a href="<?php echo e(route('admin.candidates.index')); ?>" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total_candidates']); ?></p>
                        <p class="text-sm text-gray-500 mt-1">Total Candidates</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <a href="<?php echo e(route('admin.applications.index')); ?>" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total_applications']); ?></p>
                        <p class="text-sm text-gray-500 mt-1">Total Applications</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <a href="<?php echo e(route('admin.interviews.index')); ?>" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['interviews_this_week']); ?></p>
                        <p class="text-sm text-gray-500 mt-1">Interviews This Week</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-3xl font-bold text-gray-900"><?php echo e($statusesByKey['hired'] ?? 0); ?></p>
                        <p class="text-sm text-gray-500 mt-1">Hired Candidates</p>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Application Pipeline</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Current status distribution</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <?php $__currentLoopData = $statusesByKey; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $meta = $statusMeta[$key] ?? ['label' => ucfirst($key), 'bar' => 'bg-gray-400'];
                                $percent = $totalApps > 0 ? round(($count / $totalApps) * 100) : 0;
                            ?>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700"><?php echo e($meta['label']); ?></span>
                                    <span class="text-sm font-semibold text-gray-900"><?php echo e($count); ?></span>
                                </div>
                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full <?php echo e($meta['bar']); ?> transition-all duration-500" style="width: <?php echo e($percent); ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Top Universities</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Most common institutions</p>
                    </div>
                    <div class="p-6">
                        <?php if(count($stats['top_universities'])): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $stats['top_universities']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $uni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $badgeClasses = match ($index) {
                                            0 => 'bg-amber-100 text-amber-700',
                                            1 => 'bg-gray-200 text-gray-700',
                                            2 => 'bg-orange-100 text-orange-700',
                                            default => 'bg-gray-100 text-gray-500',
                                        };
                                    ?>
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold <?php echo e($badgeClasses); ?>">
                                            <?php echo e($index + 1); ?>

                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate"><?php echo e($uni['name']); ?></p>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700"><?php echo e($uni['count']); ?></span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-sm text-gray-500 text-center py-8">No university data yet</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Recent Applications</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Latest candidate submissions</p>
                    </div>
                    <a href="<?php echo e(route('admin.applications.index')); ?>" class="text-sm font-medium text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                        View all
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>

                <?php if(count($stats['recent_applications'])): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Candidate</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Position</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Applied</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php $__currentLoopData = $stats['recent_applications']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-900"><?php echo e($app->candidate?->user?->name ?? '—'); ?></p>
                                            <p class="text-sm text-gray-500"><?php echo e($app->candidate?->user?->email); ?></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-medium text-gray-900"><?php echo e($app->position?->title); ?></p>
                                            <p class="text-sm text-gray-500"><?php echo e($app->position?->location); ?></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if (isset($component)) { $__componentOriginaldf5a194c1ccdd1698e9a89f0cb5bf2c8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf5a194c1ccdd1698e9a89f0cb5bf2c8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.status-badge','data' => ['status' => $app->status,'size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($app->status),'size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldf5a194c1ccdd1698e9a89f0cb5bf2c8)): ?>
<?php $attributes = $__attributesOriginaldf5a194c1ccdd1698e9a89f0cb5bf2c8; ?>
<?php unset($__attributesOriginaldf5a194c1ccdd1698e9a89f0cb5bf2c8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldf5a194c1ccdd1698e9a89f0cb5bf2c8)): ?>
<?php $component = $__componentOriginaldf5a194c1ccdd1698e9a89f0cb5bf2c8; ?>
<?php unset($__componentOriginaldf5a194c1ccdd1698e9a89f0cb5bf2c8); ?>
<?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-900"><?php echo e($app->created_at->format('M j, Y')); ?></p>
                                            <p class="text-sm text-gray-500"><?php echo e($app->created_at->diffForHumans()); ?></p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="<?php echo e(route('admin.applications.show', $app)); ?>" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                                Review
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No applications yet</h3>
                        <p class="text-gray-500">Applications will appear here when candidates apply.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Volumes/Seagate/Max/Seekrecruit/seekrecruit/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>