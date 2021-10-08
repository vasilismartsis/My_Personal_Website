<?php
session_start();

if (isset($_POST['createGame'])) 
{
    require 'dbh.inc.php';

    $userId = $_SESSION["id"];
    $userName = $_SESSION['name'];
    $gameName = $_POST['gameName'];
    $invitedPlayersList = explode(",", $_POST['invitedPlayers']);
    $invitedPlayersIdList = array();
    $boardColumns = $_POST['boardColumns'];
    $boardRows = $_POST['boardRows'];
    $boat2 = $_POST['boat2'];
    $boat3 = $_POST['boat3'];
    $boat4 = $_POST['boat4'];
    $boat5 = $_POST['boat5'];
    $date = date("Y-m-d");
    $gameId;

    //check for empty fields and other input errors in createGame form
    if(count($invitedPlayersList) == 0 || in_array($userName, $invitedPlayersList))
    {
        header('Location: ../createGame.php?error=noPlayersInvited');
        exit();
    }
    else if($boardColumns == 0 || $boardRows == 0 || $boardColumns == null || $boardRows == null || $boat2 == null || $boat3 == null || $boat4 == null || $boat5 == null)
    {
        header('Location: ../createGame.php?error=emptyFields');
        exit();
    }
    else if($boat2 == 0 && $boat3 == 0 && $boat4 == 0 && $boat5 == 0)
    {
        header('Location: ../createGame.php?error=noBoatsAdded');
        exit();
    }
    //check if invited players exist in database
    else
    {
        $sql = 
        "
            SELECT userName 
            FROM users 
            WHERE userName=?;
        ";
        $stmt = mysqli_stmt_init($con);

        //check for connection with the database errors
        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            header('Location: ../createGame.php?error=sqlerror');
            exit();
        }
        //if connection with the database is ok
        else
        {
            $playersNotFound = null;

            //For each player run the sql statement and check if he exists in database
            foreach ($invitedPlayersList as &$invitedPlayer) 
            {
                mysqli_stmt_bind_param($stmt, 's', $invitedPlayer);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                $resultCheck = mysqli_stmt_num_rows($stmt);
                
                //if the player does not exist in database
                if($resultCheck == 0)
                {
                    //add his name to the playersNotFound string
                    $playersNotFound .= $invitedPlayer." ";
                }
            }
            
            //if at least one player hasn't found in database, go back to create game page with error message
            if($playersNotFound != null)
            {
                header("Location: ../createGame.php?error=noSuchUser&usersNotFound=$playersNotFound");
                exit();
            }
            //if all invited players was found
            else
            {
                $sql = 
                "
                    INSERT INTO games (gameName, gameAdminId, invitedPlayersIds, gameStarted, timeStarted, boardColumns, boardRows, boat2, boat3, boat4, boat5) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?)
                ";

                $stmt = mysqli_stmt_init($con);

                if(!mysqli_stmt_prepare($stmt, $sql))
                {
                    header('Location: ../createGame.php?error=sqlerror');
                    exit();
                }
                else
                {
                    //fill invitedPlayersId array (because I only have the players names that user gave, I also need to obtain their Ids)
                    foreach ($invitedPlayersList as &$invitedPlayer) 
                    {
                        $sql= 
                        "   SELECT id
                            FROM users 
                            WHERE userName = '$invitedPlayer';
                        ";
                        
                        $result = mysqli_query($con, $sql);
                        
                        while($row = mysqli_fetch_assoc($result)) 
                        {
                            $invitedPlayersIdList[] = $row["id"];
                        }
                    }
                    
                    //fill a row in games table
                    $gameStarted = false;
                    $tempInvitedPlayersId = implode(",",$invitedPlayersIdList);

                    mysqli_stmt_bind_param($stmt, 'sisisiiiiii', $gameName, $userId, $tempInvitedPlayersId, $gameStarted, $date, $boardColumns, $boardRows, $boat2, $boat3, $boat4, $boat5);
                    mysqli_stmt_execute($stmt);
                    
                    //Create game table
                    $sql= 
                    "   SELECT id
                        FROM games 
                        WHERE gameName = '$gameName';
                    ";

                    $result = mysqli_query($con, $sql);
                    
                    while($row = mysqli_fetch_assoc($result)) 
                    {
                        $gameId = $row["id"];
                    }

                    $sql= 
                    "
                        CREATE TABLE game$gameId (
                            playerId int(11) PRIMARY KEY NOT NULL,
                            start boolean NOT NULL,
                            turn boolean NOT NULL,
                            lost boolean NOT NULL,
                            playerJson varchar(255)
                        );

                    ";

                    //check for errors to connection with database
                    if(!mysqli_query($con, $sql))  
                    {
                        header('Location: ../createGame.php?error=sqlerror');
                        exit();
                    }
                    //add players as rows to the game table
                    else
                    {
                        //add myself
                        $sql=
                        "
                            INSERT INTO game".$gameId." (playerId, start, turn, lost)
                            VALUES ($userId, false, true, false);
                        ";
                        mysqli_query($con, $sql); 

                        //add others
                        foreach ($invitedPlayersIdList as &$invitedPlayerId) 
                        {
                            $sql=
                            "
                                INSERT INTO game".$gameId." (playerId, start, turn, lost)
                                VALUES ($invitedPlayerId, false, false, false);
                            ";
                            mysqli_query($con, $sql); 
                        }

                        //create game json file
                        $playersIdList = $invitedPlayersIdList;
                        array_unshift($playersIdList, $userId);

                        $obj = [];

                        foreach ($playersIdList as &$playerId) 
                        {
                            $obj["player$playerId"]["pins"] = 
                            [
                                "white" => [],
                                "red" => []
                            ];

                            for($i = 2; $i <= 5; $i++)
                            {
                                $obj["player$playerId"]["boats"]["boat$i"] = null;
                            }
                        }
                          
                        $json_data = json_encode($obj);
                        file_put_contents("../json/game$gameId.json", $json_data);

                        header('Location: ../game.php?success=tableCreated&gameId='.$gameId);
                        exit();
                    } 
                }
            }
        }
        //close conection
        mysqli_stmt_close($stmt);
        mysqli_close($con);
    }  
}
else
{
    header('Location: ../createGame.php');
    exit();
}