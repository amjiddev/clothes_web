<div class="card mt-4 offer-item" data-index="{{ $index }}">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Offer</strong>
        <button type="button" class="btn btn-sm btn-danger remove-offer">
            <i class="fa fa-trash"></i>
        </button>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Offer Title</label>
            <input type="text"
                   name="offers[{{ $index }}][title]"
                   class="form-control"
                   value="{{ $offer['title'] ?? '' }}">
        </div>

        <div class="bullets-wrapper">
            @foreach($offer['bullets'] ?? [] as $bIndex => $bullet)
                <div class="row g-2 align-items-center bullet-item mb-2">
                    <div class="col-md-5">
                        <input type="text"
                               name="offers[{{ $index }}][bullets][{{ $bIndex }}][text]"
                               class="form-control"
                               value="{{ $bullet['text'] ?? '' }}"
                               placeholder="Bullet text">
                    </div>
                    <div class="col-md-5">
                        <input type="url"
                               name="offers[{{ $index }}][bullets][{{ $bIndex }}][url]"
                               class="form-control"
                               value="{{ $bullet['url'] ?? '' }}"
                               placeholder="URL">
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-bullet">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-sm btn-secondary add-bullet mt-2">
            + Add Bullet
        </button>
    </div>
</div>
