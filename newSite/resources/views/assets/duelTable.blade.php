<div class="table-responsive">
    <table id="{{ $tableId }}" class="table table-hover nowrap" width="100%">
        <thead>
            <tr class="default">
                <td>#</td>
                <td>{{ trans('tournaments/strategies.trainingDuelPlayer', ['number' => 1]) }}</td>
                <td>{{ trans('tournaments/strategies.trainingDuelPlayer', ['number' => 2]) }}</td>
                <td>{{ trans('shared.result') }}</td>
                @if ($hasVisualizer)
                    <td class="last">{{ trans('adminPanel/games.gameVisualizer') }}</td>
                @endif
            </tr>
        </thead>

        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                @if ($hasVisualizer)
                    <td></td>
                @endif
            </tr>
        </tfoot>
    </table>
</div>