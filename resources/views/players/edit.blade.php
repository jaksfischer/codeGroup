@extends('players.layout')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New Player</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('players.index') }}"> Back</a>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input. Verify and try again!<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
    @endif
        <form action="{{ route('players.update', $player->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-12">
                    &nbsp;
                </div>
                <div class="col-12">
                    &nbsp;
                </div>
                <div class="col-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Player's name" maxlength="255" value="{{ $player->name }}">
                        <label for="name">Player's name</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-floating mb-3">
                        <select id="level" name="level" class="form-select form-select-lg mb-3" aria-label="Select the player level">
                            <option value="1" @if ($player->level == 1) selected @endif>Level 1 - Too bad</option>
                            <option value="2" @if ($player->level == 2) selected @endif>Level 2 - Bad</option>
                            <option value="3" @if ($player->level == 3) selected @endif>Level 3 - Normal</option>
                            <option value="4" @if ($player->level == 4) selected @endif>Level 4 - Average</option>
                            <option value="5" @if ($player->level == 5) selected @endif>Level 5 - Very Good</option>
                        </select>
                        <label for="level">Select the player level</label>
                    </div>
                </div>
                <div class="col-4 d-flex align-items-center">
                </div>
                <div class="col-4 d-flex align-items-center">
                    <b>Is Goalkeeper?</b> &nbsp;&nbsp;&nbsp;
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="isGoalkeeper" id="isGoalkeeper1" value="1" @if($player->isGoalkeeper == 1) checked @endif>
                        <label class="form-check-label" for="isGoalkeeper1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="isGoalkeeper" id="isGoalkeeper2" value="0" @if($player->isGoalkeeper == 0) checked @endif>
                        <label class="form-check-label" for="isGoalkeeper2">No</label>
                    </div>
                </div>
                <div class="col-4 d-flex align-items-center">
                    <b>Confirm presence?</b> &nbsp;&nbsp;&nbsp;
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="presence" id="presence1" value="1" @if($player->presence == 1) checked @endif>
                        <label class="form-check-label" for="presence1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="presence" id="presence2" value="0" @if($player->presence == 0) checked @endif>
                        <label class="form-check-label" for="presence2">No</label>
                    </div>
                </div>
            </div>
            <button class="btn btn-success">Save player</button>
        </form>
@endsection
