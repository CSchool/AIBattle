<?php
    include_once('procedures.php');
    
    $echoAnswer = "";
    
    $compilerId = intval($_POST['compilerId']);
    
    if (isset($_POST['createCompiler']))
    {
        $result = createCompiler($compilerId, $_POST['compilerName'], $_POST['compilerFile']);
        
        switch ($result)
        {
            case 0:
                $echoAnswer = "Компилятор успешно создан и записан в БД!";
                break;
            case 1:
                $echoAnswer = "Не удалось осуществить вставку значений!";
                break;
            case 2:
                $echoAnswer = "Произоошла ошибка при работе с файлом компилятора!";
                break;
            case 3:
                $echoAnswer = "Не удалось подключиться к БД!";
                break;
            case 4:
                $echoAnswer = "К сожалению, Вы не администратор сайта!";
                break;
        }
    }
    
    echo $echoAnswer;
?>