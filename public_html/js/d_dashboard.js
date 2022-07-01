$(document).ready(function (){
    // $(document).on('click', '.dropdown-menu', function (e) {
    //     e.stopPropagation();
    // });
    $(document).on("keyup",function (e){
         if (e.keyCode === 70 && e.shiftKey) {$("#menu_search").modal("show");$("#menu_items_search_key").focus()}
    });
    if ($(window).width() < 992) {
        $('.dropdown-menu a').click(function(e){
            e.preventDefault();
            if($(this).next('.submenu').length){
                $(this).next('.submenu').toggle();
            }
            $('.dropdown').on('hide.bs.dropdown', function () {
                $(this).find('.submenu').hide();
            })
        });
    }
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
    if ($(".persian_date_constant").length > 0)
        $(".persian_date_constant").persianDatepicker({
            months: ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور", "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"],
            dowTitle: ["شنبه", "یکشنبه", "دوشنبه", "سه شنبه", "چهارشنبه", "پنج شنبه", "جمعه"],
            shortDowTitle: ["ش", "ی", "د", "س", "چ", "پ", "ج"],
            showGregorianDate: !1,
            persianNumbers: !0,
            formatDate: "YYYY/0M/0D",
            selectedBefore: !0,
            selectedDate: null,
            startDate: null,
            endDate: null,
            alwaysShow: !0,
            selectableYears: null,
            selectableMonths: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            cellWidth: 55, // by px
            cellHeight: 55, // by px
            fontSize: 13, // by px
            isRTL: !0,
            calendarPosition: {
                x: 20,
                y: 0,
            },
        });
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
    $(".masked").each(function (){
        $(this).mask($(this).data('mask'));
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
