<footer class="bg-dark-500 text-white mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                        <span class="text-white font-bold text-xl">S&R</span>
                    </div>
                    <span class="text-xl font-bold">Seek & Recruit Network</span>
                </div>
                <p class="text-gray-300 text-sm max-w-md">
                    Connecting talented candidates with exceptional opportunities. Your career journey starts here.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-lg mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?php echo e(route('positions.index')); ?>" class="text-gray-300 hover:text-white transition-colors">Browse Positions</a></li>
                    <li><a href="<?php echo e(route('register')); ?>" class="text-gray-300 hover:text-white transition-colors">Register</a></li>
                    <li><a href="<?php echo e(route('login')); ?>" class="text-gray-300 hover:text-white transition-colors">Login</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold text-lg mb-4">Contact</h3>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li>info@seekrecruit.com</li>
                    <li>+1 (555) 123-4567</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 mt-8 pt-8 text-center text-sm text-gray-400">
            <p>&copy; <?php echo e(now()->year); ?> Seek & Recruit Network. All rights reserved.</p>
        </div>
    </div>
</footer>
<?php /**PATH /Volumes/Seagate/Max/Seekrecruit/seekrecruit/resources/views/partials/footer.blade.php ENDPATH**/ ?>