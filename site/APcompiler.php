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

    <script>
        changeActiveAdminButton('compilerButton');
    </script>
    
    <script>
        function loadCompilerData()
        {
            $('#dataContainer').load('APcompiler.php?compiler=' + $('#сompilerSelector').val());
        }
    
        function loadForm()
        {   
            var selectedCompiler = $('#сompilerSelector').val();
            var compilerName = $('#compilerName').val();
            
            var form = new FormData();
            
            form.append('compilerId', selectedCompiler);
            form.append('compilerName', compilerName);
            
            if ($('#uploadCompilerFile')[0].files[0])
            {
                form.append('compilerFile', 'uploadCompilerFile');
                form.append('uploadCompilerFile', $('#uploadCompilerFile')[0].files[0]);
            }
            
            form.append(selectedCompiler == -1 ? 'createCompiler' : 'updateCompiler', true);
            
            
            $.ajax({
                url: 'jqueryCompiler.php',
                type: 'POST',
                success: function (data)
                {   
                    showModalAlert(data);
                },
                data: form,
                cache: false,
                contentType: false,
                processData: false
            });
        }
    </script
    
    <form id="compilerForm" role="form" method="post">
        <div class="form-group">
            <label for = "сompilerSelector" class = "APfont">Компилятор:</label>
            <select name="сompilerSelector" id="сompilerSelector" class="form-control" onchange="loadCompilerData();">
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
            <script>
                setSelectedIndexInSelector('сompilerSelector', <?php echo $compilerId; ?>);
            </script>
        </div>
        <br>
        <div class="form-group">
            <label for="compilerName" class="APfont">Название компилятора:</label>
            <input type="text" class="form-control"  name="compilerName" id="compilerName" placeholder="Введите название компилятора" value = "<?php if ($compilerId != -1) echo $currentCompiler[0]['name']; ?>">
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
            <label for="uploadCompilerFile" class="APfont">Файл компилятора <?php if ($compilerId != -1) { ?> (обновление) <?php } ?></label>
            <input type="file" name="uploadCompilerFile" id ="uploadCompilerFile">
        </div>
        <br>
        <div class="btn-group">
            <button type="submit" name="submit" class="btn btn-default" name="submitData" onclick="loadForm(); return false;">
                <?php
                    if ($compilerId == -1)
                        echo 'Создать компилятор';
                    else
                        echo 'Применить изменения';
                ?>
            </button>
            <?php
                if ($compilerId != -1)
                {
            ?>
            <button type="submit" name="deleteData" class="btn btn-default">Удалить компилятор</button> 
            <?php
                }
            ?>
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