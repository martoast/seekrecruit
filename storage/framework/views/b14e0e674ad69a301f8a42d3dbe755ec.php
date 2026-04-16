<?php $__env->startSection('title', 'Positions - Admin'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $openCount = $positions->where('status', App\Enums\PositionStatus::OPEN)->count();
        $closedCount = $positions->where('status', App\Enums\PositionStatus::CLOSED)->count();
        $draftCount = $positions->where('status', App\Enums\PositionStatus::DRAFT)->count();

        $statusBadge = fn ($status) => match ($status?->value) {
            'open' => ['label' => 'Open', 'pill' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20', 'dot' => 'bg-emerald-500'],
            'closed' => ['label' => 'Closed', 'pill' => 'bg-gray-100 text-gray-600', 'dot' => 'bg-gray-400'],
            'draft' => ['label' => 'Draft', 'pill' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20', 'dot' => 'bg-amber-500'],
            default => ['label' => 'Unknown', 'pill' => 'bg-gray-100 text-gray-600', 'dot' => 'bg-gray-400'],
        };
    ?>

    <div class="min-h-screen">
        <div class="mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Positions</h1>
                    <p class="mt-1 text-gray-500 text-sm sm:text-base">Manage job positions and openings</p>
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
                        Create Position
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

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo e($positions->count()); ?></p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Open</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo e($openCount); ?></p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Draft</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo e($draftCount); ?></p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Closed</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo e($closedCount); ?></p>
                </div>
            </div>
        </div>

        <?php if($positions->isEmpty()): ?>
            <div class="bg-white rounded-xl border border-gray-200 p-12">
                <div class="text-center max-w-md mx-auto">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No positions yet</h3>
                    <p class="text-gray-500 mb-6">Create your first job position to start receiving applications.</p>
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
<?php $component->withAttributes(['variant' => 'primary']); ?>Create Position <?php echo $__env->renderComponent(); ?>
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
        <?php else: ?>
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Position</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <?php if($position->image_url): ?>
                                            <div class="w-16 h-12 rounded-lg overflow-hidden bg-gray-100">
                                                <img src="<?php echo e($position->image_url); ?>" alt="<?php echo e($position->title); ?>" class="w-full h-full object-cover" />
                                            </div>
                                        <?php else: ?>
                                            <div class="w-16 h-12 rounded-lg bg-linear-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <?php if($position->company_logo_url): ?>
                                                <img src="<?php echo e($position->company_logo_url); ?>" alt="<?php echo e($position->company_name ?? 'Company'); ?>" class="w-8 h-8 rounded-lg object-contain bg-gray-50 border border-gray-100" />
                                            <?php endif; ?>
                                            <div>
                                                <p class="font-medium text-gray-900"><?php echo e($position->title); ?></p>
                                                <?php if($position->company_name): ?>
                                                    <p class="text-xs text-gray-500"><?php echo e($position->company_name); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-700"><?php echo e($position->location); ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php $badge = $statusBadge($position->status); ?>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium <?php echo e($badge['pill']); ?>">
                                            <span class="w-1.5 h-1.5 rounded-full <?php echo e($badge['dot']); ?>"></span>
                                            <?php echo e($badge['label']); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-600"><?php echo e($position->created_at->format('M j, Y')); ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <form method="POST" action="<?php echo e(route('admin.positions.update', $position)); ?>" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <input type="hidden" name="client_id" value="<?php echo e($position->client_id); ?>" />
                                                <input type="hidden" name="title" value="<?php echo e($position->title); ?>" />
                                                <input type="hidden" name="description" value="<?php echo e($position->description); ?>" />
                                                <input type="hidden" name="requirements" value="<?php echo e($position->requirements); ?>" />
                                                <input type="hidden" name="location" value="<?php echo e($position->location); ?>" />
                                                <input type="hidden" name="company_name" value="<?php echo e($position->company_name); ?>" />
                                                <input type="hidden" name="salary_min" value="<?php echo e($position->salary_min); ?>" />
                                                <input type="hidden" name="salary_max" value="<?php echo e($position->salary_max); ?>" />
                                                <input type="hidden" name="salary_currency" value="<?php echo e($position->salary_currency); ?>" />
                                                <input type="hidden" name="employment_type" value="<?php echo e($position->employment_type?->value); ?>" />
                                                <input type="hidden" name="modality" value="<?php echo e($position->modality?->value); ?>" />
                                                <select name="status" onchange="this.form.submit()" class="text-xs font-medium border border-gray-200 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 bg-white">
                                                    <option value="open" <?php if($position->status?->value === 'open'): echo 'selected'; endif; ?>>Open</option>
                                                    <option value="draft" <?php if($position->status?->value === 'draft'): echo 'selected'; endif; ?>>Draft</option>
                                                    <option value="closed" <?php if($position->status?->value === 'closed'): echo 'selected'; endif; ?>>Closed</option>
                                                </select>
                                            </form>
                                            <a href="<?php echo e(route('admin.positions.edit', $position)); ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Volumes/Seagate/Max/Seekrecruit/seekrecruit/resources/views/admin/positions/index.blade.php ENDPATH**/ ?>