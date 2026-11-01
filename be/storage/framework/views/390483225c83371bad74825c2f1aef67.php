<div class="modal fade" id="modalFotoPJ" tabindex="-1" aria-labelledby="modalFotoPJLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lihat Foto KTP Penanggung Jawab/PIC</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body text-center">
        <?php if($user->foto_pj && file_exists(public_path('storage/' . $user->foto_pj))): ?>
          <img src="<?php echo e(asset('storage/' . $user->foto_pj)); ?>" class="img-fluid rounded" alt="Foto PJ">
        <?php else: ?>
          <p class="text-danger">Foto KTP penanggung jawab/PIC belum diupload.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalSuratPernyataan" tabindex="-1" aria-labelledby="modalSuratPernyataanLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lihat Surat Pernyataan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <?php if($user->surat_pernyataan && file_exists(public_path('storage/' . $user->surat_pernyataan))): ?>
          <iframe src="<?php echo e(asset('storage/' . $user->surat_pernyataan)); ?>" width="100%" height="600px" frameborder="0"></iframe>
        <?php else: ?>
          <p class="text-danger">File surat pernyataan belum diunggah.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalProfilKomunitas" tabindex="-1" aria-labelledby="modalProfilKomunitasLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lihat Profil Komunitas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <?php if($user->profil_komunitas && file_exists(public_path('storage/' . $user->profil_komunitas))): ?>
          <iframe src="<?php echo e(asset('storage/' . $user->profil_komunitas)); ?>" width="100%" height="600px" frameborder="0"></iframe>
        <?php else: ?>
           <p class="text-danger">File profil komunitas belum diunggah.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php /**PATH C:\xampp\htdocs\sipjalu\be\resources\views\profile\modals-file-preview.blade.php ENDPATH**/ ?>