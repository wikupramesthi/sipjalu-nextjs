<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-wrapper active">

        
        <div class="sidenav-header">
            <a href="<?php echo e(route('dashboard.index')); ?>" class="navbar-brand m-0 d-flex align-items-center">
                <img id="logo-light" src="<?php echo e(asset('img/logo.png')); ?>" alt="Logo" class="navbar-brand-img">
                <img id="logo-dark" src="<?php echo e(asset('img/logo-white.png')); ?>" alt="Logo" class="navbar-brand-img d-none">
            </a>

            <div class="sidebar-toggler x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x-lg"></i></a>
            </div>
        </div>

        <hr class="horizontal dark mt-0">

        
        <div class="sidebar-menu">
            <ul class="menu navbar-nav">

                <li class="sidebar-title">Main Menu</li>

                <li class="sidebar-item <?php echo e(request()->routeIs('dashboard.index') ? 'active' : ''); ?>">
                    <a class="sidebar-link nav-link" href="<?php echo e(route('dashboard.index')); ?>">
                        <div class="icon icon-shape icon-sm">
                            <i class="bx bx-home"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>

                <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($menu->permission_name)): ?>
                <?php
                // Determine if any of the submenu items are active
                $isActive = false;
                foreach ($menu->items as $item) {
                if (request()->routeIs($item->route)) {
                $isActive = true;
                break;
                }
                }
                ?>

                <li class="sidebar-item has-sub <?php echo e($isActive ? 'active' : ''); ?>">
                    <a href="#" class="sidebar-link nav-link">
                        <div class="icon icon-shape icon-sm">
                            <i class="bx <?php echo e($menu->icon); ?>"></i>
                        </div>
                        <span class="nav-link-text ms-1"><?php echo e($menu->name); ?></span>
                    </a>

                    <ul class="submenu nav ms-4">
                        <?php $__currentLoopData = $menu->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($item->permission_name)): ?>
                        <li class="submenu-item <?php echo e(request()->routeIs($item->route) ? 'active' : ''); ?>">
                            <a href="<?php echo e(route($item->route)); ?>" class="submenu-link nav-link">
                                <span class="sidenav-mini-icon"><?php echo e(strtoupper(mb_substr($item->name, 0, 1))); ?></span>
                                <span class="sidenav-normal"><?php echo e($item->name); ?></span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </li>

                <!-- end foreach items -->
                <?php endif; ?>
                <!-- end can menu -->
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if(Auth::check()): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                <li class="sidebar-item">
                    <a class="sidebar-link nav-link" href="#" target="_blank" title="User Guide">
                        <div class="icon icon-shape icon-sm">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <span class="nav-link-text ms-1">Panduan</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'user')): ?>
                <li class="sidebar-title">Others</li>
                <li class="sidebar-item has-sub">
                    <a href="#" class="sidebar-link nav-link">
                        <div class="icon icon-shape icon-sm">
                            <i class="bx bx-cog"></i>
                        </div>
                        <span class="nav-link-text ms-1">Settings</span>
                    </a>

                    <ul class="submenu nav ms-4">
                        <li class="submenu-item">
                            <a href="<?php echo e(route('profile.edit')); ?>" class="submenu-link nav-link">
                                <span class="sidenav-mini-icon">M</span>
                                <span class="sidenav-normal">My Profile</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <li class="sidebar-item">
                    <a class="sidebar-link nav-link" href="<?php echo e(route('logout')); ?>"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <div class="icon icon-shape icon-sm">
                            <i class="bi bi-arrow-bar-left"></i>
                        </div>
                        <span class="nav-link-text ms-1">Logout</span>
                    </a>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                </li>
                <?php endif; ?>

            </ul>
        </div>

        
        <div class="sidenav-footer">
            <div class="card card-plain shadow-none border-0 text-center mb-2">
                <div class="card-body p-3">
                    <div class="docs-info">
                        <h6 class="mb-0 fw-bold">Butuh Bantuan?</h6>
                        <p class="text-xs mb-0 text-muted">Ada pertanyaan atau kendala? Hubungi kami melalui WhatsApp.</p>
                    </div>
                </div>
            </div>
            <a href="https://wa.me/6285691231100"
                target="_blank" rel="noopener" class="btn btn-success btn-sm w-100">
                <i class="bi bi-whatsapp me-1"></i> Chat via WhatsApp
            </a>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const search = document.getElementById('sidebar-search');
        if (!search) return;

        search.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();

            document.querySelectorAll('.sidebar-menu .menu > .sidebar-item').forEach(function(item) {
                const text = item.textContent.toLowerCase();
                const matches = text.includes(query);
                item.classList.toggle('sb-hidden', !matches);

                const submenu = item.querySelector('.submenu');
                if (!submenu) return;

                if (matches && query) {
                    submenu.classList.add('submenu-open');
                    submenu.classList.remove('submenu-closed');
                } else if (!query) {
                    submenu.classList.remove('submenu-open');
                    submenu.classList.remove('submenu-closed');
                }
            });
        });
    });
</script>
<?php /**PATH C:\xampp\htdocs\agenda\be\resources\views/components/web/sidebar.blade.php ENDPATH**/ ?>