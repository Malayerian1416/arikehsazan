$(document).ready(function (){
    const owl_elements = $(".owl-carousel").owlCarousel({
        rtl: true,
        nav: false,
        dots: false,
        loop: true,
        autoplay: 1000,
        autoplayHoverPause: false,
        autoplaySpeed: 2000,
        autoplayTimeout: 10000,
        responsive: {
            0: {
                items: 1
            },
            650: {
                items: 1
            },
            900: {
                items: 1
            },
            1000: {
                items: 1
            }
        }

    });
    $(window).on("resize",function (){
        owl_elements.trigger('refresh.owl.carousel');
    });
    if ($(".number_format").length > 0)
        new AutoNumeric.multiple('.number_format',['integer',{'digitGroupSeparator':',','watchExternalChanges':true}]);
    if ($('.number_format_dec').length > 0)
        new AutoNumeric.multiple('.number_format_dec',['float',{'digitGroupSeparator':'','watchExternalChanges':true}]);
    if ($(".persian_date").length > 0)
        $(".persian_date").persianDatepicker({formatDate: "YYYY/0M/0D"});
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
    if ($(".select_picker").length > 0) {
        $(".select_picker").selectpicker();
    }
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
});
