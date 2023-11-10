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
                <a class="btn btn-success" href="{{ route('players.create') }}"> Create New Player</a>    <a class="btn btn-success" href="{{ route('teams') }}"> Sort teams</a>
            </div>
            <form action="{{ route('sort') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12">
                        &nbsp;
                    </div>
                    <div class="col-12">
                        &nbsp;
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="teamsN" name="teamsN" placeholder="Number of teams">
                            <label for="teamsN">Number of teams</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="numbP" name="numbP" placeholder="Players per team">
                            <label for="numbP">Players per team</label>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success">Sort teams</button>
            </form>
        </div>
    </section>
@endsection
