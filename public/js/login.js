$(document).ready(function (){
    $("#submit").click(function () {
        $(".button_text").hide();
        $(this).prop("disabled",true);
        $(".button_loading").css("display","inline-block");
        $("#login_form").submit();
    });
});
