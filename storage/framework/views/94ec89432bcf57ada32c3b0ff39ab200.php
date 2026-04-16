<?php
    $user = auth()->user();
?>
<div data-mobile-menu class="fixed inset-0 z-50 md:hidden hidden">
    <div data-mobile-menu-close class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="fixed inset-y-0 left-0 w-full max-w-sm bg-white shadow-xl">
        <div class="flex flex-col h-full">
            <div class="px-4 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-linear-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-xl">S&R</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Seek & Recruit</span>
                    </div>
                    <button type="button" data-mobile-menu-close class="p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
                <?php if(auth()->guard()->check()): ?>
                    <?php if($user->isCandidate()): ?>
                        <a href="<?php echo e(route('candidate.dashboard')); ?>" class="flex items-center px-4 py-3 text-base font-medium text-gray-900 rounded-xl hover:bg-gray-50">Dashboard</a>
                        <a href="<?php echo e(route('candidate.profile.edit')); ?>" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Profile</a>
                        <a href="<?php echo e(route('candidate.applications.index')); ?>" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Applications</a>
                        <a href="<?php echo e(route('positions.index')); ?>" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Positions</a>
                        <a href="<?php echo e(route('candidate.referrals.index')); ?>" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Referrals</a>
                    <?php elseif($user->isAdmin()): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center px-4 py-3 text-base font-medium text-gray-900 rounded-xl hover:bg-gray-50">Dashboard</a>
                        <a href="<?php echo e(route('admin.candidates.index')); ?>" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Candidates</a>
                        <a href="<?php echo e(route('admin.applications.index')); ?>" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Applications</a>
                        <a href="<?php echo e(route('admin.interviews.index')); ?>" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Interviews</a>
                        <a href="<?php echo e(route('admin.positions.index')); ?>" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Positions</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo e(route('positions.index')); ?>" class="flex items-center px-4 py-3 text-base font-medium text-gray-600 rounded-xl hover:bg-gray-50">Positions</a>
                <?php endif; ?>
            </nav>

            <div class="border-t border-gray-200 px-4 py-4">
                <?php if(auth()->guard()->check()): ?>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','variant' => 'ghost','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'ghost','class' => 'w-full']); ?>Logout <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                    </form>
                <?php else: ?>
                    <div class="space-y-2">
                        <a href="<?php echo e(route('login')); ?>" class="block"><?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'ghost','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'ghost','class' => 'w-full']); ?>Login <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?></a>
                        <a href="<?php echo e(route('register')); ?>" class="block"><?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','class' => 'w-full']); ?>Register <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/partials/mobile-menu.blade.php ENDPATH**/ ?>