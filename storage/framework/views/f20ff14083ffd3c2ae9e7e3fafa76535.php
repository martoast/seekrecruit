<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
    'name' => null,
    'options' => [],
    'placeholder' => null,
    'error' => null,
    'required' => false,
    'value' => null,
    'id' => null,
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
    'label' => null,
    'name' => null,
    'options' => [],
    'placeholder' => null,
    'error' => null,
    'required' => false,
    'value' => null,
    'id' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $inputId = $id ?? ($name ? 'select-' . $name : 'select-' . uniqid());
    $hasError = $error || ($name && $errors->has($name));
    $errorMessage = $error ?? ($name ? $errors->first($name) : null);
    $borderClasses = $hasError
        ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
        : 'border-gray-200 focus:border-primary-500 focus:ring-primary-500';
    $selectedValue = $value ?? ($name ? old($name) : null);
?>

<div class="space-y-1.5">
    <?php if($label): ?>
        <label for="<?php echo e($inputId); ?>" class="block text-sm font-medium text-gray-700">
            <?php echo e($label); ?>

            <?php if($required): ?><span class="text-red-500">*</span><?php endif; ?>
        </label>
    <?php endif; ?>
    <select
        id="<?php echo e($inputId); ?>"
        <?php if($name): ?> name="<?php echo e($name); ?>" <?php endif; ?>
        <?php if($required): ?> required <?php endif; ?>
        <?php echo e($attributes->merge(['class' => "block w-full rounded-xl border-2 px-4 py-2.5 text-gray-900 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:bg-gray-50 disabled:cursor-not-allowed {$borderClasses}"])); ?>

    >
        <?php if($placeholder): ?>
            <option value="" <?php if(is_null($selectedValue) || $selectedValue === ''): ?> selected <?php endif; ?> disabled><?php echo e($placeholder); ?></option>
        <?php endif; ?>
        <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionValue => $optionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($optionValue); ?>" <?php if((string) $selectedValue === (string) $optionValue): echo 'selected'; endif; ?>>
                <?php echo e($optionLabel); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <?php if($errorMessage): ?>
        <p class="text-sm text-red-600"><?php echo e($errorMessage); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH /var/www/html/resources/views/components/ui/select.blade.php ENDPATH**/ ?>