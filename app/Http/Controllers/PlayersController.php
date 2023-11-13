public function sort(Request $request): View
{
    // Validate the incoming request parameters.
    $request->validate([
        'teamsN' => 'required|integer|min:2',
        'numbP' => 'required|integer|min:1'
    ]);

    // Retrieve confirmed players ordered by level in ascending order.
    $confirmedPlayers = Players::where('presence', 1)
        ->orderBy('level', 'asc')
        ->get();

    // Calculate the total number of confirmed players and the required number of players.
    $totalConfirmedPlayers = count($confirmedPlayers);
    $requiredPlayers = $request->numbP * 2;

    // Convert request parameters to integers.
    $teams = (int)$request->teamsN;
    $numbP = (int)$request->numbP;

    // Check if there are enough confirmed players for the requested teams and players per team.
    if ($totalConfirmedPlayers < $requiredPlayers) {
        return view('players.sort')->with('resp', (object)[
            'error' => true,
            'msg' => "There are not enough confirmed players for the requested number of teams and players per team.",
            'teams' => $teams,
            'numbP' => $numbP,
            'confirmed' => $totalConfirmedPlayers,
        ]);
    }

    // Check if there are at least 2 goalkeepers with confirmed presence.
    $goalkeepersCount = $confirmedPlayers->where('isGoalkeeper', 1)->count();
    if ($goalkeepersCount < 2) {
        return view('players.sort')->with('resp', (object)[
            'error' => true,
            'msg' => "At least 2 goalkeepers with confirmed presence are required.",
            'teams' => $teams,
            'numbP' => $numbP,
            'confirmed' => $totalConfirmedPlayers,
        ]);
    }

    // Shuffle and separate goalkeepers and non-goalkeepers.
    $goalkeepers = $confirmedPlayers->where('isGoalkeeper', 1)->shuffle();
    $nonGoalkeepers = $confirmedPlayers->where('isGoalkeeper', 0)->shuffle();

    // Initialize arrays for teams, all players, assigned players, and assigned goalkeepers.
    $teamsArray = [];
    $allPlayersList = [];
    $assignedPlayers = [];
    $assignedGoalkeepers = [];

    // Iterate through each team.
    for ($i = 0; $i < $teams; $i++) {
        // Initialize team structure.
        $team = [
            'id' => $i + 1,
            'playersCount' => 0,
            'players' => [],
        ];

        // Assign a goalkeeper or a non-goalkeeper to the team.
        // Update team and assigned arrays accordingly.
        // Ensure the team has the required number of players.
        // Update overall player list.
        // Repeat for each player in the team.
    }

    // Handle remaining non-goalkeepers and assign them to teams with space.
    // Update team, assigned, and overall player lists.

    // Prepare response object with the sorting results.
    $resp = (object)[
        'error' => false,
        'msg' => '',
        'teams' => $teams,
        'numbP' => $numbP,
        'confirmed' => $totalConfirmedPlayers,
        'data' => $teamsArray,
        'allPlayersList' => $allPlayersList,
    ];

    // Return the view with the response object.
    return view('players.sort')->with('resp', $resp);
}
