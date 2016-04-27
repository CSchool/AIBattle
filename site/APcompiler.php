<?php
    include_once('procedures.php');
    $_SESSION['adminPanelState'] = 'APcompiler.php';
    
    if (isAdmin())
    {
        
        
?>
    
    <form role="form" method="post">
        <div class="form-group">
            <label for = "сompilerSelector" class = "APfont">Компилятор:</label>
            <select id="сompilerSelector" class="form-control">
                <option value = "-1">Новый компилятор</option>
            </select>
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