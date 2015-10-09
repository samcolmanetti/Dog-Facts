<?php 

    require("../../includes/GoogleVoice.php");
    require("../../includes/functions.php");

    if (isset($_GET["phone"])){
        
        if (!isset($_GET["id"])) {
           $id_num = rand(1,216); 
        } else {
            $id_num = $_GET["id"];  
        } 
    
        $fact = query("SELECT data from `facts` WHERE id = ?", $id_num)[0]['data']; 
        $account = query("SELECT * from `voice_accts`")[0]; 
        try {
            $gv = new GoogleVoice($account['email'], $account['password']); 
            $gv->sendSMS($_GET["phone"], $fact);
            
            $response = "{'status':'Success','fact':".$fact."}";
        } catch (Exception $e){
            echo $e; 
        }
        
    } else {
        $response = "{'status':'Failed'}";
    }
    
    //header('Content-type: application/json');
    echo json_encode($response);
?>