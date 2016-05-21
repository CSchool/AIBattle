<div class="row">
    <div class="col-md-3">
        <a href="{{ $backLink }}" class="btn btn-primary btn-lg">
            {{ trans('shared.back') }}
        </a>
    </div>

    @if (!isset($specialMode))
        <div class="col-md-3 col-md-offset-6">
            <a href="{{ $editLink }}" class="btn btn-success btn-lg btn-block">
                {{ trans('shared.edit') . ' ' . $editName }}
            </a>
        </div>
    @elseif ($specialMode == "attachment")
        <div class="col-md-3">
            <a href="{{ $archiveLink }}" class="btn btn-info btn-lg btn-block">
                {{ trans('shared.downloadGameArchive') }}
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ $attachmentRoute }}" class="btn btn-warning btn-lg btn-block">
                {{ trans('shared.edit') . ' ' . mb_strtolower(trans('shared.attachments')) }}
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ $editLink }}" class="btn btn-success btn-lg btn-block">
                {{ trans('shared.edit') . ' ' . $editName }}
            </a>
        </div>
    @endif
</div>