<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="ADISE PROJECT">
        <meta name=viewport content="width=device-width, initial-scale=1">
        <link rel = "stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <title></title>
    </head>
    <body>
        <section id = "nav-bar">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="/"><img src="Images/ship.jpg"></a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <section id="Banner">
                    <p class="title"> Battleships Wars!</p>
                    <p class="keimeno">Welcome to Battleship Wars login to procced or Sign up to become master of the Battleships Wars!</p>
                </div>
                
                <header>
                        <p>               
                        <div class="text">
                            <?php
                                if(isset($_SESSION["id"]))
                                {
                                    echo '
                                <form action="includes/logout.inc.php" method="post">
                                        <button type="submit" name="logout" class="st2">Logout</button>
                                    </form>
                                    ';
                                }
                                else
                                {
                                    echo '
                                    <form action="includes/login.inc.php" method="post">
                                        <input type="text" name="uid" placeholder="username" class="st"></input>
                                        <input type="password" name="pwd" placeholder="password" class="st1"></input>
                                        <button type="submit" name="login" class=
                                        "st3">Login</button>
                                    </form>
                                    <a href="signup.php" class="st4">Signup</a>
                                    ';
                                }
                            ?>         
                        </div>
                    </p>
                </header>
            </nav>
        </section>

        <?php
            if (isset($_GET["error"]))
            {
                if($_GET['error'] == "emptyfields")
                {
                    echo '<p>Fill in all fields!</p>';
                }
                else if ($_GET['error'] == "usertaken") 
                {
                    echo '<p>Username taken!</p>';
                }
                else if ($_GET['error'] == "wrongpwd") 
                {
                    echo '<p>Invalid password!</p>';
                }
                else if ($_GET['error'] == "sqlerror") 
                {
                    echo '<p>Sql error!</p>';
                }
                else if ($_GET['error'] == "nouser") 
                {
                    echo '<p>Wrong credentials!</p>';
                }
            }    
        ?>
    </body>
</html>