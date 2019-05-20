/*
$(document).ready ( function(){
});
*/
(function ($) {
    $.fn.selectXle = function (selectId, _selectedItem, _selectedText, _clickFunctionBody) {
      //  console.log(selectId);
      //  console.log('#topItem_' + selectId);
        var clickFunction = new Function('item', _clickFunctionBody );

        $('#topItem_' + selectId).hover(
            function(){
                $('#items_' + selectId).show(500);
            },
            function(){
                $('#items_' + selectId).hide(500);
            });

        $('.listItem_' + selectId).hover(
            function(){
                console.log(this.id);

                $('#itemArea_'  + selectId + '_' + this.id).removeClass('itemArea').addClass('itemAreaActive');
            },
            function(){
                $('#itemArea_'  + selectId + '_' + this.id).removeClass('itemAreaActive').addClass('itemArea');
            });

        $('.listItem_' + selectId).on( 'click', function( event ){
            event.stopPropagation(); // остановка всех текущих JS событий
            event.preventDefault();  // остановка дефолтного события для текущего элемента - клик для <a> тега
            if (typeof clickFunction == 'function'){
                var arr =[];
                arr.push(clickFunction(this.dataset.id));
                arr.push(changeSelectedItem());
                collector.run(arr);
            }
        });

        function changeSelectedItem() {
            $('#itemArea_' + _selectedItem).show();
            $('#itemArea_' + _selectedItem).children().show();
            _selectedItem = this.id;
            _selectedText = this.innerText;
            $('#selectedItem').html(_selectedText);
            $(this).hide();

        }
    };
})(window.jQuery);


