<div class="row">
    <div class="col-md-3">
        <a href="{{ $backLink }}" class="btn btn-primary btn-lg">Back</a>
    </div>

    @if (!isset($specialMode))
        <div class="col-md-2 col-md-offset-7">
            <a href="{{ $editLink }}" class="btn btn-success btn-lg btn-block">Edit {{ $editName }}</a>
        </div>
    @elseif ($specialMode == "attachment")
        <div class="col-md-3 col-md-offset-3">
            <a href="{{ $attachmentRoute }}" class="btn btn-warning btn-lg btn-block">Show attachments</a>
        </div>
        <div class="col-md-3">
            <a href="{{ $editLink }}" class="btn btn-success btn-lg btn-block">Edit {{ $editName }}</a>
        </div>
    @endif
</div>