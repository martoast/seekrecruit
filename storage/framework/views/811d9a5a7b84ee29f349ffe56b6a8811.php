<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'padding' => 'md',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'padding' => 'md',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $paddings = [
        'none' => 'p-0',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];
?>

<div <?php echo e($attributes->merge(['class' => 'bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden'])); ?>>
    <?php if(isset($header)): ?>
        <div class="px-6 py-4 border-b border-gray-100"><?php echo e($header); ?></div>
    <?php endif; ?>

    <div class="<?php echo e($paddings[$padding] ?? $paddings['md']); ?>">
        <?php echo e($slot); ?>

    </div>

    <?php if(isset($footer)): ?>
        <div class="px-6 py-4 bg-gray-50 rounded-b-2xl border-t border-gray-100"><?php echo e($footer); ?></div>
    <?php endif; ?>
</div>
<?php /**PATH /Volumes/Seagate/Max/Seekrecruit/seekrecruit/resources/views/components/ui/card.blade.php ENDPATH**/ ?>