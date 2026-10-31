<!-- Modal Update Sidebar -->
<div class="modal fade" id="modalUpdateSidebar-{{ $item->uuid }}" tabindex="-1"
    aria-labelledby="modalLabelSidebar-{{ $item->uuid }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('pages.updateSidebar', $item->uuid) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelSidebar-{{ $item->uuid }}">Show in Sidebar?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-wrap text-break">
                    <p>
                        Are you sure you want to change the <em>sidebar</em> status for the page <strong>{{ $item->title }}</strong> ?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Yes, Change</button>
                </div>
            </div>
        </form>
    </div>
</div>