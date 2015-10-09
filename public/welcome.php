<?php

    require("../includes/config.php"); 

    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        render("welcome.php", ["title" => "Welcome"]);
    }
?>
