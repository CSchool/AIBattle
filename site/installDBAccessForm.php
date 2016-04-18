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
            $output['reason'] = 'Ошибка при соединении к БД!';
        }
    } 
    else
    {
        if (!isset($_POST['login']))
        {
            $output['status'] = 'ERR';
            $output['reason'] = "Значение поля 'Login' не указано!";
        }
    }
        
    echo "\n" . json_encode($output, JSON_UNESCAPED_UNICODE);
?>