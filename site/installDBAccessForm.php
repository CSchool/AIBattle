<!-- NOT WORKING YET -->

<?php
    include_once('procedures.php');
    # get data from form
   
    $output = array('status' => '', 'reason' => ''); // json output
    
    if (isset($_POST['login']) && isset($_POST['password']))
    {
        $link = mysqli_connect('localhost', $_POST['login'], $_POST['password']); 
        if ($link)
        {
            $file = fopen('authData.txt', 'w');
            if ($file)
            {
                fwrite($file, $_POST['login']."\r\n".$_POST['password']."\r\n");
                fclose($file);
                
                $output['status'] = 'OK';
            }
            else
            {
                $output['status'] = 'ERR';
                $output['reason'] = 'Ошибка при создании файла authData.txt!';
            }
        } 
        else 
        {
            $output['status'] = 'ERR';
            $output['reason'] = 'Ошибка при соединении к БД: ' . mysqli_error();
        }
    } 
    else
    {
        $output['status'] = 'ERR';
        
        var_dump($output);
        
        /*
        if (!isset($_POST['login']))
            $output['reason'] = "Значение поля 'Login' не указано!";
        if (!isset($_POST['password'])
            $output['reason'] .= "\nЗначение поля 'Password' не указано!";
        */
    }
        
    echo json_encode($output);
?>