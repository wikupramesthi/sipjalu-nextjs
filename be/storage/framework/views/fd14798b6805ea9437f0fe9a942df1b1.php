<nav aria-label="breadcrumb" class="argon-breadcrumb">
    <ol class="breadcrumb mb-0 pb-0 pt-1 px-0">
        <li class="breadcrumb-item">
            <a href="<?php echo e($route ?? url('/')); ?>"><i class="bi bi-house-door"></i></a>
        </li>
        <?php if(isset($page)): ?>
            <?php if($page): ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo e($route ?? url('/')); ?>" class="opacity-5"><?php echo e($page); ?></a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
        <?php if(isset($active)): ?>
            <?php if($active): ?>
                <li class="breadcrumb-item active" aria-current="page"><?php echo e($active); ?></li>
            <?php endif; ?>
        <?php endif; ?>
    </ol>
    <h5 class="font-weight-bolder mb-0"><?php echo e($title ?? ''); ?></h5>
</nav>
<?php /**PATH C:\xampp\htdocs\agenda\be\resources\views/components/web/breadcrumb.blade.php ENDPATH**/ ?>