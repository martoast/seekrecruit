<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'status',
    'type' => 'application',
    'size' => 'md',
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
    'status',
    'type' => 'application',
    'size' => 'md',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $applicationConfig = [
        'registered' => ['label' => 'Registered', 'classes' => 'text-blue-700 bg-blue-50 border-blue-200'],
        'preselected' => ['label' => 'Pre-selected', 'classes' => 'text-cyan-700 bg-cyan-50 border-cyan-200'],
        'interview' => ['label' => 'Interview', 'classes' => 'text-purple-700 bg-purple-50 border-purple-200'],
        'evaluation' => ['label' => 'Evaluation', 'classes' => 'text-amber-700 bg-amber-50 border-amber-200'],
        'finalist' => ['label' => 'Finalist', 'classes' => 'text-indigo-700 bg-indigo-50 border-indigo-200'],
        'hired' => ['label' => 'Hired', 'classes' => 'text-emerald-700 bg-emerald-50 border-emerald-200'],
        'discarded' => ['label' => 'Discarded', 'classes' => 'text-gray-700 bg-gray-50 border-gray-200'],
    ];

    $referralConfig = [
        'pending' => ['label' => 'Pending', 'classes' => 'text-amber-700 bg-amber-50 border-amber-200'],
        'registered' => ['label' => 'Registered', 'classes' => 'text-blue-700 bg-blue-50 border-blue-200'],
        'hired' => ['label' => 'Hired', 'classes' => 'text-emerald-700 bg-emerald-50 border-emerald-200'],
        'rewarded' => ['label' => 'Rewarded', 'classes' => 'text-purple-700 bg-purple-50 border-purple-200'],
    ];

    $interviewConfig = [
        'technical' => ['label' => 'Technical', 'classes' => 'text-blue-700 bg-blue-50 border-blue-200'],
        'hr' => ['label' => 'HR Interview', 'classes' => 'text-purple-700 bg-purple-50 border-purple-200'],
        'final' => ['label' => 'Final Interview', 'classes' => 'text-emerald-700 bg-emerald-50 border-emerald-200'],
    ];

    $key = $status instanceof \BackedEnum ? $status->value : (string) $status;

    $config = match ($type) {
        'referral' => $referralConfig[$key] ?? $referralConfig['pending'],
        'interview' => $interviewConfig[$key] ?? $interviewConfig['technical'],
        default => $applicationConfig[$key] ?? $applicationConfig['registered'],
    };

    $sizeClasses = $size === 'sm' ? 'text-xs' : 'text-sm';
?>

<span <?php echo e($attributes->merge(['class' => "inline-flex items-center font-medium rounded-lg border px-2.5 py-0.5 {$sizeClasses} {$config['classes']}"])); ?>>
    <?php echo e($config['label']); ?>

</span>
<?php /**PATH /var/www/html/resources/views/components/ui/status-badge.blade.php ENDPATH**/ ?>