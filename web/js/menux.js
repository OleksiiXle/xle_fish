
function clickItemFunction(tree_id, selected_id) {
   // alert('clickItemFunction ' + tree_id + ' ' + selected_id);
    $.ajax({
        url: '/menux/get-menux',
        type: "GET",
        data: {
            'id' : selected_id
        },
        beforeSend: function() {
            // preloader('show', 'mainContainer', 0);
          //  preloader('show', 'mainContainer', 0);
        },
        complete: function(){
            // $("#preloader" + _id).hide();
          //  preloader('hide', 'mainContainer', 0);
        },
        success: function(response){
            $("#menuInfo").html(response);
        },
        error: function (jqXHR, error, errorThrown) {
            console.log(error);
            console.log(errorThrown);
            console.log(jqXHR);
         //   errorHandler(jqXHR, error, errorThrown);
        }
    });

}
