$(document).ready(function() {

    function collapseCredentials() {

        var chosenEnvIndex = parseInt($("#CODESWHOLESALE_ENV").val());

        if(chosenEnvIndex == 0) {
            $("#CODESWHOLESALE_CLIENT_ID, #CODESWHOLESALE_CLIENT_SECRET").parents('.form-group').hide();
        } else {
            $("#CODESWHOLESALE_CLIENT_ID, #CODESWHOLESALE_CLIENT_SECRET").parents('.form-group').show();
        }

    }

    $("#CODESWHOLESALE_ENV").change(function(){
        $("#CODESWHOLESALE_CLIENT_ID, #CODESWHOLESALE_CLIENT_SECRET").val("");
        collapseCredentials();
    });

    collapseCredentials();
});





