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
        <script src="./js/indexPage.js"></script>
    </head>
    <body>
    
        <div id="container">
            <h2 id="header" class="text-center">Логин и пароль для регистрации в БД</h2>
            
            <!-- hidden link to index -->
            <div class="text-center">
                <a id="indexLink" href="index.php" class="btn btn-success btn-lg hidden">Go to index</a>
            </div>
            
            <div class="col-md-2 col-md-offset-5">
            
                <!-- first form -->
                <form id="loginForm" class="text-center">
                    <div id="loginInput" class="row">
                        <div class="form-group">
                            <label for="loginInput">Login</label>
                            <input type="text" name="login" class="form-control" placeholder="Login">
                        </div>
                    </div>
                    
                    <div id="passwordInput" class="row">
                        <div class="form-group">
                            <label for="passwordInput">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                    </div>
                    
                    <div class="row">
                        <button id="submitLoginFormData" type="button" name="submitData" class="btn btn-default" onclick="submitLoginForm()" disabled>Send data</button>
                    </div>
                </form>
                
                
                <!-- second form -->
                <form id="DBForm" class="text-center hidden">
                    <div id="DBNameInput" class="row">
                        <div class="form-group">
                            <label for="DBNameInput">Database name</label>
                            <input type="text" name="DBName" class="form-control" placeholder="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="destroyDB"> Remove existing DB
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <button id="submitDBFormData" type="button" name="submitDB" class="btn btn-default" onclick="submitDBForm()" disabled>Send data</button>
                    </div>
                </form>
                
            </div>
            
            <!-- modal -->
            <div class="modal fade" id="errorModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title text-center text-danger text-bold" id="errorModalTitle"></h4>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-toolbar">
                                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </body>
</html>
