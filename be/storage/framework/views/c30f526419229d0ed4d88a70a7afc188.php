<!-- Modal Update Sidebar -->
<div class="modal fade" id="modalUpdateSidebar-<?php echo e($item->uuid); ?>" tabindex="-1"
    aria-labelledby="modalLabelSidebar-<?php echo e($item->uuid); ?>" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo e(route('pages.updateSidebar', $item->uuid)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelSidebar-<?php echo e($item->uuid); ?>">Show in Sidebar?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-wrap text-break">
                    <p>
                        Are you sure you want to change the <em>sidebar</em> status for the page <strong><?php echo e($item->title); ?></strong> ?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Yes, Change</button>
                </div>
            </div>
        </form>
    </div>
</div><?php /**PATH C:\xampp\htdocs\agenda\be\resources\views/pages/halaman/modal-update-sidebar.blade.php ENDPATH**/ ?>