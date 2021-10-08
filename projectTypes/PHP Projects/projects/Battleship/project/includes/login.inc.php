<?php
if (isset($_POST['login'])) 
{
    require 'dbh.inc.php';

    $username = $_POST['uid'];
    $password = $_POST['pwd'];

    if(empty($username) || empty($password))
    {
        header('Location: ../index.php?error=emptyfields');
        exit();
    }
    else
    {
        $sql = 
        'SELECT * 
        FROM users 
        WHERE userName=?;';
        
        $stmt = mysqli_stmt_init($con);
        if(!mysqli_stmt_prepare($stmt, $sql))
        {
            header('Location: ../index.php?error=sqlerror1');
            exit();
        }
        else
        {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if($row = mysqli_fetch_assoc($result))
            {
                $pwdCheck = password_verify($password, $row['pass']);
                if(!$pwdCheck)
                {
                    header('Location: ../index.php?error=wrongpwd');
                    exit();
                }
                else if($pwdCheck)
                {
                    session_start();
                    $_SESSION['name'] = $row['userName'];
                    $_SESSION['id'] = $row['id'];

                    header('Location: ../index.php?login=success');
                    exit();
                }
                else
                {
                    header('Location: ../index.php?error=wrongpwd');
                    exit();
                }
            }
            else
            {
                header('Location: ../index.php?error=nouser');
                exit();
            }
        }
    }
}
else
{
    header('Location: ../index.php');
    exit();
}