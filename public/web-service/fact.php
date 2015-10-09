<?php 
    require("../../includes/functions.php");

    if (!isset($_GET["id"])) {
       $id_num = rand(1,216); 
    } else {
        $id_num = $_GET["id"];  
    } 
    
    $fact = query("SELECT * from `facts` WHERE id = ?", $id_num)[0]; 
    
    header('Content-type: application/json');
    echo json_encode($fact);
?>