<?php
    include_once('procedures.php');
    # get data from form
    var_dump($_POST);
    if (isset($_POST['submitData']))
    {
        if (isset($_POST['login']) && isset($_POST['password']))
        {
            $link = mysqli_connect('localhost', $_POST['login'], $_POST['password']) or die("Can't connect to DB: ".mysqli_error()); 
            if ($link)
            {
                $file = fopen('authData.txt', 'w');
                if ($file)
                {
                    fwrite($file, $_POST['login']."\r\n".$_POST['password']."\r\n");
                    fclose($file);
                    echo '<meta http-equiv="refresh" content="0; url=installCreateDB.php">';
                    exit();
                }
            }
        }
    }   
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Install</title>

        <!-- Bootstrap core CSS -->
        <link href="./css/bootstrap.min.css" rel="stylesheet">
        
        <script src="./js/jquery-1.10.2.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
    </head>
    <body>
    
        <div id="container">
            <h2 id="header" class="text-center">Логин и пароль для регистрации в БД</h2>
            
            <div class="col-md-2 col-md-offset-5">
                <form id="form" class="text-center">
                    <div id="loginInput" class="row">
                        <div class="form-group">
                            <label for="loginInput">Login</label>
                            <input type="text" name="login" class="form-control" placeholder="root">
                        </div>
                    </div>
                    
                    <div id="passwordInput" class="row">
                        <div class="form-group">
                            <label for="passwordInput">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="">
                        </div>
                    </div>
                    
                    <div id="DBNameInput" class="row hidden">
                        <div class="form-group">
                            <label for="DBNameInput">Database name</label>
                            <input type="text" name="DBName" class="form-control" placeholder="">
                        </div>
                    </div>
                    
                    <div class="row">
                        <button id="submitButton" type="button" name="submitData" class="btn btn-default">Send data</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>

<script>
    $( document ).ready(function() {
        $('#submitButton').click(function (event) {
            
            $.post("installDBAccessForm.php", $("#form").serialize())
            .done(function(data) {
                console.log(data);
            });
            
        });
    });
</script>
