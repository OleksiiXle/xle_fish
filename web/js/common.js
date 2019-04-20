
function errorHandler(jqXHR, error, errorThrown){
    console.log('Помилка:');
    console.log(error);
    console.log(errorThrown);
    console.log(jqXHR);
    /*
    if (jqXHR['status']==403){
        //   alert('accessDeny');
        var flashMessage = '';
        flashMessage += '<div class="alert alert-danger alert-dismissible">' + 'Дія заборонена' +'</div>';
        $("#flashMessage").show('slow');
        $("#flashMessage").html(flashMessage);
        setTimeout(function() {
            $("#flashMessage").hide('slow');
        }, 5000);
        $("#main-modal-lg").modal("hide");
        $("#main-modal-md").modal("hide");
    }
    */
}

//-- обработка ошибок после аякс запроса
//-- если 403 - в #flashMessage /views/layouts/commonLayout выводится соответствующее сообщение
function errorHandlerModal(xhrStatus, xhr, status){
    var flashMessage = '';
    switch (xhrStatus){
        case 200:
            return true;
            break;
        case 403:
            flashMessage += '<div class="alert alert-danger alert-dismissible">' + 'Дія заборонена' +'</div>';
            break;
        default:
            flashMessage += '<div class="alert alert-danger alert-dismissible">' + 'Системна помилка ' + xhrStatus +  status +'</div>';
            break;
    }
    $("#flashMessage").show();
    $("#flashMessage").html(flashMessage);
    setTimeout(function() {
        $("#flashMessage").hide();
    }, 5000);
    $("#main-modal-lg").modal("hide");
    $("#main-modal-md").modal("hide");
    console.log('Помилка:');
    console.log(status);
    console.log(xhr);
}

function objDump(object) {
    var out = "";
    if(object && typeof(object) == "object"){
        for (var i in object) {
            out += i + ": " + object[i] + "\n";
        }
    } else {
        out = object;
    }
    alert(out);
}

var collector={
    runner:[],
    counter:0,
    def : null,
    countDone:function(){--collector.counter; if (collector.counter == 0) collector.def.resolve();},
    run:function(setup){
        this.def = jQuery.Deferred();
        this.counter = setup.length;//Object.keys(setup).length;
        $.each(setup,function(index,obj){
            //console.log(typeof(obj));
            if (typeof(obj) == 'object')
                collector.runner.push($.get.apply($.get,obj).done(collector.countDone)); else
            if (typeof(obj) == 'function')
                collector.countDone();
        });
        return this.def.promise();
    },
    clear:function (){
        this.runner = [];
        this.def = null;
    }
};

function displayFlashMessage(msg) {
    var flashMessage = $("#flashMessage");
    var flashMessageContent = '';
    if (flashMessage.length > 0){
        flashMessageContent += '<div class="alert alert-danger alert-dismissible">' + msg +'</div>';
        flashMessage.show();
        flashMessage.html(flashMessageContent);
        setTimeout(function() {
            flashMessage.hide();
        }, 3000);
    }

}

//-- показать/убрать прелоадер, parent- ид элемента после которого рисуется прелоадер, и который будет затухать
//-- id -порядковый номер прелоадера - чтобы не былдо конфликтов
function preloader(mode, parent, id) {
    var parentDiv = $("#" + parent);
    var preloader_id = 'preloaderXle' + id;
    switch (mode) {
        case 'show':
            parentDiv.append('<div id="' + preloader_id + '" class="loaderXle"></div>');
            parentDiv.removeClass('LockOff').addClass('LockOn');
            $("#" + preloader_id).removeClass('LockOn').addClass('LockOff');
            break;
        case 'hide':
            $("#" + preloader_id).remove();
            parentDiv.removeClass('LockOn').addClass('LockOff');
            break;
    }

}

//-- вывести ошибки валидации к неправильным полям после аякса в загруженную форму
//-- formModel_id-модель мелкими буквами, errorsArray - массив ошибок
function showValidationErrors(formModel_id, errorsArray) {
    /*
    <div class="form-group field-orderprojectdepartment-name required has-error">
<label class="control-label" for="orderprojectdepartment-name">Найменування</label>
<input type="text" id="orderprojectdepartment-name" class="form-control" name="OrderProjectDepartment[name]" autofocus="" onchange="$('#orderprojectdepartment-name_gen').val(this.value);" tabindex="1" aria-required="true" aria-invalid="true">

<div class="help-block">Необхідно заповнити "Найменування".</div>
</div>
     */

    if (typeof errorsArray == 'object' ){
        var attrInput;
        var errorsBlock;
        var formGroup;
        $.each(errorsArray, function(index, value){
            formGroup = $(".field-" + formModel_id + "-" + index)[0];
            $(formGroup).addClass('has-error');
            attrInput = $("#" + formModel_id + "-" + index)[0];
            $(attrInput).attr("aria-invalid", "true");
            errorsBlock = $(attrInput).nextAll(".help-block")[0];
            $(errorsBlock).html(value);
        });
    } else {
        console.log(errorsArray)
    }

}
