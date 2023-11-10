<DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Add player</title>
    <link href="/resources/css/app.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <section class="cad">
        <div class="row">
        </div>
        <form action="">
            <div class="row">
                <div class="col-12">
                    &nbsp;
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <h4>Add a new player</h4>
                </div>
                <div class="col-12">
                    &nbsp;
                </div>
                <div class="col-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Player's name" maxlength="255">
                        <label for="name">Player's name</label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-floating mb-3">
                        <select id="level" name="level" class="form-select form-select-lg mb-3" aria-label="Select the player level">
                            <option value="1">Level 1 - Too bad</option>
                            <option value="2">Level 2 - Bad</option>
                            <option value="3">Level 3 - Normal</option>
                            <option value="4">Level 4 - Average</option>
                            <option value="5">Level 5 - Very Good</option>
                        </select>
                        <label for="level">Select the player level</label>
                    </div>
                </div>
                <div class="col-4 d-flex align-items-center">
                    <div class="form-floating mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="isGoalkeeper" name="isGoalkeeper">
                            <label class="form-check-label" for="isGoalkeeper">Is Goalkeeper?</label>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary">Save player</button>
        </form>
    </section>
</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
