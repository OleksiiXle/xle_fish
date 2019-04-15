const ITEM_CSS = 'item_css';
const ITEM_ACTIVE_CSS = 'item_active_css';
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
    selected_id : 0,

    init: function(menu_id){
        this.tree_id = menu_id;

        this.item_id = menu_id + '_item_';
        this.icon_id = menu_id + '_icon_';
        this.li_id   = menu_id + '_li_';
        this.ul_id   = menu_id + '_ul_';

        this.drawDefaultTree(this.tree_id);
        var what = this;
        $("#" + this.tree_id).on("click", "." + ICON_CSS, function () {
            what.clickIcon(this);
        });
        $("#" + this.tree_id).on("click", "." + ITEM_CSS, function () {
            what.clickItem(this);
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
    clickIcon: function (parent) {
        //   console.log(parent.innerHTML);
        var parent_id = parent.dataset.id;
        var that = this;
        switch (parent.innerHTML){
            case ICON_CLOSE:
                parent.innerHTML = ICON_OPEN;
                $.ajax({
                    url: '/wcontroller/menux-get-children',
                    type: "POST",
                    data: {
                        'id' : parent_id,
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
                            that.selectedIdChange(parent_id);
                        } else {
                            objDump(response['data']);
                        }
                    },
                    error: function (jqXHR, error, errorThrown) {
                        errorHandler(jqXHR, error, errorThrown)            }
                });
                break;
            case ICON_OPEN:
                parent.innerHTML = ICON_CLOSE;
                $("#" + that.ul_id + parent_id).remove();
                that.selectedIdChange(parent_id);
                break;
        }
    },

    clickItem: function (item) {
        // alert('item');
        //  console.log(item.dataset);
        this.selectedIdChange(item.dataset.id);
    },

    //-- изменение текущего выбранного элемента
    selectedIdChange: function (new_id) {
        var that = this;
        //  $.post(_urlSetConserve, {'id' : new_id, 'type' : type, 'staffOrder_id': _treeParams[tree_id]['staffOrder_id'], 'tree_id' : tree_id});

        var old_selected_id = that.selected_id;
        var new_selected_id = new_id;
        var oldNode =$("#" + that.item_id + old_selected_id);
        var newNode =$("#" + that.item_id + new_selected_id);
        //  console.log(that.selected_id);
        //   console.log(new_id);
        //  console.log("#" + that.item_id + old_selected_id);
        //   console.log("#" + that.item_id + new_selected_id);
        //  console.log(oldNode);
        //   console.log(newNode);


//   oldNode.css("font-weight",'normal' );
        //   newNode.css("font-weight",'600');
        oldNode.removeClass(ITEM_ACTIVE_CSS).addClass(ITEM_CSS);
        newNode.removeClass(ITEM_CSS).addClass(ITEM_ACTIVE_CSS);
        that.selected_id = new_selected_id;

        var container = $("#" + that.tree_id),
            scrollTo = $('#' + new_selected_id);
        if (container.length > 0 && scrollTo.length > 0){
            //    console.log(container);
            //    console.log(scrollTo);
            container.stop().animate({
                scrollTop: scrollTo.offset().top -
                container.offset().top +
                container.scrollTop() - 200
            });
        }
        //  console.log(_treeParams[tree_id]);

        return true;

    }
};
/*
$(document).ready ( function(){
    var tree1 = Object.create(MENU_TREE);
    tree1.init(_menu_id);
});
*/

