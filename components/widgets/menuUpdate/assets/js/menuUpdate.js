//*****************************************

/*
$(document).ready ( function(){
    $("#start").html('S T A R T');

});
*/
//-- todo слелать инициализацию переменных,если их еще нет
const ITEM_CSS = 'item_css';
const ICON_CSS = 'icon_css';
const LI_CSS = 'li_css';
const UL_CSS = 'ul_css';
const ICON_OPEN  =  '<span class="glyphicon glyphicon-folder-open"></span>';
const ICON_CLOSE = '<span class="glyphicon glyphicon-folder-close"></span>';


var MENU_TREE = {
    tree_id: null,
    item_id : null,
    icon_id : null,
    li_id : null,
    ul_id : null,

    init: function(menu_id){
        this.tree_id = menu_id;

        this.item_id = menu_id + '_item_';
        this.icon_id = menu_id + '_icon_';
        this.li_id   = menu_id + '_li_';
        this.ul_id   = menu_id + '_ul_';

        this.drawDefaultTree(this.tree_id);
        var what = this;
        $("#" + this.tree_id).on("click", "." + ICON_CSS, function () {
            alert('icon');
            console.log(this.dataset);
            what.drawChildren(this.dataset.id);
        });
    },

    //-- возвращает строку для рисования наименования с закрытой иконкой, если есть потомки, если нет - без иконки
    getItem: function (data) {
        var that = this;
        var result =
            '<li class="' + LI_CSS + '"' +
            ' id="' + this.li_id + data['id'] +  '"' +
            ' data-tree_id="' + this.tree_id + '" ' +
            ' data-id="' + data['id'] + '"' +
            ' data-parent_id="' + data['parent_id'] + '">';
        if (data['hasChildren']){
            result = result +
                '<a class="' + ICON_CSS + '"' +
                ' id="' + this.icon_id + data['id'] +  '"' +
                ' data-tree_id="' + this.tree_id + '"' +
                ' data-id="' + data['id'] + '" ' +
                ' data-parent_id="' + data['parent_id'] + '"' +
               // ' onClick="' + that.drawChildren(this.dataset.id)+ ';"'
                '>' +
                ICON_CLOSE  +
                '</a>  ';
        }
        result = result +
            '<a class="' + ITEM_CSS + '"' +
            ' id="' + this.item_id + data['id'] +  '"' +
            ' data-tree_id="' + this.tree_id + '" ' +
            ' data-id="' + data['id'] + '" ' +
            ' data-parent_id="' + data['parent_id'] +'" ' +
           // ' onClick="' + that.clickItem(this) + ';"'  +
            '> ' +
            data['name'] +
            '</a></li>' ;
        return result;
    },

    //-- рисует дефолтное дерево (вершины, у которых нет парентов
    drawDefaultTree: function (tree_id) {
        var that = this;
        $.ajax({
            url: '/wcontroller/menux-get-default-tree',
            type: "POST",
            data: {'_csrf':_csrfT},
            dataType: 'json',
            beforeSend: function() {
                // preloader('show', 'mainContainer', 0);
            },
            complete: function(){
                //  preloader('hide', 'mainContainer', 0);
            },
            success: function(response){
                if (response['status']) {
               //     console.log(response);
                    var firstDiv = $("#" + tree_id);
                    $.each(response['data'], function(index, value){
                        $(firstDiv).append(that.getItem(value))
                    });
                } else {
                    objDump(response['data']);
                }
            },
            error: function (jqXHR, error, errorThrown) {
                errorHandler(jqXHR, error, errorThrown)            }
        });
    },

    //-- рисует потомков первого уровня узлу parent_id
    drawChildren: function (parent_id) {
        var that = this;
        $.ajax({
            url: '/wcontroller/menux-get-children',
            type: "POST",
            data: {
                'id' :parent_id,
                '_csrf':_csrfT
            },
            dataType: 'json',
            beforeSend: function() {
                // preloader('show', 'mainContainer', 0);
            },
            complete: function(){
                //  preloader('hide', 'mainContainer', 0);
            },
            success: function(response){
                if (response['status']) {
                    var children = response['data'];
                    if (children.length > 0){
                        //--найти родителя в этом дереве
                        var parent = $("#" + that.item_id + parent_id);
                    //    console.log(parent);
                        //-- добавить после него ul
                        parent.after(
                            '<ul class="'+ UL_CSS + '"' +
                            ' id="' + that.ul_id + parent_id +  '"' +
                            '></ul>'
                        );
                        //-- получить єтот ul
                        var parent_ul = $("#" + that.ul_id + parent_id);
                        //-- добавить в ul потомков
                        $.each(children, function(index, value){
                            $(parent_ul).append(that.getItem(value))
                        });
                    }
                } else {
                    objDump(response['data']);
                }
            },
            error: function (jqXHR, error, errorThrown) {
                errorHandler(jqXHR, error, errorThrown)            }
        });
    },

    clickItem: function (item) {
        alert('item');
    },



};



/*
jQuery(document).ready(function(){
    MENU_TREE.init();
});
*/
//console.log(_menu_id);
//console.log(_menuData);
//MENU_TREE.init(_menu_id ,_menuData);

var tree1 = Object.create(MENU_TREE);
tree1.init(_menu_id ,_menuData);


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


