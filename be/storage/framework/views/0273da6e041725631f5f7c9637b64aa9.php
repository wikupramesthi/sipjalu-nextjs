<?php
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();
    $range = 3;
    $start = max(1, $current - $range);
    $end = min($last, $current + $range);
?>

<?php if($paginator->hasPages()): ?>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            
            <?php if($current > 1): ?>
                <li class="page-item"><a class="page-link" href="<?php echo e($paginator->url(1)); ?>">&laquo;</a></li>
            <?php else: ?>
                <li class="page-item disabled"><span class="page-link" aria-hidden="true">&laquo;</span></li>
            <?php endif; ?>

            
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-hidden="true">&lsaquo;</span></li>
            <?php else: ?>
                <li class="page-item"><a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">&lsaquo;</a></li>
            <?php endif; ?>

            
            <?php if($start > 1): ?>
                <li class="page-item"><a class="page-link" href="<?php echo e($paginator->url(1)); ?>">1</a></li>
                <?php if($start > 2): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
            <?php endif; ?>

            <?php for($i = $start; $i <= $end; $i++): ?>
                <?php if($i == $current): ?>
                    <li class="page-item active" aria-current="page"><span class="page-link"><?php echo e($i); ?></span></li>
                <?php else: ?>
                    <li class="page-item"><a class="page-link" href="<?php echo e($paginator->url($i)); ?>"><?php echo e($i); ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if($end < $last): ?>
                <?php if($end < $last - 1): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <li class="page-item"><a class="page-link" href="<?php echo e($paginator->url($last)); ?>"><?php echo e($last); ?></a></li>
            <?php endif; ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item"><a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">&rsaquo;</a></li>
            <?php else: ?>
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-hidden="true">&rsaquo;</span></li>
            <?php endif; ?>

            
            <?php if($current < $last): ?>
                <li class="page-item"><a class="page-link" href="<?php echo e($paginator->url($last)); ?>">&raquo;</a></li>
            <?php else: ?>
                <li class="page-item disabled"><span class="page-link" aria-hidden="true">&raquo;</span></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\vendor\pagination/minimal.blade.php ENDPATH**/ ?>