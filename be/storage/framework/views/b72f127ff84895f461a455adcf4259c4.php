<header>
    <nav class="navbar navbar-expand navbar-light navbar-top">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- <div class="argon-search ms-2 d-none d-lg-block">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="sidebar-search" placeholder="Cari menu...">
                    </div>
                </div> -->
                <button type="button" class="btn btn-sm btn-light d-none d-lg-flex align-items-center gap-2 ms-2"
                    data-bs-toggle="modal" data-bs-target="#globalSearchModal" title="Pencarian global (Ctrl+K)">
                    <i class="bi bi-search"></i>
                    <span class="text-muted small">Cari semua…</span>
                    <kbd style="font-size:10px;">Ctrl K</kbd>
                </button>
                <div class="me-auto"></div>
                <ul class="navbar-nav ms-auto mb-lg-0 align-items-center">
                    <li class="nav-item d-flex align-items-center me-3">
                        <div class="theme-toggle d-flex align-items-center" style="height: 2rem;">
                            <div class="form-check form-switch fs-6 mb-0 d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="toggle-dark"
                                    style="cursor: pointer; margin: 0;" />
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown me-2 me-lg-3">
                        <a class="nav-link text-gray-600 position-relative" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false" id="notifDropdown">

                            <i class="bi bi-bell bi-sub fs-4"></i>

                            <?php
                                $unreadCount = auth()->user()->unreadNotifications->count();
                            ?>

                            <?php if($unreadCount > 0): ?>
                                <span class="badge badge-notification bg-danger" id="notif-count">
                                    <?php echo e($unreadCount); ?>

                                </span>
                            <?php endif; ?>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown shadow-lg"
                            aria-labelledby="notifDropdown">

                            <li class="dropdown-header d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                <h6 class="mb-0 fw-bold">Notifikasi</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if($unreadCount > 0): ?>
                                        <span class="badge bg-danger rounded-pill"><?php echo e($unreadCount); ?> baru</span>
                                        <a href="javascript:void(0)" class="small text-decoration-none mark-all-read">Tandai dibaca</a>
                                    <?php endif; ?>
                                </div>
                            </li>

                            <div class="notification-list">
                            <?php
                                $notifications = auth()->user()->notifications()->latest()->take(5)->get();
                            ?>

                            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    // Path relatif diubah ke URL penuh; URL absolut lama
                                    // (mis. localhost) disesuaikan ke host yang sedang diakses.
                                    $href = $notification->data['path'] ?? null;
                                    $href = $href ? url($href) : ($notification->data['url'] ?? 'javascript:void(0)');
                                    if (!str_starts_with($href, 'javascript')) {
                                        $parts = parse_url($href);
                                        if (!empty($parts['host']) && $parts['host'] !== request()->getHost()) {
                                            $href = request()->root()
                                                . ($parts['path'] ?? '/')
                                                . (!empty($parts['query']) ? '?' . $parts['query'] : '');
                                        }
                                    }
                                ?>
                                <li
                                    class="notification-item <?php echo e($notification->read_at ? '' : 'unread'); ?>">

                                    <a href="<?php echo e($href); ?>"
                                        class="mark-as-read d-flex align-items-start gap-2 text-decoration-none p-2 px-3"
                                        data-id="<?php echo e($notification->id); ?>">

                                        <span class="notif-dot <?php echo e($notification->read_at ? 'read' : ''); ?>" aria-hidden="true"></span>

                                        <div class="notification-text flex-grow-1 min-w-0">

                                            <p class="notification-title fw-bold mb-0 text-dark">
                                                <?php echo e($notification->data['title'] ?? 'Notifikasi'); ?>

                                            </p>

                                            <p class="notification-subtitle text-muted small mb-1">
                                                <?php echo e($notification->data['message'] ?? '-'); ?>

                                            </p>

                                            <small class="text-muted d-flex align-items-center gap-1">
                                                <i class="bi bi-clock" style="font-size:10px;"></i> <?php echo e($notification->created_at->diffForHumans()); ?>

                                            </small>

                                        </div>

                                    </a>
                                </li>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li class="empty-notif">
                                    <div class="empty-notif-icon"><i class="bi bi-bell-slash"></i></div>
                                    <p class="mb-1 fw-semibold small text-muted">Tidak ada notifikasi</p>
                                    <small class="text-muted" style="font-size:11px;">Belum ada aktivitas terbaru</small>
                                </li>
                            <?php endif; ?>
                            </div>

                            <a href="<?php echo e(route('notifications.index')); ?>" class="dropdown-footer d-block text-center text-decoration-none small fw-bold py-2 border-top">
                                Lihat semua notifikasi <i class="bi bi-arrow-right ms-1"></i>
                            </a>

                        </ul>
                    </li>

                </ul>
                <div class="dropdown user-dropdown">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false" id="userDropdown" class="d-flex align-items-center text-decoration-none">
                        <div class="user-menu d-flex">
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="<?php echo e(Auth::user()->avatar
                                        ? (Str::startsWith(Auth::user()->avatar, 'http')
                                            ? Auth::user()->avatar
                                            : asset('storage/' . Auth::user()->avatar))
                                        : asset('dist/assets/images/avatar.jpg')); ?>"
                                        alt="<?php echo e(auth()->user()->name); ?>" class="img-thumbnail rounded-circle">
                                </div>
                            </div>
                            <div class="user-name text-start">
                                <h6 class="mb-0 text-gray-600"><?php echo e(Auth::user()->name ?? ''); ?></h6>
                                <p class="mb-0 text-sm text-gray-600">
                                    <?php echo e(Auth::user()->getRoleNames()->first() ?? ''); ?>

                                </p>
                            </div>

                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu shadow-lg" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>"><i
                                    class="icon-mid bi bi-person me-2"></i> My Profile</a>
                        </li>

                        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'super-admin|admin')): ?>
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('account.index')); ?>">
                                    <i class="icon-mid bi bi-gear me-2"></i> Settings
                                </a>
                            </li>
                        <?php else: ?>
                        <?php endif; ?>

                        <hr class="dropdown-divider">

                        <li>
                            <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="icon-mid bi bi-box-arrow-left me-2"></i>Logout</a>
                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST"
                                style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views/components/web/header.blade.php ENDPATH**/ ?>