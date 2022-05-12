$(document).ready(function (){
    document.addEventListener("popstate",function(e){
        $(".loading_window").hide();
    });
    document.addEventListener("unload",function(e){
        $(".loading_window").hide();
    });
    document.addEventListener("blur",function(e){
        $(".loading_window").hide();
    });
    document.addEventListener("pagehide",function(e){
        $(".loading_window").hide();
    });
    if ($(".number_format").length > 0)
        new AutoNumeric.multiple('.number_format',['integer',{'digitGroupSeparator':',','watchExternalChanges':true}]);
    if ($('.number_format_dec').length > 0)
        new AutoNumeric.multiple('.number_format_dec',['float',{'digitGroupSeparator':'','watchExternalChanges':true}]);
    if ($(".persian_date").length > 0)
        $(".persian_date").persianDatepicker();
    $(".dropdown-btn").click(function (){
        if ($(this).hasClass("menu_dropdown_active")){
            $(this).removeClass("menu_dropdown_active");
            $(this).next().removeClass("active");
        }
        else {
            $(this).toggleClass("menu_dropdown_active");
            $(this).next().toggleClass("active");
        }
    });
    if ($(".alert_container").length !== 0){
        $(".alert_container").fadeTo(2000, 500).slideUp(500, function () {
            $("#success-alert").slideUp(500);
        });
    }
    if ($(".select_picker").length > 0)
        $(".select_picker").selectpicker();
    $('[data-toggle="tooltip"]').tooltip();
    $(".search_button").click(function (){
        if ($("#search_modal").length)
            $("#search_modal").modal('show');
    });
    $('input[type="text"]').click(function (e){
        let self = $(this);
        if (self.val() && e.ctrlKey) {
            let mask = self.data("mask");
            let string = self.unmask().val();
            string = string.replace(/[()\-_!@#$%^.,\/]/g, '');
            navigator.clipboard.writeText(string).then(function () {
                if (mask)
                    self.mask(mask["mask"]);
                alerify.notify('متن مورد نظر در حافظه کپی شد!', 'copy', "2");
            }, function (err) {
                alerify.warning("مرورگر شما از کپی در حافظه پشتیبانی نمیکند!");
            });
        }
    });
    $(".doc_expand").click(function (){
        if ($(this).parent().parent().hasClass("active")) {
            $(this).parent().parent().removeClass("active");
            $(".doc_expand_icon").removeClass("fa-arrow-alt-circle-down").toggleClass("fa-arrow-alt-circle-left");
        }
        else {
            $(this).parent().parent().toggleClass("active");
            $(".doc_expand_icon").removeClass("fa-arrow-alt-circle-left").toggleClass("fa-arrow-alt-circle-down")
        }
    });
    $(".index_form_submit_button").click(function (e){e.stopPropagation();});
    $(".hide_section_container").on("click",function (){
        $(".hide_section").toggleClass("active");
        $(".table-responsive").toggleClass("smaller");
        $(".hide_section_icon").hasClass("fa-plus-square") ? $(".hide_section_icon").removeClass("fa-plus-square").addClass("fa-minus-square") : $(".hide_section_icon").removeClass("fa-minus-square").addClass("fa-plus-square");
    });
});

