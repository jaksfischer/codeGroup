@extends('players.layout')
@section('content')
    <section class="cad">
        <div class="row">
            <div class="col-12">
                &nbsp;
            </div>
            <div class="col-12 d-flex justify-content-center">
                <h4>Players details</h4>
            </div>
            <div class="col-12">
                <a class="btn btn-success" href="{{ route('players.create') }}"> Create New Player</a> <a class="btn btn-success" href="{{ route('teams') }}"> Sort teams</a>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @if (count($players) < 1)
            No data found!
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Level</th>
                        <th scope="col">Goalkeeper</th>
                        <th scope="col">Presence</th>
                        <th scope="col" width="280px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($players as $player)
                    <tr>
                        <td scope="row">{{ ++$i }}</td>
                        <td>{{ $player->name }}</td>
                        <td>{{ $player->level }}</td>
                        @if($player->isGoalkeeper == 0)
                            <td>No</td>
                        @else
                            <td>Yes</td>
                        @endif
                        @if($player->presence == 0)
                            <td>No</td>
                        @else
                            <td>Yes</td>
                        @endif
                        <td>
                            <form action="{{ route('players.destroy',$player->id) }}" method="POST">
                                <a class="btn btn-primary" href="{{ route('players.edit',$player->id) }}">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td>{!! $players->links() !!}</td>
                </tr>
            </table>
        @endif
    </section>
@endsection
