<div id="modal-form-view-faq-{{ $item->uuid }}" class="modal fade modal-form-inovasi-faq-view" tabindex="-1"
    aria-labelledby="modal-form-view-faq-{{ $item->uuid }}-label" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title" id="modal-form-view-faq-{{ $item->uuid }}-label">Detail FAQ & Answer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>

            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="pertanyaan" class="mb-2">Pertanyaan :</label><br>
                    <span 
                        style="display: block; white-space: normal; word-break: break-word; overflow-wrap: break-word; max-width: 100%; color: #007bff;">
                        {{ $item->pertanyaan ?? '-' }}
                    </span>
                </div>

                  <div class="form-group mb-3">
                    <label for="jawaban" class="mb-2">Jawaban :</label><br>
                    <span
                        style="display: block; white-space: normal; word-break: break-word; overflow-wrap: break-word; max-width: 100%; color: #007bff;">
                        {{ $item->jawaban ?? '-' }}
                    </span>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Keluar</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
