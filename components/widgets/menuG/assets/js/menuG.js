//*****************************************
$(document).ready ( function(){
    /*
    $(".ulMenuX").each(function (index, el){
        if ($(el).parents('ul').length > 1){
            $(el).hide();
        }
    });
    */
    console.log(_tree['135']['children']['0']);
    console.log(_tree['135'].children);
    console.log(_tree['135'].item);

});

//--действия по клику
function clickAction(item) {
    ///  console.log(icon);
    var ul = $(item).siblings('ul');
    //   console.log(ul);
    if (ul.css('display') == 'block'){
        ul.hide();
    } else {
        ul.show();
    }
}
