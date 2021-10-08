<?php
    require "header.php";
?>

<main>
    <?php
        if(isset($_SESSION["id"]))
        {
            echo '
            <ul>
                <li>
                    <a href="createGame.php" class="creategame">Create Game</a>
                </li>
                <li>
                    <a href="selectGame.php" class="selectgame">Select Game</a>
                </li>
            </u>
            ';
            echo "<p>You are logged in!</p>";
        }
        else
        {
            echo "<p>Login to proceed</p>";
        }
    ?>
</main>

<?php
    require "footer.php";
?>