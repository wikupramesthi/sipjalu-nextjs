<link rel="stylesheet" href="<?php echo e(asset('dist/assets/extensions/toastify-js/src/toastify.css')); ?>">
<script src="<?php echo e(asset('dist/assets/extensions/toastify-js/src/toastify.js')); ?>"></script>

<?php if($errors->any()): ?>
  <!-- Toast with Placements -->
  <script>
    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      Toastify({
        text: "<?php echo e($error); ?>", // Display each error message
        duration: 4000, // Duration of the toast
        close: true, // Option to close the toast manually
        gravity: "top", // Toast appears at the top
        position: "right", // Align toast to the right
        style: { background: '#dc3545' }, // Error color (Bootstrap danger)
      }).showToast();
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </script>
<?php endif; ?>

<?php if(session()->has('success')): ?>
  <script>
    Toastify({
      text: "<?php echo e(session('success')); ?>", // Display success message from session
      duration: 4000, // Duration of the toast
      close: true, // Option to close the toast manually
      gravity: "top", // Toast appears at the top
      position: "right", // Align toast to the right
        style: { background: '#28a745' }, // Success color
    }).showToast();
  </script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views/components/validation-errors.blade.php ENDPATH**/ ?>