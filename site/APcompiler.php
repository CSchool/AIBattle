<?php
    include_once('procedures.php');
    $_SESSION['adminPanelState'] = 'APcompiler.php';
    
    if (isAdmin())
    {
        
        $compilerId = -1;
        
        if (isset($_GET['compiler']))
            $compilerId = intval($_GET['compiler']);
        
        $compilerList = getCompilerList();
        $currentCompiler = getCompilerList($compilerId);
?>
    
    <form role="form" method="post">
        <div class="form-group">
            <label for = "сompilerSelector" class = "APfont">Компилятор:</label>
            <select id="сompilerSelector" class="form-control">
                <option value = "-1">Новый компилятор</option>
                <?php
                    foreach ($compilerList as $compiler)
                    {
                ?>
                    <option value = "<?php echo $compiler['id']; ?>">
                        <?php echo $compiler['name']; ?>
                    </option>
                <?php
                    }
                ?>
            </select>
        </div>
        <br>
        <div class="form-group">
            <label for="compilerName" class = "APfont">Название компилятора:</label>
            <input type="text" class="form-control" id="compilerName" placeholder="Введите название компилятора" value = "<?php if ($compilerId != -1) echo $currentCompiler[0]['name']; ?>">
        </div>
        <br>
        <div class="form-group">
            <?php if ($compilerId != -1) 
                { 
            ?>
                <label><i>Компилятор в наличии!</i></label>
                <br>
            <?php 
                } 
            ?>
            <label for = "uploadCompilerFile" class = "APfont">Код компилятора <?php if ($compilerId != -1) { ?> (обновление) <?php } ?>:</label>
            <input type = "file" id = "uploadCompilerFile">
        </div>
    </form>
    
<?php
    }
    else
    {
?>
    <div class = "redColored">
        <p>Тебя не должно быть здесь!</p>
    </div>
<?php
    }
?>