$(document).ready(function() {

    var regexpName = /^[а-я\s]+$/i;

    $('#submit').attr('disabled',true);

    $('#nickname').keyup(function () {
        var regexpNickname = /^[a-z\s]+$/i;
        var inputNickname = regexpNickname.test($('#nickname').val());

        testValidation(inputNickname,'nickname','Только латинские буквы');
        submitValidation(inputNickname);
    });

    $('#name').keyup(function (){
        var inputName = regexpName.test($('#name').val());

        var result = testValidation(inputName,'name','Только русские буквы');
        submitValidation(result);
    });

    $('#surname').keyup(function (){
        var inputSurname = regexpName.test($('#surname').val());

        testValidation(inputSurname,'surname','Только русские буквы');
        submitValidation(inputSurname);
    });

    $('#email').keyup(function (){
        var regexEmail = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i;
        var inputEmail = regexEmail.test($('#email').val());

        testValidation(inputEmail,'email','Неккоректный email');
        submitValidation(inputEmail);
    });

    $('#password').keyup(function (){
        var inputPassword = $('#password').val();

        if(inputPassword.length < 5){
            $('.message-password').text('Пожалуйста выдумайте пароль длинее 5 символов');
            $('#status-password').text('!').attr('class','red');
            submitValidation(false);
        }else{
            $('#status-password').html('&#10004;').attr('class','green');
            $('.message-password').text(' ');
            submitValidation(true);
        }

    });


    function submitValidation(value){
        if(value == true || value != null || value != undefined){
            $('#submit').attr('disabled',false);
        }else{
            $('#submit').attr('disabled',true);
        }
    }




    //проверка на соответствие
    function testValidation(name,cell,text) {
        if(!name){
            if(text){
                $('.message-'+cell).text(text);
            }else{
                $('.message-'+cell).text('Неверно!');
            }
            $('#status-'+cell).text('!').attr('class','red');
            return false;
        }else{
            $('#status-'+cell).html('&#10004;').attr('class','green');
            $('.message-'+cell).text(' ');
            return true;
        }
    }


    $('form').submit(function(event) {

        var json;
        event.preventDefault();
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                
            },
            success: function(result) {
                json = jQuery.parseJSON(result);
                if (json.message) {
                    alert(json.message);
                }else if(json.name){
                    $('.message-nickname').text(json.name);
                }
            },
        });
    });
});