<?php
session_start();

if(isset($_POST['gameId']))
{
    $gameId = $_POST['gameId'];

    $gameJsonRaw = file_get_contents("../json/game".$gameId.".json");
    $gameJson = json_encode($gameJsonRaw);

    print json_encode($gameJsonRaw, JSON_PRETTY_PRINT);
}
else
{
    $files = scandir('../json');
    foreach($files as $file) 
    {
        if(strpos($file, '.json'))
        {
            $gameJsonRaw = file_get_contents("../json/$file");
            $gameJson = json_encode($gameJsonRaw);

            print json_encode($gameJsonRaw, JSON_PRETTY_PRINT);
        }
    }
}