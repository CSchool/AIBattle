<div class="row">
    <div class="col-md-3">
        <a href="{{ $link }}" class="btn btn-primary btn-lg">Back</a>
    </div>

    @if ($mode == "create")
        <div class="col-md-3 col-md-offset-6">
            <button type="submit" name="create" class="btn btn-lg btn-success btn-block">Create {{ $name }}</button>
        </div>
    @elseif ($mode == "edit")
        <div class="col-md-3 col-md-offset-3">
            <button type="submit" name="update" value="update" class="btn btn-lg btn-success btn-block">Update {{ $name }}</button>
        </div>
        <div class="col-md-3">
            <button type="submit" name="delete" value="delete" class="btn btn-lg btn-danger btn-block">Delete {{ $name }}</button>
        </div>
    @endif
</div>