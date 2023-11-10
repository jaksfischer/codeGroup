<?php

namespace App\Http\Controllers;
use App\Models\Players;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $players = Players::latest()->paginate(30);
        return view('players.index',compact('players'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('players.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'          =>  'required',
            'level'         =>  'required',
            'isGoalkeeper'  => 'required'
        ]);
        Players::create($request->all());
        return  redirect()->route('players.index')
            ->with('success', 'Player created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Players $player): View
    {
        return view('players.edit',compact('player'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Players $player): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'level' => 'required',
            'isGoalkeeper' => 'required',
            'presence' => 'required'
        ]);
        $player->update($request->all());
        return redirect()->route('players.index')
            ->with('success','Player updated successfully');
    }

    /**
     * Remove the specified resource from storageRemove the specified resource from storage.
     */
    public function destroy(Players $player): RedirectResponse
    {
        $player->delete();
        return redirect()->route('players.index')
            ->with('success','Player deleted successfully');
    }

    /**
     * Shows the resource to define teams and number of players
     */
    public function teams(): View
    {
        return view('players.teams');
    }

    /**
     * Makes the draw, applies the rules and returns the randomly drawn teams, balancing the teams
     */
/*    public function sort(Request $request): View
    {
        $request->validate([
            'teamsN' => 'required|integer|min:2',
            'numbP' => 'required|integer|min:1'
        ]);

        $confirmedPlayers = Players::where('presence', 1)
            ->orderBy('level', 'asc')
            ->get();

        $totalConfirmedPlayers = count($confirmedPlayers);

        $requiredPlayers = $request->numbP * $request->teamsN;

        //Number of teams defined
        $teams = (int)$request->teamsN;

        //Number of players per team
        $numbP = (int)$request->numbP;

        if ($totalConfirmedPlayers < $requiredPlayers || $requiredPlayers < $request->numbP * 2) {
            $resp = new \stdClass();
            $resp->error = true;
            $resp->msg = "Sorry, it's not possible to form teams with the current number of players.";
            $resp->teams = $teams;
            $resp->numbP = $numbP;
            $resp->data = $totalConfirmedPlayers;
            return view('players.sort', compact('resp'));
        }

        // Separate goalkeepers and non-goalkeepers
        $goalkeepers = $confirmedPlayers->where('isGoalkeeper', 1)->shuffle();
        $nonGoalkeepers = $confirmedPlayers->where('isGoalkeeper', 0)->shuffle();

        // Initialize teams array
        $teamsArray = [];

        // Form the first two teams with the specified number of players, including the goalkeeper
        for ($i = 0; $i < 2; $i++) {
            $team = [];

            // Add a goalkeeper if available
            $team['goalkeeper'] = $goalkeepers->shift();

            // Add non-goalkeeper players until reaching the specified number of players
            $team['players'] = $nonGoalkeepers->splice(0, $numbP - 1)->all();

            // Ensure that the goalkeeper is always included in the player count
            array_unshift($team['players'], $team['goalkeeper']);

            $teamsArray[] = $team;
        }

        // Form the remaining teams, each with the specified number of players, including the goalkeeper
        for ($i = 2; $i < $teams; $i++) {
            $team = [];

            // Add a goalkeeper if available
            $team['goalkeeper'] = $goalkeepers->shift();

            // Add non-goalkeeper players until reaching the specified number of players
            $team['players'] = $nonGoalkeepers->splice(0, $numbP)->all();

            // Ensure that the goalkeeper is always included in the player count
            array_unshift($team['players'], $team['goalkeeper']);

            $teamsArray[] = $team;
        }

        $resp = new \stdClass();
        $resp->error = false;
        $resp->msg = '';
        $resp->teams = $teams;
        $resp->numbP = $numbP;
        $resp->data = $teamsArray;

        return view('players.sort', compact('resp'));
    }*/
    public function sort(Request $request): View
    {
        $request->validate([
            'teamsN' => 'required|integer|min:2',
            'numbP' => 'required|integer|min:1'
        ]);

        $confirmedPlayers = Players::where('presence', 1)
            ->orderBy('level', 'asc')
            ->get();

        $totalConfirmedPlayers = count($confirmedPlayers);

        $requiredPlayers = $request->numbP * 2;

        // Number of teams defined
        $teams = (int)$request->teamsN;

        // Number of players per team
        $numbP = (int)$request->numbP;

        if ($totalConfirmedPlayers < $requiredPlayers || $requiredPlayers < $request->numbP * 2) {
            $resp = new \stdClass();
            $resp->error = true;
            $resp->msg = "Sorry, it's not possible to form teams with the current number of players.";
            $resp->teams = $teams;
            $resp->numbP = $numbP;
            $resp->confirmed = $totalConfirmedPlayers;
            return view('players.sort', compact('resp'));
        }

        // Separate goalkeepers and non-goalkeepers
        $goalkeepers = $confirmedPlayers->where('isGoalkeeper', 1)->shuffle();
        $nonGoalkeepers = $confirmedPlayers->where('isGoalkeeper', 0)->shuffle();

        // Initialize teams array
        $teamsArray = [];

        // Form the first two teams with the specified number of players, including the goalkeeper
        for ($i = 0; $i < 2; $i++) {
            $team = [];

            // Add a goalkeeper if available
            $team['goalkeeper'] = $goalkeepers->shift();

            // Add non-goalkeeper players until reaching the specified number of players
            $team['players'] = $nonGoalkeepers->splice(0, $numbP - 1)->all();

            // Ensure that the goalkeeper is always included in the player count
            array_unshift($team['players'], $team['goalkeeper']);

            // Ensure that the team has exactly the specified number of players
            while (count($team['players']) < $numbP) {
                $remainingPlayer = array_shift($nonGoalkeepers->all());
                if ($remainingPlayer) {
                    $team['players'][] = $remainingPlayer;
                } else {
                    break;
                }
            }

            $teamsArray[] = $team;
        }

        // Form the remaining teams, each with the specified number of players, including the goalkeeper
        for ($i = 2; $i < $teams; $i++) {
            $team = [];

            // Add a goalkeeper if available
            $team['goalkeeper'] = $goalkeepers->shift();

            // Add non-goalkeeper players until reaching the specified number of players
            $team['players'] = $nonGoalkeepers->splice(0, $numbP - 1)->all();

            // Ensure that the goalkeeper is always included in the player count
            array_unshift($team['players'], $team['goalkeeper']);

            // Ensure that the team has at most the specified number of players
            while (count($team['players']) > $numbP) {
                array_pop($team['players']);
            }

            $teamsArray[] = $team;
        }

        // Assign any remaining players to the last team
        $lastTeam = end($teamsArray);
        while (!empty($nonGoalkeepers->all())) {
            $remainingPlayer = array_shift($nonGoalkeepers->all());
            if ($remainingPlayer) {
                $lastTeam['players'][] = $remainingPlayer;
            } else {
                break;
            }
        }

        $resp = new \stdClass();
        $resp->error = false;
        $resp->msg = '';
        $resp->teams = $teams;
        $resp->numbP = $numbP;
        $resp->confirmed = $totalConfirmedPlayers;
        $resp->data = $teamsArray;

        return view('players.sort', compact('resp'));
    }
}
