@extends('players.layout')

@section('content')
    <section class="cad">
        <div class="row">
            <div class="col-12">
                &nbsp;
            </div>
            <div class="col-12 d-flex justify-content-center">
                <h4>Sort teams</h4>
            </div>
            <div class="col-12">
                <a class="btn btn-success" href="{{ route('players.create') }}"> Create New Player</a>
                <a class="btn btn-success" href="{{ route('teams') }}"> Sort teams</a>
                <a class="btn btn-primary" href="{{ route('players.index') }}"> Back to Home</a>
            </div>
        </div>
        @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
        @endif
        @if($resp->error == true)
            <div class="col-12">
                {{ $resp->msg }}
            </div>
        @else
            <div class="row">
                <h4>Statistics</h4>
                <div class="col-3">
                    <h5>Confirmed players: {{ $resp->confirmed }}</h5>
                </div>
                <div class="col-3">
                    <h5>Number of teams: {{ $resp->teams }}</h5>
                </div>
                <div class="col-3">
                    <h5>Players per team: {{ $resp->numbP }}</h5>
                </div>
                @foreach($resp->data as $key => $team)
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Team {{ $key + 1 }}</h5>
                                @if(isset($team['players'][0]) && is_object($team['players'][0]))
                                    @if($team['players'][0]->isGoalkeeper == 1)
                                        <p class="card-text text-success font-weight-bold">Goalkeeper: {{ $team['players'][0]->name }}</p>
                                    @endif
                                @endif
                                <table>
                                    <tr>
                                        <td width="10%"><b>Player</b></td>
                                        <td width="50%"><b>Name</b></td>
                                        <td width="10%"><b>Level</b></td>
                                    </tr>
                                    @foreach($team['players'] as $p => $player)
                                        @if(is_object($player))
                                            <tr @if($player->isGoalkeeper == 1)class="text-success font-weight-bold" @endif>
                                                <td>{{ $p + 1 }}</td>
                                                <td>{{ $player->name }}</td>
                                                <td>{{ $player->level }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
