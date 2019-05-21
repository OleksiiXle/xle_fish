$(document).ready ( function(){
    $("#newMediaBtn").click('on', function () {
        $("#newMedia").show('slow');
        $("#listMedia").hide('slow');
    });

    var files; // переменная. будет содержать данные файлов

    // заполняем переменную данными, при изменении значения поля file
    $('input[type=file]').on('change', function(){
        files = this.files;
        console.log(files);
    });



    // обработка и отправка AJAX запроса при клике на кнопку upload_files
    $('#saveMediaBtn').on( 'click', function( event ){

        event.stopPropagation(); // остановка всех текущих JS событий
        event.preventDefault();  // остановка дефолтного события для текущего элемента - клик для <a> тега

        // ничего не делаем если files пустой
        if( typeof files == 'undefined' ) return;

        // создадим объект данных формы
        var data = new FormData();
       // var data = $("#post-update-id").serialize();
/*
        //  data.append('file', files);
        jQuery.each(jQuery('#postmedia-mediafile')[0].files, function(i, file) {
            console.log(file);

            data.append('file-'+i, file);
        });

        console.log(files);
*/
        // заполняем объект данных файлами в подходящем для отправки формате
        $.each( files, function( key, value ){
            data.append( key, value );
        });
        console.log(data);

        // добавим переменную для идентификации запроса
        data.append( 'my_file_upload', 1 );

        // AJAX запрос
        $.ajax({
            url         : '/post/post/get-media-preview',
            type        : 'POST', // важно!
            data        : data,
            cache       : false,
            dataType    : 'json',
            // отключаем обработку передаваемых данных, пусть передаются как есть
            processData : false,
            // отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
            contentType : false,
            // функция успешного ответа сервера
            success     : function( respond, status, jqXHR ){
                console.log(respond);

                // ОК - файлы загружены
                if( typeof respond.error === 'undefined' ){
                    // выведем пути загруженных файлов в блок '.ajax-reply'
                    /*
                    var files_path = respond.files;
                    var html = '';
                    $.each( files_path, function( key, val ){
                        html += val +'<br>';
                    } )

                    $('.ajax-reply').html( html );
                    */
                }
                // ошибка
                else {
                    console.log('ОШИБКА: ' + respond.error );
                }
            },
            // функция ошибки ответа сервера
            error: function( jqXHR, status, errorThrown ){
                console.log( 'ОШИБКА AJAX запроса: ' + status, jqXHR );
            }

        });

    });







});


