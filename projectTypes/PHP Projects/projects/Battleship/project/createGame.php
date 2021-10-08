<?php
    require "header.php";
?>

<main>
    <?php
        if(isset($_SESSION["id"]))
        {
            echo 
            '
                <div id="invitedPlayersDiv">
                    <input type="text" placeholder="invite player" onkeyup="addInvitedPlayerInput(this)" class="inviteplayers"/>
                </div>
            ';
            echo '
                <form action="includes/createGame.inc.php" method="post"><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><p>Choose number of cells for the board and how many ships   (2 seats)     (3 seats)   (4 seats)      (5 seats) respectively
                <input type="text" name="gameName" placeholder="game name" class="gamename"/>
                <input type="text" id="invitedPlayers" name="invitedPlayers" placeholder="invited players" style="display:none;"/>
                <input type="text" name="boardColumns" value="10" placeholder="board columns" onkeyup="checkForNumbers(this)" class="boardcol"/>
                <input type="text" name="boardRows" value="10" placeholder="board rows" onkeyup="checkForNumbers(this)"  class="boardrow"/>
                <input type="text" name="boat2" value="1" placeholder="boat2" onkeyup="checkForNumbers(this)" class="boardship3"/>
                <input type="text" name="boat3" value="2" placeholder="boat3" onkeyup="checkForNumbers(this)" class="boardship4"/>
                <input type="text" name="boat4" value="1" placeholder="boat4" onkeyup="checkForNumbers(this)" class="boardship5"/>
                <input type="text" name="boat5" value="1" placeholder="boat5" onkeyup="checkForNumbers(this)" class="boardship6"/>
                <button type="submit" name="createGame" class="creategamebut">Create Game</button>
                </form>
            ';
        }
        else
        {
            echo '<h1>You must login first</h1>';
        }
    ?>

    <script>
    //every time I add a number inside the input, if its not a number delete it
        function checkForNumbers(x) 
        {
            x.value = x.value.replace(/[^0-9]/g, "");
        }

        function addInvitedPlayerInput(x)
        {
            var emptyInputs = 0;
            var invitedPlayersDiv = document.getElementById("invitedPlayersDiv");
            var invitedPlayers = document.getElementById("invitedPlayers");
            var finalValue = "";

            //remove input 
            for(var i = 0; i < invitedPlayersDiv.children.length; i++)
            {
                if(invitedPlayersDiv.children[i].value == "")
                {
                    emptyInputs++;

                    if(emptyInputs > 1)
                    {
                        invitedPlayersDiv.children[i].remove();
                    }
                }
                else
                {
                    //add all values from invitedPlayersDiv inputs to the form input, seperated by comas
                    finalValue += invitedPlayersDiv.children[i].value + ","
                }
            }

            //fill form input value, removing last comma
            invitedPlayers.value = finalValue.substring(0, finalValue.length - 1);

            //add input 
            if(emptyInputs == 0)
            {
                var invitedPlayerInput = x.cloneNode(true);
                x.parentElement.appendChild(invitedPlayerInput);
                invitedPlayerInput.value = "";
            }          
        }
    </script>
</main>

<?php
    require "footer.php";
?>