<?php
if (isset($_POST['signup'])) 
{
    require 'dbh.inc.php';

    $username = $_POST['uid'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];

    if(empty($username) || empty($password) || empty($passwordRepeat))
    {
        header('Location: ../signup.php?error=emptyFields&uid='.$username);
        exit();
    }
    else if($password !== $passwordRepeat)
    {
        header('Location: ../signup.php?error=wrongpwd&uid='.$username);
    }
    else
    {
        $sql = 
        '   SELECT userName 
            FROM users 
            WHERE userName=?;
        ';

        $stmt = mysqli_stmt_init($con);
        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            header('Location: ../signup.php?error=sqlerror&uid='.$username);
            exit();
        }
        else
        {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if($resultCheck > 0)
            {
                header('Location: ../signup.php?error=userTaken');
                exit();
            }
            else
            {
                $sql = 'INSERT INTO users (userName, pass) VALUES (?, ?)';
                $stmt = mysqli_stmt_init($con);
                if(!mysqli_stmt_prepare($stmt, $sql))
                {
                    header('Location: ../signup.php?error=sqlerror&uid='.$username);
                    exit();
                }
                else
                {
                    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

                    mysqli_stmt_bind_param($stmt, 'ss', $username, $hashedPwd);
                    mysqli_stmt_execute($stmt);

                    header('Location: ../signup.php?signup=success');
                    exit();
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
else
{
    header('Location: ../signup.php');
    exit();
}