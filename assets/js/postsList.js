/*
$(document).ready ( function(){
});
*/

var selectedImage;
var selected;

$('#topMedia_' + selectId).hover(
    function(){
        $('#medias_' + selectId).show(500);
    },
    function(){
        $('#medias_' + selectId).hide(500);
    });

$('.mediaItem_' + selectId).hover(
    function(){
        console.log(this.id);

        $('#mediaArea_'  + selectId + '_' + this.id).removeClass('mediaArea').addClass('mediaAreaActive');
    },
    function(){
        $('#mediaArea_'  + selectId + '_' + this.id).removeClass('mediaAreaActive').addClass('mediaArea');
    });

$('.mediaItem_' + selectId).on( 'click', function( event ){
    event.stopPropagation(); // остановка всех текущих JS событий
    event.preventDefault();  // остановка дефолтного события для текущего элемента - клик для <a> тега
    if (typeof clickFunction == 'function'){
        var arr =[];
        arr.push(clickFunction(this.dataset.id));
        arr.push(changeSelectedItem());
        collector.run(arr);
    } else {
        changeSelectedItem();
    }
});

function changeSelectedItem() {
    $('#mediaArea_' + _selectedItem).show();
    $('#mediaArea_' + _selectedItem).children().show();
    _selectedItem = this.id;
    _selectedText = this.innerText;
    $('#selectedItem').html(_selectedText);
    $(this).hide();

}

