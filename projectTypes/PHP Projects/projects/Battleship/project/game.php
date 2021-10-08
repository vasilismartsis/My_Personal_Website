<?php
    require "header.php";
?>

<style>
    table, td, th
    {
        border: 3px solid black;
        border-color: black;
        border-spacing: 0px;
    }
    td
    {
        width: 50px;
        height: 50px;
    }
    .start
    {
        width: 50px;
        height: 50px;
    }
</style>

<main style="align-content: center; text-align: center;">
    <?php
        $playerId;
        $gameId;
        $columns;
        $rows;

        if(isset($_SESSION["id"]))
        {
            if (!isset($_GET['gameId']))
            {
                header('Location: ../selectGame.php');
                exit();
            }
            else
            {
                require 'includes/dbh.inc.php';

                //initialize some variables
                $playerId = $_SESSION["id"];
                $gameId = $_GET['gameId'];
                $_SESSION['gameId'] = $gameId;

                $sql= 
                "   SELECT *
                    FROM games
                    WHERE id = '$gameId';
                ";
                        
                $result = mysqli_query($con, $sql);
                        
                while($row = mysqli_fetch_assoc($result)) 
                {
                    $columns = $row["boardColumns"];
                    $rows = $row["boardRows"];
                }

                //check if game has already started
                $gameStarted = false;

                $sql= 
                "   SELECT gameStarted
                    FROM games
                    WHERE id = '$gameId';
                ";
                        
                $result = mysqli_query($con, $sql);

                while($row = mysqli_fetch_assoc($result)) 
                {
                    $gameStarted = $row['gameStarted'];
                }
                
                echo "<p id='playerTurn'>The Game hasn't started yet. Place your boats.</p>";
                if($gameStarted)
                {
                    
                }
                else
                {
                    initializeShipContainer();
                    initializeStartCheckbox();
                }

                initializeScoreTable();
                initializeBoard();
            }
        }
        else
        {
            echo '<h1>You must login first</h1>';
        }

        function initializeShipContainer()
        {            
            echo '<table id="ships">';
                echo 
                '
                    <tr>
                        <td align="center">Boat Type</td>
                        <td align="center">Quantity</td>
                    </tr>
                    <tr>
                        <td id="boat2" onclick="selectBoat(this)" align="center">
                            <img src="images/boat2"></img>
                        </td>
                        <td id="boat2Quantity" align="center">0</td>
                    </tr>
                    <tr>
                        <td id="boat3" onclick="selectBoat(this)" align="center">
                            <img src="images/boat3"></img>
                        </td>
                        <td id="boat3Quantity" align="center">0</td>
                    </tr>
                    <tr>
                        <td id="boat4" onclick="selectBoat(this)" align="center">
                            <img src="images/boat4"></img>
                        </td>
                        <td id="boat4Quantity" align="center">0</td>
                    </tr>
                    <tr>
                        <td id="boat5" onclick="selectBoat(this)" align="center">
                            <img src="images/boat5"></img>
                        </td>
                        <td id="boat5Quantity" align="center">0</td>
                    </tr>
                ';
            echo '</table>';
        }

        function initializeBoard()
        {
            global $columns;
            global $rows;

            //Boat Board
            echo "<div style='display: inline-block; padding:5px;'>";
                echo "<p align='center'>Your Boats Board</p>";
                echo "<table>";
                    echo "<tr>";
                        echo
                            "
                                <td align='center' style='width: 53px;'></td>
                            ";
                        for($j = 0; $j < $columns; $j++)
                        {
                            echo
                            "
                                <td align='center'>$j</td>
                            ";
                        }
                    echo "<tr>";
                echo "</table>";

                echo "<table style='float:left;'>";
                for($i = 0; $i < $rows; $i++)
                {
                    echo
                    "
                        <tr>
                            <td align='center'>$i</td>
                        </tr>
                    ";
                }
                echo "</table>";

                echo '<table id="boatBoard">';
                    for($i = 0; $i < $rows; $i++)
                    {
                        echo '<tr>';
                            for($j = 0; $j < $columns; $j++)
                            {
                                echo 
                                "
                                    <td id='m$i,$j' onmouseover='highlightBoatArea(this)' onwheel='highlightBoatArea(this)' onclick='addOrRemoveBoat(this)'></td>
                                ";
                            }
                        echo '</tr>';
                    }
                echo '</table>';
            echo "</div>";

            //Pin Board
            echo "<div style='display: inline-block; padding:5px;'>";
                echo "<p align='center'>Battle Board</p>";
                echo "<table>";
                    echo "<tr>";
                        echo
                            "
                                <td align='center' style='width: 53px;'></td>
                            ";
                        for($j = 0; $j < $columns; $j++)
                        {
                            echo
                            "
                                <td align='center'>$j</td>
                            ";
                        }
                    echo "<tr>";
                echo "</table>";

                echo "<table style='float:left;'>";
                for($i = 0; $i < $rows; $i++)
                {
                    echo
                    "
                        <tr>
                            <td align='center'>$i</td>
                        </tr>
                    ";
                }
                echo "</table>";
                
                echo "<table id='pinBoard'>";
                for($i = 0; $i < $rows; $i++)
                {
                    echo '<tr>';
                        for($j = 0; $j < $columns; $j++)
                        {
                            echo 
                            "
                                <td id='o$i,$j' onmouseover='highlightPinArea(this)' onwheel='highlightPinArea(this)', onclick='sendPin(this)'></td>
                            ";
                        }
                    echo '</tr>';
                }
                echo '</table>';
            echo "</div>";
        }

        function initializeStartCheckbox()
        {
            echo 
            "
            <div style='width: 150px; height: 150px; position:absolute; top: 250px; left:20%;'>
                <p id='startLabel' align='center' style='display: none'>Ready to start game?<p>
                <input id='start' class='start' type='checkbox' style='display: none; position:absolute; top: 35%; left:32%;' onclick='sendStartGameCheckbox(this)'/>
            </div>
            ";
        }

        function initializeScoreTable()
        {
            global $con;
            global $gameId;

            $invitedPlayersIds = "";
            $playersIds;
            $players;

            //fill playersIds list
            $sql= 
            "   SELECT gameAdminId, invitedPlayersIds
                FROM games
                WHERE id = '$gameId';
            ";
                    
            $result = mysqli_query($con, $sql);
                    
            while($row = mysqli_fetch_assoc($result)) 
            {
                $invitedPlayersIds = $row["gameAdminId"].",";

                $invitedPlayersIds .= $row["invitedPlayersIds"];
            }

            $invitedPlayersIdsList = explode(',', $invitedPlayersIds);

            foreach ($invitedPlayersIdsList as $invitedPlayerIdKey => $invitedPlayerId)
            {
                $playersIds[] = $invitedPlayerId;
                $players[$invitedPlayerId] = "";
            }

            //fill players list with names
            $sql= 
            "   SELECT id, userName
                FROM users
            ";
                    
            $result = mysqli_query($con, $sql);
                    
            while($row = mysqli_fetch_assoc($result)) 
            {
                if(in_array($row["id"], $playersIds))
                {
                    $players[$row["id"]] = $row["userName"];
                }
            }

            //create score table
            echo '<table id="scoreTable" style="display:none; position:relative; top:50%; left:32.3%;">';
                echo '<tr>';
                    echo '<th align="center">Player</th>';
                    echo '<th align="center">Score</th>';
                echo '</tr>';
                foreach ($players as $playerId => $playerName)
                {
                    echo '<tr>';
                        echo "<td align='center'>$playerName</td>";
                        echo "<td id='".$playerId."Score' align='center'>0</td>";
                    echo '</tr>';
                }
            echo '</table>';
        }
    ?>
 
    <!-- <button onclick='TestAjax()'>Test Button</button> -->
    <p id="demo"></p>

    <script src="js/gameStarting.js"></script>
    <script src="js/gameStarted.js"></script>
</main>

<?php
    require "footer.php";
?>