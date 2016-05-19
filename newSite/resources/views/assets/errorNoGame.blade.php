<div class="alert alert-danger text-center">
    <div class="row"><p><strong>{{ trans('shared.NoGamesMessage') }}</strong></p></div>

    <div class="row">
        <a href="{{ url('/adminPanel/games/create') }}" class="btn btn-success btn-lg" role="button">
            {{ trans('shared.NoGamesCreateGame') }}
        </a>
    </div>
</div>