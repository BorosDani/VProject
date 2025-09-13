<?php
$db = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "villamme_villammelo";

try
{
    $con = mysqli_connect($db, $db_user, $db_pass, $db_name);

    if(!$con)
    {
        throw new exeption("Nem sikerült csatlakozni az adatbázishoz: " . mysqli_connect_error());
    }
    mysqli_set_charset($con, "utf8");
}

catch(exeption $e)
{
    die("Hiba: " . $e->getMessage());
}
?>