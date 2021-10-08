<?php
session_start();

if(isset($_SESSION["id"]))
{
    require 'dbh.inc.php';

    $playerId = $_SESSION['id'];
    $gameId = $_SESSION['gameId'];

    //send start game button value to database
    if(isset($_POST['start']))
    {
        $start = $_POST['start'];

        $sql= 
        "
            UPDATE game$gameId 
            SET start = $start
            WHERE playerId = '$playerId';
        ";
                
        $result = mysqli_query($con, $sql);
    }
    //check if everyone pressed start game button
    else if(isset($_POST['checkStart']))
    {
        $start = $_POST['checkStart'];

        $sql= 
        "
            SELECT start
            FROM game$gameId
        ";
                
        $result = mysqli_query($con, $sql);

        $readyCheck = true;

        //check if everyone has start button pressed
        while($row = mysqli_fetch_assoc($result))
        {
            if(!$row['start'])
            {
                $readyCheck = false;
            }
        }

        //if everyone has start button pressed update started variable in games table
        if ($readyCheck)
        {
            $sql= 
            "
                UPDATE games 
                SET gameStarted = true
                WHERE id = '$gameId';
            ";
                        
            $result = mysqli_query($con, $sql); 
        }

        //send answer to make start button invisible if started variable in games table has become true
        $sql= 
            "
                SELECT gameStarted
                FROM games
                WHERE id = '$gameId';
            ";
                        
        $result = mysqli_query($con, $sql);
        
        $started = false;

        while($row = mysqli_fetch_assoc($result))
        {
            $started = $row['gameStarted'];
        }

        if($started)
        {
            echo true;
        }
        else
        {
            echo false;
        }
    }
    //place boat in json
    else if(isset($_POST['coordinates']))
    {
        $coordinates = $_POST['coordinates'];
        echo $coordinates;

        $sql= 
        "
            UPDATE game$gameId 
            SET playerJson = '$coordinates'
            WHERE playerId = '$playerId';
        ";
                
        $result = mysqli_query($con, $sql);
    }
    else if(isset($_POST['addShippsInJson']))
    {
        //check if this is the last player pressing start game checkbox
        $sql= 
        "
            SELECT start
            FROM game$gameId
        ";
                
        $result = mysqli_query($con, $sql);

        $playerCount = mysqli_num_rows($result);
        $lastPlayer = 0;

        while($row = mysqli_fetch_assoc($result))
        {
            $lastPlayer += $row["start"];
        }

        //if this is the last player pressing start game checkbox update json
        if($lastPlayer == $playerCount)
        {
            // //Set Turn = true to this player
            // $sql= 
            // "
            //     UPDATE game$gameId 
            //     SET turn = 1
            //     WHERE playerId = '$playerId';
            // ";
                    
            // $result = mysqli_query($con, $sql);

            //update json
            $sql= 
            "
                SELECT playerId, playerJson
                FROM game$gameId
            ";
                    
            $result = mysqli_query($con, $sql);

            $playersBoats = [];

            while($row = mysqli_fetch_assoc($result))
            {
                $playersBoats[$row['playerId']] = json_decode($row['playerJson']);
            }
            
            $gameJsonRaw = file_get_contents("../json/game".$gameId.".json");
            $gameJson = json_decode($gameJsonRaw, true);

            //clear json boats
            foreach ($gameJson as $playerKey => $player)
            {
                foreach ($player["boats"] as $boatTypeKey => $boatType)
                {
                    if(!empty($boatType))
                    {
                        foreach ($boatType as $boatKey => $boat)
                        {
                            unset($gameJson[$playerKey]["boats"][$boatTypeKey][$boatKey]);
                        }
                    }
                }
            }
            
            //add json boats
            foreach ($playersBoats as $currentPlayerId => $boatTypes)
            {
                foreach ($boatTypes as &$boatType)
                {
                    $boatLength = count($boatType);
                    $boatTypeCount = 0;
                    if(!empty($gameJson["player$currentPlayerId"]["boats"]["boat$boatLength"]))
                    {
                        $boatTypeCount = count($gameJson["player$currentPlayerId"]["boats"]["boat$boatLength"]);
                    }
                    
                    $gameJson["player$currentPlayerId"]["boats"]["boat$boatLength"]["b$boatTypeCount"]["coordinates"] = $boatType;
                }
            }
            $gameJson = json_encode($gameJson);
            file_put_contents("../json/game".$gameId.".json", $gameJson);
        }
    }
    else if(isset($_POST['pinSent']))
    {
        $pinSent = $_POST['pinSent'];

        $gameJsonRaw = file_get_contents("../json/game".$gameId.".json");
        $gameJson = json_decode($gameJsonRaw, true);

        $boatHit = false;

        //check if a boat is hit
        foreach ($gameJson as $playerKey => $player)
        {
            if($playerKey != "player$playerId"){
                foreach ($player["boats"] as $boatTypeKey => $boatType)
                {
                    if(!empty($boatType))
                    {
                        foreach ($boatType as $boatKey => $boat)
                        {
                            foreach ($boat["coordinates"] as $coordinateKey => $coordinate)
                            {
                                if( $pinSent == $coordinate)
                                {
                                    $boatHit = true;
                                }
                            }
                        }
                    }
                }
            }
        }

        if($boatHit)
        {
            $gameJson["player$playerId"]["pins"]["red"][] = $pinSent;
        }
        else
        {
            $gameJson["player$playerId"]["pins"]["white"][] = $pinSent;
        }

        echo $boatHit;

        $gameJson = json_encode($gameJson);
        file_put_contents("../json/game".$gameId.".json", $gameJson);
    }
    else if(isset($_POST['checkTurn']))
    {
        $sql= 
        "
            SELECT playerId, turn
            FROM game$gameId
        ";
                
        $result = mysqli_query($con, $sql);

        $myTurn = false;

        while($row = mysqli_fetch_assoc($result))
        {
            if($row["playerId"] == $playerId && $row["turn"])
            {
                $myTurn = true;
            }
        }

        echo $myTurn;
    }
    else if(isset($_POST['changeTurn']))
    {
        //Set turn false to the current player
        $sql= 
        "
            UPDATE game$gameId 
            SET turn = false
            WHERE playerId = '$playerId';
        ";
                
        $result = mysqli_query($con, $sql);

        //find whose turn is it next
        $sql= 
        "
            SELECT playerId, lost
            FROM game$gameId
        ";
                
        $result = mysqli_query($con, $sql);

        $playersIds = [];
        $playersLost = [];

        while($row = mysqli_fetch_assoc($result))
        {
            $playersIds[] = $row["playerId"];
            $playersLost[] = $row["lost"];
        }

        $nextPlayer = "";
        $nextActivePlayerFound = false;

        //for every player in this game
        for($i = 0; $i < count($playersIds); $i++)
        {
            //find my position ($i) in the playersIds array
            if($playersIds[$i] == $playerId)
            {
                
                $count = 0;
                $j = 1;
        
                //until I find an a nextPlayer that has not lost the game
                while (!$nextActivePlayerFound && $count <= count($playersIds))
                {
                    //if I am not the last index
                    if($i + $j < count($playersIds))
                    {
                        //if this nextPlayer hasn't lost set him as nextPlayer
                        if(!$playersLost[$i + $j])
                        {
                            $nextPlayer = $playersIds[$i + $j];
                        }
                        //if this nextPlayer has lost go back to while and try to find next player again
                        else
                        {
                            $j++;
                        }
                    }
                    //if I am the last index
                    else
                    {
                        //if this nextPlayer hasn't lost set him as nextPlayer
                        if(!$playersLost[0])
                        {
                            $nextPlayer = $playersIds[0];
                        }
                        //if this nextPlayer has lost go back to while and try to find next player again
                        else
                        {
                            $j = 0;
                        }
                    }
                    
                    $count++;
                }
            }
        }

        echo $nextPlayer;

        //Set turn = true to the next player
        $sql= 
        "
            UPDATE game$gameId 
            SET turn = true
            WHERE playerId = '$nextPlayer';
        ";
                
        $result = mysqli_query($con, $sql);

    }
    else if(isset($_POST['initializeGame']))
    {
        $gameJsonRaw = file_get_contents("../json/game".$gameId.".json");
        $gameJson = json_decode($gameJsonRaw, true);
        $newJson = [];
        $newJsonRaw = "";

        $newJson = $gameJson["player$playerId"];

        $newJsonRaw = json_encode($newJson);

        //if($newJsonRaw != null)
        {
            print_r($newJsonRaw);
        }
    }
    else if(isset($_POST['boatLengths']))
    {
        $boatLengths = [];

        $sql= 
        "
            SELECT boat2, boat3, boat4, boat5
            FROM games
            where id = $gameId
        ;";
                
        $result = mysqli_query($con, $sql);

        while($row = mysqli_fetch_assoc($result))
        {
            $boatLengths["boat2"] = $row["boat2"];
            $boatLengths["boat3"] = $row["boat3"];
            $boatLengths["boat4"] = $row["boat4"];
            $boatLengths["boat5"] = $row["boat5"];
        }

        $boatLengthsJson = json_encode($boatLengths);

        echo $boatLengthsJson;
    }
    else if(isset($_POST['getBoatsWrecked']))
    {
        $gameJsonRaw = file_get_contents("../json/game".$gameId.".json");
        $gameJson = json_decode($gameJsonRaw, true);

        $newJson = [];

        foreach ($gameJson as $playerKey => $player)
        {
            if($playerKey != "player$playerId")
            {                
                foreach ($player["boats"] as $boatTypeKey => $boatType)
                {
                    if(!empty($boatType)){
                        foreach ($boatType as $boatKey => $boat)
                        {
                            if(count(array_intersect($boat["coordinates"], $gameJson["player".$playerId]["pins"]["red"])) == count($boat["coordinates"]))
                            {
                                $newJson[] = $boat["coordinates"];
                            }      
                        }
                    }
                }
            }
        }

        $newJsonRaw = json_encode($newJson);

        echo $newJsonRaw;
    }
    else if(isset($_POST['getMyBoatPins']))
    {
        $gameJsonRaw = file_get_contents("../json/game".$gameId.".json");
        $gameJson = json_decode($gameJsonRaw, true);

        $newJson = [];

        $myCoordinates = [];
        $othersRedPins = [];

        foreach ($gameJson["player$playerId"]["boats"] as $boatTypeKey => $boatType)
        {
            if(!empty($boatType))
            {
                foreach ($boatType as $boatKey => $boat)
                {
                    foreach ($boat["coordinates"] as $coordinateKey => $coordinate)
                    {
                        $myCoordinates[] = $coordinate;
                    }
                }
            }
        }

        foreach ($gameJson as $playerKey => $player)
        {
            if($playerKey != "player$playerId")
            {                
                foreach ($player["pins"]["red"] as $redPinKey => $redPin)
                {
                    if(in_array($redPin, $myCoordinates) && !in_array($redPin, $newJson))
                    {
                        $newJson[] = $player["pins"]["red"][$redPinKey];
                    }
                }
            }
        }

        $newJsonRaw = json_encode($newJson);

        echo $newJsonRaw;
    }
    else if(isset($_POST['setPlayerTurn']))
    {
        $sql= 
        "   SELECT userName, turn
            FROM game$gameId LEFT JOIN users on game$gameId.playerId = users.id;
        ";
                
        $result = mysqli_query($con, $sql);

        while($row = mysqli_fetch_assoc($result))
        {
            if($row['turn'])
            {
                echo $row['userName'];
            }
        }
    }
    else if(isset($_POST['setScore']))
    {
        $gameJsonRaw = file_get_contents("../json/game".$gameId.".json");
        $gameJson = json_decode($gameJsonRaw, true);
        $newJson = [];

        foreach ($gameJson as $playerKey => $player)
        {               
            $newJson[str_replace("player", "", $playerKey)] = null;

            foreach ($player["pins"]["red"] as $redPinKey => $redPin)
            {
                $newJson[str_replace("player", "", $playerKey)][] = $redPin;
            }
        }

        $newJsonRaw = json_encode($newJson);

        echo $newJsonRaw;
    }
    else if(isset($_POST['lose']))
    {
        $sql= 
        "   UPDATE game$gameId 
            SET lost = true
            WHERE playerId = '$playerId';
        ";
                
        $result = mysqli_query($con, $sql);
    }
    else if(isset($_POST['checkForWinner']))
    {
        $sql= 
        "   SELECT userName, lost
            FROM game$gameId LEFT JOIN users on game$gameId.playerId = users.id;
        ";
                
        $result = mysqli_query($con, $sql);

        $playersRemaining = 0;
        $test = 0;
        $winner = "";

        while($row = mysqli_fetch_assoc($result))
        {
            if(!$row['lost'])
            {
                $test++;
                $playersRemaining++;
                $winner = $row['userName'];
            }
        }

        if($playersRemaining == 1)
        {
            echo $winner;
        }
    }
    else if(isset($_POST['TestAjax']))
    {
        $gameJsonRaw = file_get_contents("../json/game".$gameId.".json");

        echo $gameJsonRaw;
    }

    mysqli_close($con);
}
else
{
    echo "<h1>You need to login first!</h1>";
}