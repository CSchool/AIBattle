@section('adminPanelTournaments')
    <li class="list-group-item noBorder">
        <a href="{{ url('adminPanel/tournaments') }}" class="tree-toggle">{{ trans('adminPanel/main.generalLink') }}</a>
    </li>
    @if (count($globalCurrentTournaments) > 0)
        @include('assets.adminPanel.extendedSidebarTournaments', [
            'text' => trans('adminPanel/tournaments.sidebarRunning'),
            'tournaments' => $globalCurrentTournaments,
            'style' => 'btn-success',
        ])
    @endif

    @if (count($globalPreparingTournaments) > 0)
        @include('assets.adminPanel.extendedSidebarTournaments', [
            'text' => trans('adminPanel/tournaments.sidebarPreparing'),
            'tournaments' => $globalPreparingTournaments,
            'style' => 'btn-info',
        ])
    @endif

    @if (count($globalClosedTournaments) > 0)
        @include('assets.adminPanel.extendedSidebarTournaments', [
            'text' => trans('adminPanel/tournaments.sidebarClosed'),
            'tournaments' => $globalClosedTournaments,
            'style' => 'btn-danger',
        ])
    @endif
@endsection