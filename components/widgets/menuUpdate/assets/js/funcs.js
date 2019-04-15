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
