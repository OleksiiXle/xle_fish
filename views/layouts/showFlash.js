var flashMessage = '';
$.each(_fms, function (type, message) {
    if (type == 'success'){
        flashMessage += '<div class="alert alert-success alert-dismissible">' + message +'</div>'
    } else {
        flashMessage += '<div class="alert alert-danger alert-dismissible">' + message +'</div>'
    }
    $("#flashMessage").show();
    $("#flashMessage").html(flashMessage);
    setTimeout(function() {
        $("#flashMessage").hide();
    }, 3000);
});
