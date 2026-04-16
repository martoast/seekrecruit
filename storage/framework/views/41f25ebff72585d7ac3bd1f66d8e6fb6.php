<?php $__env->startSection('title', 'Seek & Recruit Network — Where talent meets opportunity'); ?>

<?php $__env->startSection('content'); ?>
    <div class="overflow-hidden">
        
        <section class="relative min-h-screen bg-dark-500 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-32 -right-32 w-[600px] h-[600px] rounded-full border border-primary-500/20 animate-[float_20s_ease-in-out_infinite]"></div>
                <div class="absolute top-1/2 -left-24 w-[400px] h-[400px] rounded-full bg-primary-500/5 animate-[float_15s_ease-in-out_infinite]" style="animation-delay: -5s;"></div>
                <div class="absolute bottom-32 right-1/4 w-32 h-32 rounded-full bg-accent-green/10 animate-[float_12s_ease-in-out_infinite]" style="animation-delay: -2s;"></div>
                <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.1) 1px, transparent 1px); background-size: 60px 60px;"></div>
                <div class="absolute inset-0 bg-linear-to-br from-dark-500 via-dark-500/95 to-primary-900/30"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-32 lg:pt-32 lg:pb-40">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center min-h-[70vh]">
                    <div class="space-y-8">
                        <span class="inline-flex items-center gap-2 text-primary-300 text-sm font-medium tracking-wider uppercase">
                            <span class="w-8 h-px bg-primary-400"></span>
                            Recruitment Reimagined
                        </span>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl text-white leading-[1.1] tracking-tight font-bold">
                            Where Talent
                            <span class="block text-primary-400">Meets Opportunity</span>
                        </h1>

                        <p class="text-lg sm:text-xl text-gray-300 max-w-xl leading-relaxed">
                            Join a curated network of professionals and connect with companies
                            that value what you bring to the table. Your next chapter starts here.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <a href="<?php echo e(route('register')); ?>">
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','size' => 'lg','class' => 'w-full sm:w-auto']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'lg','class' => 'w-full sm:w-auto']); ?>
                                    Start Your Journey
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
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
                            <a href="<?php echo e(route('positions.index')); ?>">
                                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'ghost','size' => 'lg','class' => 'w-full sm:w-auto text-white border border-white/20 hover:bg-white/10 hover:border-white/40']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'ghost','size' => 'lg','class' => 'w-full sm:w-auto text-white border border-white/20 hover:bg-white/10 hover:border-white/40']); ?>
                                    View Open Positions
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

                        <div class="flex items-center gap-6 pt-8">
                            <div class="flex -space-x-3">
                                <div class="w-10 h-10 rounded-full bg-linear-to-br from-primary-400 to-primary-600 border-2 border-dark-500 flex items-center justify-center text-white text-xs font-bold">JD</div>
                                <div class="w-10 h-10 rounded-full bg-linear-to-br from-emerald-400 to-emerald-600 border-2 border-dark-500 flex items-center justify-center text-white text-xs font-bold">MK</div>
                                <div class="w-10 h-10 rounded-full bg-linear-to-br from-amber-400 to-amber-600 border-2 border-dark-500 flex items-center justify-center text-white text-xs font-bold">AS</div>
                                <div class="w-10 h-10 rounded-full bg-dark-400 border-2 border-dark-500 flex items-center justify-center text-white text-xs font-medium">+2k</div>
                            </div>
                            <p class="text-sm text-gray-400">
                                Trusted by <span class="text-white font-medium">2,000+</span> professionals
                            </p>
                        </div>
                    </div>

                    <div class="hidden lg:block relative">
                        <div class="relative bg-white/5 backdrop-blur-sm rounded-3xl border border-white/10 p-8">
                            <div class="space-y-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-2xl bg-linear-to-br from-primary-500 to-primary-700 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold text-lg">Senior Product Designer</p>
                                        <p class="text-gray-400">TechCorp Inc. · Remote</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 bg-primary-500/20 text-primary-300 text-sm rounded-full">Full-time</span>
                                    <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 text-sm rounded-full">$120k - $160k</span>
                                    <span class="px-3 py-1 bg-white/10 text-gray-300 text-sm rounded-full">Remote</span>
                                </div>
                                <div class="pt-4 border-t border-white/10">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2 text-accent-green">
                                            <div class="w-2 h-2 rounded-full bg-accent-green"></div>
                                            <span class="text-sm font-medium">Actively Hiring</span>
                                        </div>
                                        <span class="text-gray-400 text-sm">Posted 2h ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -top-6 -right-6 bg-white rounded-2xl shadow-2xl p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-accent-green/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-accent-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-900 font-medium text-sm">Application Sent!</p>
                                    <p class="text-gray-500 text-xs">Just now</p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-8 -left-8 bg-dark-400/80 backdrop-blur-sm rounded-2xl border border-white/10 p-5">
                            <div class="flex items-center gap-4">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-white">94%</p>
                                    <p class="text-xs text-gray-400">Success Rate</p>
                                </div>
                                <div class="w-px h-10 bg-white/20"></div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-accent-green">48h</p>
                                    <p class="text-xs text-gray-400">Avg. Response</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 h-32 bg-linear-to-t from-white to-transparent"></div>
        </section>

        
        <section class="relative bg-white py-16 -mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                    <div class="text-center">
                        <p class="text-4xl sm:text-5xl lg:text-6xl font-light text-dark-500 mb-2">
                            2k<span class="text-primary-500">+</span>
                        </p>
                        <p class="text-gray-500 text-sm uppercase tracking-wider">Active Candidates</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl sm:text-5xl lg:text-6xl font-light text-dark-500 mb-2">
                            150<span class="text-primary-500">+</span>
                        </p>
                        <p class="text-gray-500 text-sm uppercase tracking-wider">Partner Companies</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl sm:text-5xl lg:text-6xl font-light text-dark-500 mb-2">
                            94<span class="text-primary-500">%</span>
                        </p>
                        <p class="text-gray-500 text-sm uppercase tracking-wider">Match Success</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl sm:text-5xl lg:text-6xl font-light text-dark-500 mb-2">
                            48<span class="text-primary-500">h</span>
                        </p>
                        <p class="text-gray-500 text-sm uppercase tracking-wider">Avg. Response</p>
                    </div>
                </div>
            </div>
        </section>

        
        <section class="bg-gray-50 py-20">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl sm:text-4xl font-bold text-dark-500 mb-4">Ready to find your next opportunity?</h2>
                <p class="text-lg text-gray-600 mb-8">Create your profile in minutes and start applying to curated positions today.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo e(route('register')); ?>">
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'lg']); ?>Get Started Free <?php echo $__env->renderComponent(); ?>
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
                    <a href="<?php echo e(route('positions.index')); ?>">
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'lg']); ?>Browse Positions <?php echo $__env->renderComponent(); ?>
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
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/home.blade.php ENDPATH**/ ?>