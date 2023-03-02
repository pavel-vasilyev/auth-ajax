<!-- Modal -->
<div class="modal fade{{$modalClass ?? null}}" id="ajaxModal" tabindex="-1" aria-labelledby="ajaxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajaxModalLabel">{{ $modalTitle ?? null }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">{!! $modalBody ?? null !!}</div>
        </div>
    </div>
</div>
