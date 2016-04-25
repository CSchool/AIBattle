<?php
    include_once('procedures.php');
    $link = getDBConnection();
    if (mysqli_select_db($link, getDBName()))
    {
        $id = intval($_GET['id']);
        $query = "SELECT * FROM strategies WHERE id = $id";
        if (!isAdmin())
            $query .= " AND user = " . intval(getActiveUserID());
        $res = mysqli_query($link, $query);
        if (mysqli_num_rows($res) == 1 && !(($file = @file_get_contents("./executions/".$id)) === FALSE))
        {
            
            // in php >= 5.6 we need to set encoding for htmlspecialchars!
            $encodings = array('UTF-8', 'CP1251', 'ISO-8859-1', 'KOI8-R');
            
            foreach ($encodings as $encoding)
            {
               
                $editedCode = nl2br(str_replace(" ", "&nbsp;", 
                    str_replace("\t", "    ", htmlspecialchars($file, ENT_COMPAT | ENT_HTML401, $encoding))));
                    
                    
                if (!empty($editedCode)) 
                {
                    echo '<code>' . $editedCode . '</code>';
                    break;
                }

                
            }

        }
    }
?>
