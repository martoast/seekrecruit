<?php
    $user = auth()->user();
    $initials = $user
        ? collect(explode(' ', $user->name))->take(2)->map(fn ($p) => mb_substr($p, 0, 1))->implode('')
        : '?';
?>
<nav class="bg-white border-b border-gray-200 sticky top-0 z-40 backdrop-blur-sm bg-white/95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="<?php echo e(route('home')); ?>" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-linear-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-xl">S&R</span>
                    </div>
                    <span class="text-xl font-bold text-gray-900 hidden sm:block">Seek & Recruit</span>
                </a>

                <?php if(auth()->guard()->check()): ?>
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <?php if($user->isCandidate()): ?>
                            <a href="<?php echo e(route('candidate.dashboard')); ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium <?php echo e(request()->routeIs('candidate.dashboard') ? 'text-gray-900' : 'text-gray-600'); ?> hover:text-primary-600 transition-colors">Dashboard</a>
                            <a href="<?php echo e(route('candidate.profile.edit')); ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium <?php echo e(request()->routeIs('candidate.profile.*') ? 'text-gray-900' : 'text-gray-600'); ?> hover:text-primary-600 transition-colors">Profile</a>
                            <a href="<?php echo e(route('candidate.applications.index')); ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium <?php echo e(request()->routeIs('candidate.applications.*') ? 'text-gray-900' : 'text-gray-600'); ?> hover:text-primary-600 transition-colors">Applications</a>
                            <a href="<?php echo e(route('positions.index')); ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium <?php echo e(request()->routeIs('positions.*') ? 'text-gray-900' : 'text-gray-600'); ?> hover:text-primary-600 transition-colors">Positions</a>
                            <a href="<?php echo e(route('candidate.referrals.index')); ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium <?php echo e(request()->routeIs('candidate.referrals.*') ? 'text-gray-900' : 'text-gray-600'); ?> hover:text-primary-600 transition-colors">Referrals</a>
                        <?php elseif($user->isAdmin()): ?>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 hover:text-primary-600 transition-colors">Dashboard</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="<?php echo e(route('positions.index')); ?>" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-600 hover:text-primary-600 transition-colors">Positions</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex items-center space-x-4">
                <?php if(auth()->guard()->check()): ?>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="flex items-center">
                        <?php echo csrf_field(); ?>
                        <div class="flex items-center space-x-2 px-3 py-2 rounded-xl bg-gray-50">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                <span class="text-primary-700 font-semibold text-sm"><?php echo e($initials); ?></span>
                            </div>
                            <span class="hidden sm:block text-sm font-medium text-gray-700"><?php echo e($user->name); ?></span>
                        </div>
                        <button type="submit" class="ml-2 p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="Logout">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>">
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'ghost']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'ghost']); ?>Login <?php echo $__env->renderComponent(); ?>
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
                    <a href="<?php echo e(route('register')); ?>">
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary']); ?>Register <?php echo $__env->renderComponent(); ?>
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
                <?php endif; ?>

                <button type="button" data-mobile-menu-toggle class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
<?php /**PATH /var/www/html/resources/views/partials/navbar.blade.php ENDPATH**/ ?>