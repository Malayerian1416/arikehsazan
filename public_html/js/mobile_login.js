$(document).ready(function (){
    $("#username").on("focus",function (){
        if(!$("#username_label").hasClass("active"))
            $("#username_label").addClass("active");
    }).on("blur",function (){
        if ($(this).val() === ""){
            if ($("#username_label").hasClass("active"))
                $("#username_label").removeClass("active");
        }
    });
    $("#password").on("focus",function (){
        if(!$("#password_label").hasClass("active"))
            $("#password_label").addClass("active")
    }).on("blur",function (){
        if ($(this).val() === ""){
            if ($("#password_label").hasClass("active"))
                $("#password_label").removeClass("active");
        }
    });
    if ($("#username").css("background") !== "#f5f5f5"){
        if(!$("#username_label").hasClass("active"))
            $("#username_label").addClass("active");
    }
    if ($("#password").css("background") !== "#f5f5f5"){
        if(!$("#password_label").hasClass("active"))
            $("#password_label").addClass("active");
    }

});
let app = new Vue({
    el: "#app",
    data: {
        loading_window_active:false,
    }
});
