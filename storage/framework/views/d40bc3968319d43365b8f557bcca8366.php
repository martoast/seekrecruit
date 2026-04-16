<a href="<?php echo e(route('positions.show', $position)); ?>" class="block">
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-primary-300 hover:shadow-lg transition-all duration-300 cursor-pointer h-full group">
        <div class="relative h-40 bg-linear-to-br from-primary-500 to-primary-600 overflow-hidden">
            <?php if($position->image_url): ?>
                <img src="<?php echo e($position->image_url); ?>" alt="<?php echo e($position->title); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
            <?php else: ?>
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
            <?php endif; ?>
            <?php if($position->isOpen()): ?>
                <div class="absolute top-3 right-3">
                    <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['variant' => 'success','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'success','size' => 'sm']); ?>Open <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="p-5">
            <?php if($position->company_name || $position->company_logo_url): ?>
                <div class="flex items-center gap-2 mb-3">
                    <?php if($position->company_logo_url): ?>
                        <img src="<?php echo e($position->company_logo_url); ?>" alt="<?php echo e($position->company_name ?? 'Company'); ?>" class="w-8 h-8 rounded-lg object-contain bg-gray-50 border border-gray-100" />
                    <?php else: ?>
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <?php if($position->company_name): ?>
                        <span class="text-sm font-medium text-gray-600"><?php echo e($position->company_name); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors line-clamp-2">
                <?php echo e($position->title); ?>

            </h3>

            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 mb-3">
                <div class="flex items-center gap-1">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <?php echo e($position->location); ?>

                </div>
                <div class="flex items-center gap-1">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <?php echo e($position->created_at->format('M j, Y')); ?>

                </div>
            </div>

            <div class="flex flex-wrap items-center gap-1.5 mb-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary-50 text-primary-700">
                    <?php echo e($position->modality?->label() ?? 'On-site'); ?>

                </span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-violet-50 text-violet-700">
                    <?php echo e($position->employment_type?->label() ?? 'Full-time'); ?>

                </span>
                <?php if($position->salary_range): ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                        <?php echo e($position->salary_range); ?>

                    </span>
                <?php endif; ?>
            </div>

            <p class="text-gray-600 text-sm line-clamp-2 mb-4">
                <?php echo e($position->description); ?>

            </p>

            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                <span class="text-sm text-primary-600 font-medium inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                    View Details
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            </div>
        </div>
    </div>
</a>
<?php /**PATH /Volumes/Seagate/Max/Seekrecruit/seekrecruit/resources/views/positions/_card.blade.php ENDPATH**/ ?>