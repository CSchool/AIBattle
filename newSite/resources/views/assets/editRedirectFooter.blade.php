<div class="row">
    <!--
    <div class="col-md-3">
        <a href="{{ $backLink }}" class="btn btn-primary btn-lg">
            {{ trans('shared.back') }}
        </a>
    </div>
    -->
    @if (!isset($specialMode))
        <div class="col-md-3 col-md-offset-6">
            <a href="{{ $editLink }}" class="btn btn-success btn-lg btn-block">
                {{ trans('shared.edit') . ' ' . $editName }}
            </a>
        </div>
    @elseif ($specialMode == "game")
        @if (class_exists('ZipArchive'))
            <div class="col-md-3">
                <a href="{{ $archiveLink }}" class="btn btn-info btn-lg btn-block">
                    {{ trans('shared.downloadGameArchive') }}
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ $checkerRoute }}" class="btn btn-warning btn-lg btn-block">
                    {{ trans('shared.edit') . "\r\n" . mb_strtolower(trans('shared.checkers')) }}
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ $attachmentRoute }}" class="btn btn-warning btn-lg btn-block">
                    {{ trans('shared.edit') . PHP_EOL . mb_strtolower(trans('shared.attachments')) }}
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ $editLink }}" class="btn btn-success btn-lg btn-block">
                    {{ trans('shared.edit') . ' ' . $editName }}
                </a>
            </div>
        @else
            <div class="col-md-4">
                <a href="{{ $checkerRoute }}" class="btn btn-warning btn-lg btn-block">
                    {{ trans('shared.edit') . ' ' . mb_strtolower(trans('shared.checkers')) }}
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ $attachmentRoute }}" class="btn btn-warning btn-lg btn-block">
                    {{ trans('shared.edit') . ' ' . mb_strtolower(trans('shared.attachments')) }}
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ $editLink }}" class="btn btn-success btn-lg btn-block">
                    {{ trans('shared.edit') . ' ' . $editName }}
                </a>
            </div>
        @endif
    @elseif ($specialMode == "tournament")
        <div class="col-md-4">
            <a href="{{ $roundsLink }}" class="btn btn-info btn-lg btn-block">
                {{ trans('shared.rounds') }}
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ $strategiesLink }}" class="btn btn-warning btn-lg btn-block">
                {{ trans('shared.strategies') }}
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ $editLink }}" class="btn btn-success btn-lg btn-block">
                {{ trans('shared.edit') . ' ' . $editName }}
            </a>
        </div>
    @elseif ($specialMode == "rounds")
        <div class="col-md-3 col-md-offset-6">
            <button type="submit" name="updateState" value="updateState" class="btn btn-lg btn-success btn-block">
                @if ($isVisible == 0)
                    {{ trans('adminPanel/rounds.showRoundMakeRoundVisible') }}
                @else
                    {{ trans('adminPanel/rounds.showRoundMakeRoundInvisible') }}
                @endif
            </button>
        </div>
        <div class="col-md-3">
            <a href="{{ $resultsLink }}" class="btn btn-info btn-lg btn-block">
                {{ trans('adminPanel/rounds.roundsResults') }}
            </a>
        </div>
    @endif
</div>