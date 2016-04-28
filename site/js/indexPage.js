$( document ).ready(function() {
    $("form#loginForm :input").keyup(function() {
        $('#submitLoginFormData').prop('disabled', $("input[name='login']").val() === "");
    });
    
    $("form#DBForm :input[name='DBName']").keyup(function() {
        $('#submitDBFormData').prop('disabled', $(this).val() === "");
    });
});

function submitLoginForm() {
    $.post("installDBAccessForm.php", $("#loginForm").serialize())
    .done(function(data) {
        
        
        var splitData = data.split('\n');
        
        $(splitData).each( function(index, element) {
            try {
                var response = JSON.parse(data);
        
                if (response.status == 'ERR') {
                   showModal(response.reason); 
                } else if (response.status == 'OK') {
                    $('#header').html('Создание новой базы данных');
                    $('#loginForm').hide();
                    $('#DBForm').removeClass('hidden');
                } else {
                    showModal('Неизвестный код со стороны сервера!');
                }
            }
            catch (err) {}
        });        
    });
}

function submitDBForm() {
    $.post("installCreateDB.php", $("#DBForm").serialize())
    .done(function(data) {
        
        var splitData = data.split('\n');
        
        $(splitData).each( function(index, element) {
            try {
                var response = JSON.parse(element);
                showModal(response.reason);
                
                if (response.status == 'OK') {
                    $('#header').html('Все готово!');
                    $('#DBForm').hide();
                    $('#indexLink').removeClass('hidden');
                } 
            }
            catch (err) {}
        });
    });
}

function showModal(text) {
    $('#errorModalTitle').html(text);
    $('#errorModal').modal('show');
}