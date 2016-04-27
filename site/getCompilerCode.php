<?php
    include_once('procedures.php');
    
    $id = intval($_GET['id']);
    
    $link = getDBConnection();
    
    $result = "";
    
    if (mysqli_select_db($link, getDBName()))
    {
        $queryText = "SELECT text FROM compilers WHERE id = $id";
        $query = mysqli_query($link, $queryText);
        
        $code = mysqli_result($query, 0);
        $result = htmlspecialcharsEncoding($code);
    }
    
    echo '<code>' . $result . '</code>';
?>