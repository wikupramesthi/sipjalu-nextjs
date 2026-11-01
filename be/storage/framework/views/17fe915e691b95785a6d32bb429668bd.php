<?php $__env->startSection('title', 'Notifikasi'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Notifikasi','page' => 'Dashboard','active' => 'Semua Notifikasi','route' => ''.e(route('dashboard.index')).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Breadcrumb::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal269900abaed345884ce342681cdc99f6)): ?>
<?php $attributes = $__attributesOriginal269900abaed345884ce342681cdc99f6; ?>
<?php unset($__attributesOriginal269900abaed345884ce342681cdc99f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal269900abaed345884ce342681cdc99f6)): ?>
<?php $component = $__componentOriginal269900abaed345884ce342681cdc99f6; ?>
<?php unset($__componentOriginal269900abaed345884ce342681cdc99f6); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-md-4">

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <h6 class="mb-0">Semua Notifikasi</h6>
                <?php if($unreadCount > 0): ?>
                    <span class="badge bg-danger rounded-pill"><?php echo e($unreadCount); ?> baru</span>
                <?php endif; ?>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group btn-group-sm" role="group" aria-label="Filter notifikasi">
                    <a href="<?php echo e(route('notifications.index')); ?>"
                        class="btn <?php echo e(request('filter') !== 'unread' ? 'btn-primary' : 'btn-light'); ?>">Semua</a>
                    <a href="<?php echo e(route('notifications.index', ['filter' => 'unread'])); ?>"
                        class="btn <?php echo e(request('filter') === 'unread' ? 'btn-primary' : 'btn-light'); ?>">Belum dibaca</a>
                </div>
                <?php if($unreadCount > 0): ?>
                    <a href="javascript:void(0)" class="btn btn-sm btn-light mark-all-read">
                        <i class="bi bi-check2-all me-1"></i>Tandai semua dibaca
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="list-group list-group-flush">
            <?php $__empty_2 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                <?php
                    $href = $notification->data['path'] ?? ($notification->data['url'] ?? 'javascript:void(0)');
                    if (! str_starts_with($href, 'javascript')) {
                        $parts = parse_url($href);
                        if (! empty($parts['host']) && $parts['host'] !== request()->getHost()) {
                            $href = request()->root()
                                . ($parts['path'] ?? '/')
                                . (! empty($parts['query']) ? '?' . $parts['query'] : '');
                        }
                    }
                    $icon = $notification->data['icon'] ?? 'bi-bell';
                ?>
                <a href="<?php echo e($href); ?>"
                    class="mark-as-read list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 px-3 <?php echo e($notification->read_at ? '' : 'bg-light'); ?>"
                    data-id="<?php echo e($notification->id); ?>">
                    <span class="notif-icon" aria-hidden="true"><i class="bi <?php echo e($icon); ?>"></i></span>
                    <span class="flex-grow-1 min-w-0">
                        <span class="d-flex align-items-center gap-2 flex-wrap">
                            <strong class="mb-0"><?php echo e($notification->data['title'] ?? 'Notifikasi'); ?></strong>
                            <?php if(! $notification->read_at): ?>
                                <span class="badge bg-primary">Baru</span>
                            <?php endif; ?>
                        </span>
                        <span class="text-muted small d-block text-truncate"><?php echo e($notification->data['message'] ?? '-'); ?></span>
                        <small class="text-muted" title="<?php echo e($notification->created_at->format('d M Y H:i')); ?>">
                            <i class="bi bi-clock me-1"></i><?php echo e($notification->created_at->diffForHumans()); ?>

                        </small>
                    </span>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                <div class="text-center py-5 text-muted">
                    <div class="empty-notif-icon mx-auto mb-2"><i class="bi bi-bell-slash"></i></div>
                    <p class="mb-1 fw-semibold">
                        <?php echo e(request('filter') === 'unread' ? 'Semua sudah dibaca' : 'Tidak ada notifikasi'); ?>

                    </p>
                    <small>Notifikasi agenda yang masuk akan tampil di sini.</small>
                </div>
            <?php endif; ?>
        </div>
        <?php if($notifications->hasPages()): ?>
            <div class="card-footer d-flex justify-content-center">
                <?php echo e($notifications->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('after-style'); ?>
<style>
    .notif-icon {
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 0.9rem;
        background: rgba(94, 114, 228, 0.1);
        color: var(--modern-brand);
        font-size: 1.1rem;
    }
    .list-group-item.mark-as-read:hover .notif-icon {
        background: var(--modern-brand);
        color: #fff;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\pages\notifications\index.blade.php ENDPATH**/ ?>