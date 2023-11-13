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
    public function sort(Request $request): View
    {
        $request->validate([
            'teamsN' => 'required|integer|min:2',
            'numbP' => 'required|integer|min:1'
        ]);

        // Retrieve confirmed players, ordered by level
        $confirmedPlayers = Players::where('presence', 1)
            ->orderBy('level', 'asc')
            ->get();

        // Count the total number of confirmed players
        $totalConfirmedPlayers = count($confirmedPlayers);
        // Calculate the total number of players required
        $requiredPlayers = $request->numbP * 2;

        // Convert parameters to integers
        $teams = (int)$request->teamsN;
        $numbP = (int)$request->numbP;

        // Check if there are enough players for the draw
        if ($totalConfirmedPlayers < $requiredPlayers) {
            // Return a view with an error message
            return view('players.sort')->with('resp', (object)[
                'error' => true,
                'msg' => "There are not enough confirmed players for the requested number of teams and players per team.",
                'teams' => $teams,
                'numbP' => $numbP,
                'confirmed' => $totalConfirmedPlayers,
            ]);
        }

        // Check if there are at least 2 goalkeepers with confirmed presence
        $goalkeepersCount = $confirmedPlayers->where('isGoalkeeper', 1)->count();
        if ($goalkeepersCount < 2) {
            // Return a view with an error message
            return view('players.sort')->with('resp', (object)[
                'error' => true,
                'msg' => "At least 2 goalkeepers with confirmed presence are required.",
                'teams' => $teams,
                'numbP' => $numbP,
                'confirmed' => $totalConfirmedPlayers,
            ]);
        }

        // Shuffle goalkeepers and non-goalkeepers
        $goalkeepers = $confirmedPlayers->where('isGoalkeeper', 1)->shuffle();
        $nonGoalkeepers = $confirmedPlayers->where('isGoalkeeper', 0)->shuffle();

        // Initialize the array of teams
        $teamsArray = [];

        // Initialize the array of all players in the same list
        $allPlayersList = [];

        // Loop to create teams
        for ($i = 0; $i < $teams; $i++) {
            $team = [
                'id' => $i + 1,
                'playersCount' => 0,
                'players' => [],
            ];

            // Add a goalkeeper if available and not already added to a team
            $goalkeeper = $goalkeepers->where('team_id', null)->shift();

            // If there is no goalkeeper available, add a non-goalkeeper player
            if (empty($goalkeeper)) {
                $nonGoalkeeper = $nonGoalkeepers->shift();
                $team['players'][] = $nonGoalkeeper;
            } else {
                // If there is a goalkeeper available, add it to the team
                $team['players'][] = $goalkeeper;
                $goalkeeper->update(['team_id' => $team['id']]); // Update the team_id of the goalkeeper
                $team['playersCount'] += 1;
            }

            // Add non-goalkeeper players until the desired number is reached
            $remainingNonGoalkeepers = $nonGoalkeepers->splice(0, $numbP - 1)->all();

            // Ensure the team has at most the desired number of players (including the goalkeeper)
            $team['players'] = array_merge($team['players'], $remainingNonGoalkeepers);

            // Check if the total number of players in the team (including the goalkeeper) exceeds the maximum allowed
            if ($team['playersCount'] > $numbP) {
                // If yes, trim the excess players
                $team['players'] = array_slice($team['players'], 0, $numbP);
                $team['playersCount'] = $numbP;
            }

            // Add the team to the array of teams
            $teamsArray[] = $team;

            // Update team_id for non-goalkeeper players
            foreach ($team['players'] as $player) {
                if (!$player->isGoalkeeper) {
                    $player->update(['team_id' => $team['id']]);
                    $team['playersCount'] += 1;
                }
            }

            // Add team players to the list of all players
            $allPlayersList = array_merge($allPlayersList, $team['players']);
        }

        // Distribute remaining players among the teams
        foreach ($nonGoalkeepers as $remainingPlayer) {
            $availableTeams = collect($teamsArray)->where('playersCount', '<', $numbP);
            $teamToAdd = $availableTeams->sortBy('playersCount')->first();

            if ($teamToAdd) {
                $teamToAdd['players'][] = $remainingPlayer;
                $remainingPlayer->update(['team_id' => $teamToAdd['id']]);
                $teamToAdd['playersCount'] += 1;
                $allPlayersList[] = $remainingPlayer;
            }
        }

        $resp = (object)[
            'error' => false,
            'msg' => '',
            'teams' => $teams,
            'numbP' => $numbP,
            'confirmed' => $totalConfirmedPlayers,
            'data' => $teamsArray,
            'allPlayersList' => $allPlayersList,
        ];

        return view('players.sort')->with('resp', $resp);
    }
}
