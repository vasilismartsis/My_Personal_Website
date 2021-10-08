<?php
    require "header.php";
?>

<main>
    <?php
        require 'includes/dbh.inc.php';
        
        //show all open games for the player
        if(isset($_SESSION["id"]))
        {
            $userId = $_SESSION["id"];

            $sql= 
            "   SELECT id, gameName
                FROM games 
                WHERE gameAdminId = '$userId' OR invitedPlayersIds LIKE '%$userId%';
            ";
                    
            $result = mysqli_query($con, $sql);
                    
            //list the available games
            echo '<ul>';
            while($row = mysqli_fetch_assoc($result)) 
            {
                echo 
                '
                    <li>
                        <a href="game.php?gameId='.$row["id"].'">'
                            .$row["gameName"].
                        '</a>
                    </li>
                ';
            }
            echo '</ul>';

            mysqli_close($con);
        }
        else
        {
            echo '<h1>You must login first</h1>';
        }
    ?>
</main>

<?php
    require "footer.php";
?>