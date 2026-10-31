<?php
    $growth = (float) ($growth ?? 0);
    $daysDiff = (int) ($daysDiff ?? 30);
    // growth dibulatkan 1 desimal lalu hilangkan .0 biar layout tidak melebar & tanpa koma
    $growthAbs = abs($growth);
    $growthFormatted = rtrim(rtrim(number_format($growthAbs, 1, '.', ''), '0'), '.');
    // periode label simpel tanpa desimal
    $periodeLabel = $daysDiff === 1
        ? 'dibanding kemarin'
        : "dibanding {$daysDiff} hari sebelumnya";
?>
<?php if($growth > 0.05): ?>
    <div class="stat-trend" title="<?php echo e($periodeLabel); ?>">
        <i class="bi bi-arrow-up-right text-success"></i>
        <span class="text-success fw-medium">Naik <?php echo e($growthFormatted); ?>%</span>
        <span class="text-muted small"><?php echo e($periodeLabel); ?></span>
    </div>
<?php elseif($growth < -0.05): ?>
    <div class="stat-trend" title="<?php echo e($periodeLabel); ?>">
        <i class="bi bi-arrow-down-right text-danger"></i>
        <span class="text-danger fw-medium">Turun <?php echo e($growthFormatted); ?>%</span>
        <span class="text-muted small"><?php echo e($periodeLabel); ?></span>
    </div>
<?php else: ?>
    <div class="stat-trend text-muted" title="<?php echo e($periodeLabel); ?>">
        <i class="bi bi-dash"></i>
        <span>Stabil 0%</span>
        <span class="small"><?php echo e($periodeLabel); ?></span>
    </div>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\agenda\be\resources\views/partials/stat-trend.blade.php ENDPATH**/ ?>