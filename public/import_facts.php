<?php 
    // configuration
   require("../includes/config.php"); 
    /*   
    $handle = fopen("dogfacts.txt", "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            query("INSERT into facts (data) VALUES (?)", $line); 
        }
        fclose($handle);
    } else {
        echo "File not found"; 
    }
    */
    $rows = query ("select * from facts");
    
    foreach ($rows as $row){
        echo ($row["id"]."\t".$row["data"]."<br>"); 
    }

?>
