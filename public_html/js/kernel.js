const extra_work_line = {
    template: `<tr>
    <td style="width: 60%"><input type="text" class="form-control text-center iran_yekan" name="extra_work_desc[]"></td>
    <td style="width: 35%"><input type="text" class="form-control text-center iran_yekan number_format" name="extra_work_amount[]" v-on:input="input_value"></td>
    <td><i class="fa fa-trash remove_icon" v-on:click="remove_from_extra_work_table"></i></td>
    </tr>`,
    mounted() {
        new AutoNumeric(this.$el.children[1].children[0],['integer',{'digitGroupSeparator':','}]);
    },
    methods: {
        remove_from_extra_work_table(){
            this.$parent.$data["extra_inline"].pop();
            this.$parent.new_invoice_total_amount_process();
        },
        input_value(){
            this.$parent.new_invoice_total_amount_process()
        }
    }
};
const deduction_work_line = {
    template: `<tr>
    <td style="width: 60%"><input type="text" class="form-control text-center iran_yekan" name="deduction_work_desc[]"></td>
    <td style="width: 35%"><input type="text" class="form-control text-center iran_yekan number_format" name="deduction_work_amount[]" v-on:input="input_value"></td>
    <td><i class="fa fa-trash remove_icon" v-on:click="remove_from_deduction_work_table"></i></td>
    </tr>`,
    mounted() {
        new AutoNumeric(this.$el.children[1].children[0],['integer',{'digitGroupSeparator':','}]);
    },
    methods: {
        remove_from_deduction_work_table(){
            this.$parent.$data["deduction_inline"].pop();
            this.$parent.new_invoice_total_amount_process();
        },
        input_value(){
            this.$parent.new_invoice_total_amount_process();
        }
    }
};
const app = new Vue({
    el:"#app",
    data:{
        account_info_active: false,
        loading_window_active: true,
        button_loading: false,
        button_not_loading: true,
        live_data_adding_modal_title: "",
        live_data_adding_label: "",
        live_data_adding_value: "",
        live_adding_data_type: "",
        live_adding_data_content: {type: "", title: ""},
        response_items: [],
        edit_input_data: "",
        edit_select_data: "",
        related_data_select: typeof related_data_select === 'undefined' ? null : related_data_select,
        related_data_select_child: typeof related_data_select_child === 'undefined' ? null : related_data_select_child,
        related_data_search_loading: false,
        related_data_search_loading_child: false,
        searches: typeof searches === 'undefined' ? [] : searches,
        searches_child: typeof searches_child === 'undefined' ? [] : searches_child,
        parent_id: typeof parent_id === 'undefined' ? '' : parent_id,
        disabled: true,
        final_starter: [],
        final_inductor: [],
        final_finisher: '',
        contract_branch: '',
        contract_category: '',
        contractor_name: '',
        invoices_count: '',
        new_invoice_quantity: typeof quantity === 'undefined' ? '0' : quantity,
        new_invoice_frame: false,
        new_invoice_unit: '',
        new_invoice_amount: typeof amount === 'undefined' ? '0' : amount,
        new_invoice_total_amount: typeof total_amount === 'undefined' ? '0' : total_amount,
        extra_inline: [],
        deduction_inline: [],
        new_invoice_comment: '',
        invoice_payment_offer: typeof payment_offer === 'undefined' ? '0' : payment_offer,
        invoice_payment_offer_percent: typeof payment_offer_percent === 'undefined' ? '0' : payment_offer_percent,
        payment_offer_disabled: typeof payment_offer_disabled === 'undefined' ? true : payment_offer_disabled,
        bank_name: '',
        bank_card_number: '',
        bank_account_number: '',
        bank_sheba_number: '',
        bank_items: typeof bank_already_information === 'undefined' ? [] : bank_already_information,
        check_serial: '',
        check_sayyadi: '',
        check_start: '',
        check_end: '',
        check_quantity: '',
        check_items: typeof check_already_information === "undefined" ? [] : check_already_information,
        current_check_number: '',
        deposit_kind_number: '',
        sidebar_visibility: false,
        new_invoice_automation_show:  typeof new_invoice_automation_show_already !== "undefined",
        new_invoice_automation_text: typeof new_invoice_automation_text_already !== "undefined" ? new_invoice_automation_text_already : 0,
        new_worker_payment_automation_show: typeof new_worker_automation_show_already !== "undefined",
        new_worker_payment_automation_text: typeof new_worker_automation_text_already !== "undefined" ? new_worker_automation_text_already : 0,
        new_leave_automation_show:  typeof new_leave_automation_show_already !== "undefined",
        new_leave_automation_text: typeof new_leave_automation_text_already !== "undefined" ? new_leave_automation_text_already : 0,
        invoice_total_previous_quantity: 0,
        linklist:'',
        notification_permission: false,
        notification_text:'',
        invoice_detail_head_role:[],
        location_id: '',
        contract_info: [],
        contract_info_total: {"total_quantity":'0',"total_sum":'0',"total_payed":'0',"total_remain":'0',"unit":''},
        leave_type_show: false,
        leave_type: '',
        leave_type_hidden:'',
        leave_date: '',
        departure_time: '',
        arrival_time: '',
        leave_items: [],
        worker_comment:''
    },
    mounted() {
        const self = this;
        this.loading_window_active = false;
        this.linkList = document.getElementsByTagName('a');
        for( let i=0; i < this.linkList.length; i++ ) {
            if (!$(this.linkList[i]).hasClass("print_anchor"))
                this.linkList[i].onclick = this.linkAction;
        }
        if ('serviceWorker' in navigator)
            navigator.serviceWorker.register(`/serviceworker.js`, {scope: '/'});
        if (Notification.permission !== "granted" && Notification.permission !== "denied") {
            if ('serviceWorker' in navigator && 'PushManager' in window) {
                Notification.requestPermission().then(function (permission) {
                    navigator.serviceWorker.ready.then(registration => {
                        const title = 'اریکه سازان';
                        const options = {
                            body: 'سیستم پیام رسانی با موفقیت فعال شد',
                            icon: `${window.location.protocol}//${window.location.host}/img/new_notification.png`,
                            tag: 'renotify',
                            renotify: true,
                        };
                        registration.showNotification(title, options).then(() => {});
                    });
                });
            } else
                this.notification_text = "متاسفانه مرورگر دستگاه شما از سیستم پیام رسانی (Notification) پشتیبانی نمی کند";
        }
        Echo.private(`invoice_automation.${role_id}`)
            .listen('InvoiceEvent', (notification) => {
                navigator.serviceWorker.ready.then(registration => {
                    const title = 'اریکه سازان';
                    const options = {
                        body: notification.message,
                        icon: `${window.location.protocol}//${window.location.host}/img/new_notification.png`,
                        data: notification,
                        tag: 'renotify',
                        badge: '/img/notification_badge.png',
                        renotify: true,
                    };
                    registration.showNotification(title, options).then(() => {
                        let notification = document.getElementsByClassName("badge");
                        if (notification.length) {
                            self.new_invoice_automation_text = self.new_invoice_automation_text + 1;
                            self.new_invoice_automation_show = true;
                        }
                    });
                });
            });
        Echo.private(`worker_automation.${role_id}`)
            .listen('WorkerEvent', (notification) => {
                navigator.serviceWorker.ready.then(registration => {
                    const title = 'اریکه سازان';
                    const options = {
                        body: notification.message,
                        icon: `${window.location.protocol}//${window.location.host}/img/new_notification.png`,
                        data: notification,
                        tag: 'renotify',
                        badge: '/img/notification_badge.png',
                        renotify: true,
                    };
                    registration.showNotification(title, options).then(() => {
                        let notification = document.getElementsByClassName("badge");
                        if (notification.length) {
                            self.new_worker_payment_automation_text = self.new_worker_payment_automation_text + 1;
                            self.new_worker_payment_automation_show = true;
                        }
                    });
                });
            });
        Echo.private(`leave_automation.${role_id}`)
            .listen('LeaveEvent', (notification) => {
                navigator.serviceWorker.ready.then(registration => {
                    const title = 'اریکه سازان';
                    const options = {
                        body: notification.message,
                        icon: `${window.location.protocol}//${window.location.host}/img/new_notification.png`,
                        data: notification,
                        tag: 'renotify',
                        badge: '/img/notification_badge.png',
                        renotify: true,
                    };
                    registration.showNotification(title, options).then(() => {
                        let notification = document.getElementsByClassName("badge");
                        if (notification.length) {
                            self.new_leave_automation_text = self.new_leave_automation_text + 1;
                            self.new_leave_automation_show = true;
                        }
                    });
                });
            });
        if ($("#attendance_map").length){
            let map = L.map('attendance_map').setView([36.31559,59.56796], 12);
            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                }).addTo(map);
            const self = this
            let options = {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            };
            self.loading_window_active = true;
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    L.marker([position.coords.latitude,position.coords.longitude]).addTo(map);
                    map.flyTo([position.coords.latitude, position.coords.longitude], 16)
                    self.loading_window_active = false;
                },positionError => {
                    self.loading_window_active = false;
                    switch (positionError.code) {
                        case 1:
                            bootbox.alert("اجازه دسترسی به سیستم ناوبری صادر نشده است");
                            break
                        case 2:
                            bootbox.alert("موقعیت مکانی شما مشخص نمی باشد");
                            break
                        case 3:
                            bootbox.alert("زمان سنجش موقعیت مکانی شما به اتمام رسیده است");
                            break
                    }
                },options);
            }
        }
        const installApp = document.getElementById('installApp');
        const denyApp = document.getElementById('denyApp');
        let deferredPrompt;
        if (installApp) {
            window.addEventListener('beforeinstallprompt', (e) => {
                $('.install-app-btn-container').css("display", "flex");
                deferredPrompt = e;
            });
            denyApp.addEventListener('click', () => {
                $('.install-app-btn-container').hide();
            });
            installApp.addEventListener('click', async () => {
                if (deferredPrompt !== null) {
                    deferredPrompt.prompt();
                    const {outcome} = await deferredPrompt.userChoice;
                    if (outcome === 'accepted')
                        deferredPrompt = null;
                    else
                        $('.install-app-btn-container').hide();
                }
            });
        }
        if ($(".select_picker").length)
            $(".select_picker").selectpicker('refresh');
        window.addEventListener("resize",self.resize_window);
    },
    beforeMount() {
        if ($("#invoice_automation_quantity").length && $("#invoice_automation_amount").length){
            this.new_invoice_quantity = parseFloat($("#invoice_automation_quantity").val().replace(/,/g,''));
            this.new_invoice_amount = parseFloat($("#invoice_automation_amount").val().replace(/,/g,''));
            this.new_invoice_total_amount_process();
        }
    },
    watch: {
        searches: function(){
            this.$nextTick(function () {
                $('.select_picker').selectpicker('refresh').selectpicker('render');
            });
        },
        searches_child: function(){
            this.$nextTick(function () {
                $('.select_picker').selectpicker('refresh').selectpicker('render');
            });
        },
        new_invoice_amount: function (new_value){
            const self = this;
            const edited_value = new_value.toString().replace(/,/g,'');
            this.$nextTick(function (){
                self.new_invoice_amount = edited_value;
            });
        },
        invoice_payment_offer: function (new_value){
            const self = this;
            const edited_value = new_value.toString().replace(/,/g,'')
            this.$nextTick(function (){
                self.invoice_payment_offer = edited_value;
            });
        },
        bank_items: function (){
            this.$nextTick(function () {
                $(".masked_card").mask("0000-0000-0000-0000");
                $(".masked_account").mask("000000000000000000000000000000");
                $(".masked_sheba").mask("IR00-0000-0000-0000-0000-0000-00");
            });
        },
    },
    methods: {
        account_information_show(event) {
            event.stopPropagation();
            (this.account_info_active === false) ? this.account_info_active = true : this.account_info_active = false;
        },
        account_information_open() {
            (this.account_info_active === true) ? this.account_info_active = false : this.account_info_active = false;
        },
        submit_form(e) {
            e.preventDefault();
            let form = e.target;
            let self = this;
            let message;
            switch (form.dataset.type){
                case "create":{
                    message = "آیا برای ارسال و ذخیره اطلاعات اطمینان دارید؟";
                    break;
                }
                case "update":{
                    message = "آیا برای ارسال ویرایش اطلاعات اطمینان دارید؟";
                    break;
                }
                case "delete":{
                    message = "آیا برای حذف اطلاعات اطمینان دارید؟";
                    break;
                }
                case "active":{
                    let status_code = form.dataset.status;
                    let status = '';
                    switch (status_code) {
                        case "0": {
                            status = "فعال";
                            break
                        }
                        case "1": {
                            status = "غیر فعال";
                            break
                        }
                    }
                    message = `آیا برای ${status} کردن این رکورد اطمینان دارید؟`;
                    break;
                }
                case "send":{
                    message = "آیا برای تایید و ارسال اطلاعات اطمینان دارید؟";
                    break;
                }
                case "refer":{
                    message = "آیا برای ارجاع اطلاعات اطمینان دارید؟";
                    break;
                }
                case "approve":{
                    message = "آیا برای تایید اطلاعات و اتمام جریان اطمینان دارید؟";
                    break;
                }
                case "reject":{
                    message = "آیا برای عدم تایید اطلاعات و اتمام جریان اطمینان دارید؟";
                    break;
                }
                case "pay":{
                    message = "آیا برای تایید اطلاعات و پرداخت اطمینان دارید؟";
                    break;
                }
                case "attendance":{
                    message = $("#type").val() === "presence" ? "آیا برای ثبت ورود کارمند اطمینان دارید؟":"آیا برای ثبت خروج کارمند اطمینان دارید؟";
                    break;
                }
            }
            bootbox.confirm({
                message: message,
                closeButton: false,centerVertical: true,
                buttons: {
                    confirm: {
                        label: 'بله',
                        className: 'btn-success',
                    },
                    cancel: {
                        label: 'خیر',
                        className: 'btn-danger',
                    }
                },
                callback: function (result) {
                    if (result === true) {
                        self.loading_window_active = true;
                        bootbox.hideAll();
                        $(".masked").length > 0 ? $(".masked").unmask() : "";
                        $(".number_format").length > 0 ? AutoNumeric.getAutoNumericElement('.number_format').formUnformat() : '';
                        self.button_loading = true;
                        self.button_not_loading = false;
                        form.submit();
                    }
                }
            }).find('.modal-content').css({'background-color': '#343a40', color: '#ffffff'});
        },
        submit_attendance_register_form(e) {
            let self = this;
            let form = e.target;
            let options = {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            };
            e.preventDefault();
            let message = $("#type").val() === "presence" ? "آیا برای ثبت ورود کارمند اطمینان دارید؟":"آیا برای ثبت خروج کارمند اطمینان دارید؟"
            bootbox.confirm({
                message: message,
                closeButton: false,centerVertical: true,
                buttons: {
                    confirm: {
                        label: 'بله',
                        className: 'btn-success',
                    },
                    cancel: {
                        label: 'خیر',
                        className: 'btn-danger',
                    }
                },
                callback: function (result) {
                    if (result === true) {
                        self.loading_window_active = true;
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(position => {
                                axios.post("/get_geo_json", {"location_id" : self.location_id})
                                    .then(function (response) {
                                        self.loading_window_active = false;
                                        if (response.data !== null) {
                                            const in_position = d3.geoContains(response.data,[position.coords.longitude, position.coords.latitude]);
                                            if (in_position){
                                                bootbox.hideAll();
                                                self.button_loading = true;
                                                self.button_not_loading = false;
                                                self.loading_window_active = true;
                                                form.submit();
                                            }
                                            else {
                                                self.loading_window_active = false;
                                                bootbox.alert("در موقعیت انتخاب شده نمی باشید");
                                            }
                                        }
                                    }).catch(function (){self.loading_window_active = false;});
                            },positionError => {
                                self.loading_window_active = false;
                                switch (positionError.code) {
                                    case 1:
                                        bootbox.alert("اجازه دسترسی به سیستم ناوبری صادر نشده است");
                                        break
                                    case 2:
                                        bootbox.alert("موقعیت مکانی شما مشخص نمی باشد");
                                        break
                                    case 3:
                                        bootbox.alert("زمان سنجش موقعیت مکانی شما به اتمام رسیده است");
                                        break
                                }
                            },options);
                        }
                        else
                            bootbox.alert("مرورگر شما از سیستم ناوبری پشتیبانی نمی کند");
                    }
                }
            }).find('.modal-content').css({'background-color': '#343a40', color: '#ffffff'});
        },
        live_data_adding(e) {
            let type = e.target.dataset.type;
            switch (type) {
                case "new_contract_category": {
                    this.live_data_adding_modal_title = "ایجاد سرفصل پیمان جدید";
                    this.live_data_adding_label = "عنوان سرفصل";
                    this.live_adding_data_type = "new_contract_category";
                    break;
                }
                case "new_unit": {
                    this.live_data_adding_modal_title = "ایجاد واحد شمارش جدید";
                    this.live_data_adding_label = "واحد شمارش";
                    this.live_adding_data_type = "new_unit";
                    break;
                }
                case "new_ability_category": {
                    this.live_data_adding_modal_title = "ایجاد سرفصل دسترسی جدید";
                    this.live_data_adding_label = "عنوان سرفصل";
                    this.live_adding_data_type = "new_ability_category";
                    break;
                }
            }
            $("#live_data_adding_modal").modal("show");
        },
        live_data_adding_submit() {
            let self = this;
            if (this.live_data_adding_value.length > 0) {
                bootbox.confirm({
                    message: ` آیا برای ${self.live_data_adding_modal_title} اطمینان دارید؟`,
                    closeButton: false,centerVertical: true,
                    buttons: {
                        confirm: {
                            label: 'بله',
                            className: 'btn-success',
                        },
                        cancel: {
                            label: 'خیر',
                            className: 'btn-danger',
                        }
                    },
                    callback: function (result) {
                        if (result === true) {
                            self.live_adding_data_content.type = self.live_adding_data_type;
                            self.live_adding_data_content.title = self.live_data_adding_value;
                            bootbox.hideAll();
                            self.loading_window_active = true;
                            axios.post("/live_adding_data", self.live_adding_data_content)
                                .then(function (response) {
                                    self.loading_window_active = false;
                                    alerify.success("ارسال و ذخیره سازی با موفقیت انجام شد");
                                    $("#live_data_adding_modal").modal("hide");
                                    if (response.data.length > 0) {
                                        $(document).find("select").each(function () {
                                            let target = $(this);
                                            if ($(this).data("type") === self.live_adding_data_type) {
                                                target.html('');
                                                switch (self.live_adding_data_type) {
                                                    case "new_contract_category": {
                                                        target.append(`<option value='0'>ندارد</option>`);
                                                        break;
                                                    }
                                                }
                                                response.data.forEach(function (item) {
                                                    target.append(`<option value='${item.id}'>${item.title}</option>`);
                                                });
                                                let new_item = target.find("option:last").val();
                                                target.selectpicker('val', new_item);
                                                target.selectpicker('destroy');
                                                target.selectpicker();
                                            }
                                        });
                                    }
                                }).catch(function (error) {
                                self.loading_window_active = false;
                                if (error.response) {
                                    console.log(error.response.data);
                                    console.log(error.response.status);
                                    console.log(error.response.headers);
                                }
                            });
                        }
                    }
                }).find('.modal-content').css({'background-color': '#343a40', color: '#ffffff'});
            }
        },
        static_data_add_modal() {
            $("#data_adding_modal").modal("show");
        },
        static_data_edit_modal(e) {
            let target = e.target;
            $("#update_form").prop("action", target.dataset.route);
            this.edit_input_data = target.dataset.value;
            if (target.dataset.extra_value) {
                this.edit_select_data = target.dataset.extra_value;
            }
            $("#data_editing_modal").modal("show");
        },
        main_route_change() {
            let main = $("#main");
            main.find('option').remove();
            $("#menu_action_id option:selected").map(function () {
                main.append(`<option value=${$(this).val()}>${$(this).text()}</option>`);

            });
            main.selectpicker('refresh');
        },
        popup_file_browser(e) {
            $(e.target).closest('div').find('input[type="file"]').click();
        },
        file_browser_change(e) {
            let valid_ext = ["pdf", "doc", "docx", "jpg", "png", "bmp", "jpeg", "xls", "xlsx", "txt"]
            let error_ext = [];
            let error_size = [];
            let file_names = [];
            let ext_str = '';
            let size_str = '';
            for (let i = 0; i < e.target.files.length; i++) {
                let file_ext = e.target.files[i].name.split('.').pop();
                let file_size = parseInt(e.target.files[i].size);
                if (valid_ext.indexOf(file_ext.toLowerCase()) === -1)
                    error_ext.push(e.target.files[i].name)
                if (file_size > 325000)
                    error_size.push(`${e.target.files[i].name}(${Math.ceil((file_size / 1000)).toString()} KB)`);
                file_names.push(e.target.files[i].name);
            }
            if (error_ext.length > 0)
                ext_str = "<h6 style='color: red'>فرمت فایل(های) ذیل مورد قبول نمی باشد:</h6>" + error_ext.toString();
            if (error_size.length > 0)
                size_str = "<h6 style='color: red'>حجم فایل(های) ذیل مورد قبول نمی باشد:</h6>" + error_size.toString();
            if (error_size.length > 0 || error_ext.length > 0) {
                $(e.target).closest('div').find('input[type="text"]').val('فایلی انتخاب نشده است');
                bootbox.alert({
                    "message": ext_str + size_str,
                    closeButton: false,centerVertical: true,
                    buttons: {
                        ok: {
                            label: 'قبول'
                        }
                    },
                    backdrop: true,
                });
            } else
                $(e.target).closest('div').find('input[type="text"]').val(file_names.toString());
        },
        related_data_search(e) {
            const self = this;
            const target = e.target;
            if (target.value) {
                self.related_data_select = '';
                self.related_data_search_loading = true;
                const values = {"id": target.value, "type": target.dataset.type, "parent_id": self.parent_id};
                axios.post("/related_data_search", values)
                    .then(response =>
                    {
                        self.related_data_search_loading = false;
                        self.searches_child = '';
                        self.related_data_select_child = '';
                        if (response["data"].length === 0) {
                            self.contract_branch = '';
                            self.contract_category = '';
                            self.contractor_name = '';
                            self.invoices_count = '';
                            self.new_invoice_frame = false;
                            self.searches = '';
                            self.new_invoice_quantity = 0;
                            self.new_invoice_unit = '';
                            self.new_invoice_amount = 0;
                            self.new_invoice_total_amount = 0;
                            self.extra_inline = [];
                            self.deduction_inline = [];
                            self.new_invoice_comment = '';
                        }else {
                            self.searches = response["data"];
                            if (target.dataset.related_id)
                                self.related_data_select = target.dataset.related_id;
                        }
                    });
            }
        },
        related_data_search_child(e) {
            const self = this;
            const target = e.target;
            if (target.value) {
                self.related_data_select_child = '';
                self.related_data_search_loading_child = true;
                const values = {"id": target.value, "parent_id": self.parent_id, "type": target.dataset.type};
                axios.post("/related_data_search", values)
                    .then(response =>
                    {
                        if (response["data"].length === 0){
                            self.contract_branch = '';
                            self.contract_category = '';
                            self.contractor_name = '';
                            self.invoices_count = '';
                            self.new_invoice_frame = false;
                            self.searches = '';
                            self.searches_child = '';
                            self.new_invoice_quantity = 0;
                            self.new_invoice_unit = '';
                            self.new_invoice_amount = 0;
                            self.new_invoice_total_amount = 0;
                            self.extra_inline = [];
                            self.deduction_inline = [];
                            self.new_invoice_comment = '';
                        }else {
                            self.related_data_search_loading_child = false;
                            self.searches_child = response["data"];
                        }
                    });
            }
        },
        change_extra_deduction_content(e){
            const self = this;
            const target = e.target;
            let values = {};
            values["id"] = target.dataset.id;
            values["type"] = target.dataset.type;
            values["action"] = target.dataset.action;
            switch (target.dataset.type){
                case "extra":{
                    values["desc"] = $("#extra_desc_" + target.dataset.id).val();
                    values["amount"] = $("#extra_amount_" + target.dataset.id).val().replaceAll(",",'');
                    break;
                }
                case "deduction":{
                    values["desc"] = $("#deduction_desc_" + target.dataset.id).val();
                    values["amount"] = $("#deduction_amount_" + target.dataset.id).val().replaceAll(",",'');
                    break;
                }
            }
            if (values["desc"] !== null && values["amount"] !== null && target.dataset.action === "edit" || target.dataset.action === "delete") {
                self.loading_window_active = true;
                axios.post("/change_extra_deduction_content", values)
                    .then(response => {
                        self.loading_window_active = false;
                        if (response["data"] === "done")
                            alerify.success(`عملیات با موفقیت انجام شد`);
                        else
                            alerify.error(response["data"]);
                    });
            }
            else
                alerify.error(`مقادیر شرح و مبلغ را وارد کنید`);
        },
        search_input_filter(e) {
            let filter = e.target.value;
            let table, columns, tr, td, i, j, txtValue;
            typeof e.target.dataset.table_id !== 'undefined' ? table = document.getElementById(e.target.dataset.table_id) : table = document.getElementById("main_table");
            columns = JSON.parse(table.dataset.filter);
            tr = table.getElementsByTagName("tr");
            for (i = 1; i < tr.length; i++) {
                let strings = [];
                for (j = 0; j < columns.length; j++) {
                    td = tr[i].getElementsByTagName("td")[parseInt(columns[j])];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        strings.push(txtValue);
                    }
                }
                if (strings.length) {
                    const match = strings.find(element => {
                        const clearElement = element.replace(/[()\-_!@#$%^.,]/g, '');
                        return !!clearElement.includes(filter);
                    });
                    if (match)
                        tr[i].style.display = "";
                    else
                        tr[i].style.display = "none";
                }
            }
        },
        list_item_select(e) {
            const item = e.target;
            if ($(item).hasClass("active"))
                $(item).removeClass("active")
            else {
                if ($(item).parent().attr("id") !== "origin_list") {
                    $("#starter_list").children().each(function () {
                        if ($(this).hasClass("active"))
                            $(this).removeClass('active');
                    });
                    $("#inductor_list").children().each(function () {
                        if ($(this).hasClass("active"))
                            $(this).removeClass('active');
                    });
                    $("#finisher_list").children().each(function () {
                        if ($(this).hasClass("active"))
                            $(this).removeClass('active');
                    });
                } else {
                    $(item).parent().children().each(function () {
                        if ($(this).hasClass("active"))
                            $(this).removeClass('active');
                    });
                }
                $(item).toggleClass('active');
            }
        },
        flow_modal() {
            $("#flow_type_modal").modal("show");
        },
        adding_item() {
            let self = this;
            $("#origin_list").children().each(function (){
                if ($(this).hasClass("active")) {
                    $(this).removeClass("active");
                    let radios = document.getElementsByName('flow_type');
                    let selected = '';
                    for (let i = 0, length = radios.length; i < length; i++) {
                        if (radios[i].checked) {
                            selected = radios[i].id;
                            break;
                        }
                    }
                    if (selected === "finisher" && $("#finisher_list").children().length > 0)
                        $("#finisher_list").html('');
                    let item = $(this).clone(true);
                    console.log(selected);
                    switch (selected){
                        case "starter":{
                            self.final_starter.push($(item).data("id"));
                            break;
                        }
                        case "inductor":{
                            self.final_inductor.push($(item).data("id"));
                            break;
                        }
                        case "finisher":{
                            self.final_finisher = $(item).data("id");
                            break;
                        }
                    }
                    $(item).click(function (){self.list_item_select(event)});
                    $(`#${selected}_list`).append(item);
                    $("#flow_type_modal").modal("hide");
                }
            });
        },
        remove_item() {
            let item = '';
            let self = this;
            $("#starter_list,#inductor_list,#finisher_list").children().each(function () {
                if ($(this).hasClass("active")) {
                    $(this).removeClass('active');
                    item = $(this);
                }
            });
            if (item){
                let parent_list_id = $(item).parent().attr("id");
                switch (parent_list_id){
                    case "starter_list":{
                        let index = self.final_starter.indexOf($(item).data("id"));
                        if (index !== -1) {
                            self.final_starter.splice(index, 1);
                        }
                        break;
                    }
                    case "inductor_list":{
                        let index = self.final_inductor.indexOf($(item).data("id"));
                        if (index !== -1) {
                            self.final_inductor.splice(index, 1);
                        }
                        break;
                    }
                    case "finisher_list":{
                        self.final_finisher = '';
                        break;
                    }
                }
                $(item).remove();
            }
        },
        moving_item_up() {
            let item = '';
            $("#inductor_list").children().each(function () {
                if ($(this).hasClass("active")) {
                    item = $(this)
                    return false;
                }
            });
            if (item && $(item).index() > 0){
                let index = this.final_inductor.indexOf($(item).data("id"));
                this.final_inductor = this.array_move(this.final_inductor,index,--index);
                let previous_item = $(item).parent().children().eq($(item).index() - 1);
                $(item).insertBefore(previous_item);
            }
        },
        moving_item_down() {
            let item = '';
            $("#starter_list,#inductor_list,#finisher_list").children().each(function () {
                if ($(this).hasClass("active")) {
                    item = $(this)
                    return false;
                }
            });
            if (item && $(item).index() < ($(item).parent().children().length - 1)){
                let index = this.final_inductor.indexOf($(item).data("id"));
                this.final_inductor = this.array_move(this.final_inductor,index,++index);
                let next_item = $(item).parent().children().eq($(item).index() + 1);
                $(item).insertAfter(next_item);
            }
        },
        array_move(arr, old_index, new_index) {
            if (new_index >= arr.length) {
                let k = new_index - arr.length + 1;
                while (k--) {
                    arr.push(undefined);
                }
            }
            arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
            return arr;
        },
        get_new_invoice_information(){
            let self = this;
            let project_id = $("#project_id").val();
            let contract_id = $("#contract_id").val();
            if (project_id !== null && contract_id !== null){
                const values = {"project_id": project_id, "contract_id": contract_id};
                axios.post("/get_new_invoice_information", values)
                    .then(response =>
                    {
                        self.contract_branch = response["data"]["contract"]["category"]["branch"]["branch"];
                        self.contract_category = response["data"]["contract"]["category"]["category"];
                        self.contractor_name = response["data"]["contract"]["contractor"]["name"];
                        self.invoices_count = parseInt(response["data"]["contract"]["invoices_count"]) + 1;
                        self.new_invoice_frame = true;
                        self.new_invoice_unit = response["data"]["contract"]["unit"]["name"];
                        self.new_invoice_amount = response["data"]["contract"]["amount"];
                        self.invoice_total_previous_quantity = response["data"]["contract"]["automation_amounts_sum_quantity"] ? response["data"]["contract"]["automation_amounts_sum_quantity"] : 0;
                        self.contract_info = response["data"]["report"]["details"];
                        if(response["data"]["report"].length > 0)
                            self.contract_info_total = response["data"]["report"]["totals"];
                    });
            }
        },
        new_invoice_total_amount_process(){
            let amount = this.new_invoice_quantity;
            let price = this.new_invoice_amount.toString().replace(/,/g,'');

            let extra_amounts = 0;
            let deduction_amounts = 0;
            $("#extra_work_table tbody").children().each(function (){
                extra_amounts += parseFloat($(this).children().eq(1).children().eq(0).val().replace(/,/g,''));
            });
            $("#deduction_work_table tbody").children().each(function (){
                deduction_amounts += parseFloat($(this).children().eq(1).children().eq(0).val().replace(/,/g,''));
            });
            let extra_deduction = extra_amounts - deduction_amounts;
            if (amount && price) {
                const summary = (amount * price) + extra_deduction;
                this.payment_offer_disabled = false;
                this.new_invoice_total_amount = summary;
                this.invoice_payment_offer = summary;
                this.invoice_payment_offer_percent = (this.invoice_payment_offer / this.new_invoice_total_amount) * 100;
            }
            else {
                this.new_invoice_total_amount = 0;
                this.new_invoice_quantity = 0;
                this.payment_offer_disabled = true;
                this.invoice_payment_offer = 0;
                this.invoice_payment_offer_percent = 0;
            }
        },
        create_new_invoice_extra_line(){
            this.extra_inline.push(extra_work_line);
        },
        create_new_invoice_deduction_line(){
            this.deduction_inline.push(deduction_work_line);
        },
        clear_invoice_form(){
            this.new_invoice_quantity = 0;
            this.new_invoice_unit = '';
            this.new_invoice_amount = 0;
            this.new_invoice_total_amount = 0;
            this.extra_inline = [];
            this.deduction_inline = [];
            this.new_invoice_comment = '';
            this.new_invoice_frame = false;
        },
        show_contract_details_modal(){
            $("#contract_details_modal").modal("show");
        },
        invoice_payment_offer_percent_change(){
            if (this.new_invoice_total_amount !== 0 && this.new_invoice_total_amount !== null) {
                if (parseInt(this.invoice_payment_offer_percent) > 100)
                    this.invoice_payment_offer_percent = 100;
                this.invoice_payment_offer = (this.invoice_payment_offer_percent / 100) * this.new_invoice_total_amount;
            }
            else {
                this.invoice_payment_offer_percent = 0;
                this.invoice_payment_offer = 0;
            }
        },
        invoice_payment_offer_change(){
            if (parseInt(this.new_invoice_total_amount) !== 0 && this.new_invoice_total_amount !== "") {
                let percent = (this.invoice_payment_offer.replaceAll(",",'') / this.new_invoice_total_amount) * 100;
                percent = Math.ceil(percent);
                if (parseInt(this.invoice_payment_offer.replaceAll(",",'')) > parseInt(this.new_invoice_total_amount)){
                    this.invoice_payment_offer = this.new_invoice_total_amount;
                    percent = 100;
                }
                this.invoice_payment_offer_percent = percent;
            }
            else{
                this.invoice_payment_offer_percent = 0;
                this.invoice_payment_offer = 0;
            }
        },
        new_invoice_amounts_modal(){
            $("#enter_new_invoice_amounts").modal("show");
        },
        new_bank_information_modal(){
            $("#bank_information").modal("show");
        },
        add_bank_information(){
            if (this.bank_name && this.bank_card_number && this.bank_account_number && this.bank_sheba_number){
                let tmp = {"name":this.bank_name,"card":this.bank_card_number,"account":this.bank_account_number,"sheba":this.bank_sheba_number};
                this.bank_items.push(tmp);
                $("#bank_information").modal("hide");
            }
        },
        invoice_automation_total_payment(e){
            if(e.target.value.length > 0){
                if(parseFloat(e.target.value.replace(/,/g,'')) > parseFloat(e.target.dataset.total_amount)) {
                    e.target.value = e.target.dataset.total_amount;
                    alerify.error(`سقف قابل پرداخت صورت وضعیت مبلغ ${e.target.value} ریال می باشد.`);
                }
            }
        },
        copy_bank_information(e){
            if(e.target.dataset.copy.length > 0) {
                navigator.clipboard.writeText(e.target.dataset.copy).then(function () {
                    alerify.notify('متن مورد نظر در حافظه کپی شد!', 'copy', "2");
                }, function (err) {
                    alerify.warning("مرورگر شما از کپی در حافظه پشتیبانی نمیکند!");
                });
            }
        },
        show_contractor_details_modal(){
            $("#contractor_details_modal").modal("show");
        },
        new_check_information_modal(){
            $("#check_information").modal("show");
        },
        set_check_end(){
            if (this.check_quantity && this.check_start)
                this.check_end = parseInt(this.check_start) + (parseInt(this.check_quantity) - 1)
        },
        add_check_information(){
            if (this.check_serial && this.check_sayyadi && this.check_start && this.check_end){
                let tmp = {"serial":this.check_serial,"sayyadi":this.check_sayyadi,"start":this.check_start,"end":this.check_end};
                this.check_items.push(tmp);
                $("#check_information").modal("hide");
            }
        },
        get_contractor_bank_information(e){
            if (e.target.value){
                this.deposit_kind_number = $(e.target).find(":selected").data("options")["card"];
                $("#deposit_kind_card").val("card");
                $("#deposit_kind_card_number").val($(e.target).find(":selected").data("options")["card"]).trigger("input");
                $("#card-copy").attr("data-copy",$(e.target).find(":selected").data("options")["card"]);
                $("#edit_card").attr("data-bank_id",$(e.target).find(":selected").data("options")["bank_id"]);
                $("#deposit_kind_sheba").val("sheba");
                $("#deposit_kind_sheba_number").val($(e.target).find(":selected").data("options")["sheba"]).trigger("input");
                $("#sheba-copy").attr("data-copy",$(e.target).find(":selected").data("options")["sheba"]);
                $("#edit_sheba").attr("data-bank_id",$(e.target).find(":selected").data("options")["bank_id"]);
                $("#deposit_kind_account").val("account");
                $("#deposit_kind_account_number").val($(e.target).find(":selected").data("options")["account"]).trigger("input");
                $("#account-copy").attr("data-copy",$(e.target).find(":selected").data("options")["account"]);
                $("#edit_account").attr("data-bank_id",$(e.target).find(":selected").data("options")["bank_id"]);
            }
        },
        deposit_kind_change(e){
            if (e.target.checked === true){
                switch (e.target.id){
                    case "deposit_kind_card":{
                        this.deposit_kind_number = $("#card-copy").attr("data-copy");
                        break;
                    }
                    case "deposit_kind_sheba":{
                        this.deposit_kind_number = $("#sheba-copy").attr("data-copy");
                        break;
                    }
                    case "deposit_kind_account":{
                        this.deposit_kind_number = $("#account-copy").attr("data-copy");
                        break;
                    }
                }
            }
        },
        sidebar_toggle(e){
            this.sidebar_visibility = !this.sidebar_visibility;
            if ($(e.target).hasClass("fa-bars"))
                $(e.target).removeClass("fa-bars").addClass("fa-times");
            else
                $(e.target).removeClass("fa-times").addClass("fa-bars");
        },
        phone_menu_header(e){
            let target = e.target;
            $(".menu_header").removeClass("show");
            $(".menu_header_container").removeClass("active")
            $("#" + target.dataset.slug).addClass("show");
            $("#" + target.dataset.slug + "_header").addClass("active");
        },
        worker_payment_process(){
            $("#worker_payment_process").modal("show");
        },
        percent_check(e){
            if (parseInt(e.target.value) > 100)
                e.target.value = 100;
            else if (parseInt(e.target.value) < 0)
                e.target.value = 0;
        },
        sidenav_visibility(){
            $(".sidenav").toggleClass("disappear");
            $(".header_container, .pages_container, .gadget_container").toggleClass("full-width");
            $(".header_menu_button").toggleClass("fa-arrow-right").toggleClass("fa-arrow-left");
            window.dispatchEvent(new Event('resize'));
        },
        submit_login(){
            this.loading_window_active = true;
        },
        linkAction (e) {
            if (e.currentTarget.getAttribute('href') !== "" && e.currentTarget.getAttribute('href') !== null && e.currentTarget.getAttribute('href').startsWith("#") === false) {
                this.loading_window_active = true;
            }
        },
        invoice_details(e){
            const self = this;
            let invoice_id = e.currentTarget.dataset.invoice_id;
            if (invoice_id) {
                const values = {"invoice_id": invoice_id};
                this.loading_window_active = true;
                axios.post("/get_invoice_details", values).then(response => {
                        self.loading_window_active = false;
                        const header = $("#roles_head");
                        const titles = $("#roles_titles");
                        const body = $("#amounts_body");
                        const ex_body = $("#extras_body");
                        let extras = 0,deductions = 0;
                        const de_body = $("#deductions_body");
                        const total_body = $("#totals_body");
                        titles.append(`<th colspan="${response["data"]["automation_amounts"].length}">
                                کارکرد</th>
                                <th></th>
                                <th colspan="${response["data"]["automation_amounts"].length}">
                                بهاء جزء(ریال)</th>
                                <th colspan="${response["data"]["automation_amounts"].length}">
                                بهاء کل(ریال)</th>`);
                        for (const item of response["data"]["automation_amounts"])
                            header.append(`<th>${item["user"]["role"]["name"]}</th>`);
                        header.append("<th>واحد</th>");
                        for (const item of response["data"]["automation_amounts"])
                            header.append(`<th>${item["user"]["role"]["name"]}</th>`);
                        for (const item of response["data"]["automation_amounts"])
                            header.append(`<th>${item["user"]["role"]["name"]}</th>`);
                        for (const item of response["data"]["automation_amounts"])
                            body.append(`<td>${item["quantity"]}</td>`);
                        body.append(`<td>${response["data"]["contract"]["unit"]["name"]}</td>`);
                        for (const item of response["data"]["automation_amounts"]) {
                            const amount = numeral(item["amount"]).format("0,0")
                            body.append(`<td>${amount}</td>`);
                        }
                        for (const item of response["data"]["automation_amounts"]) {
                            const amount = item["amount"];
                            const quantity = item["quantity"];
                            const amount_sum = numeral(amount * quantity).format("0,0");
                            body.append(`<td>${amount_sum}</td>`);
                        }
                        for (const item of response["data"]["extras"]) {
                            extras += parseFloat(item["amount"]);
                            const amount = numeral(item["amount"]).format("0,0")
                            ex_body.append(`<tr><td>${item["description"]}</td><td>${amount}</td></tr>`);
                        }
                        for (const item of response["data"]["deductions"]) {
                            deductions += parseFloat(item["amount"]);
                            const amount = numeral(item["amount"]).format("0,0")
                            de_body.append(`<tr><td>${item["description"]}</td><td>${amount}</td></tr>`);
                        }
                        for (const item of response["data"]["automation_amounts"]) {
                            const payment_offer = numeral(item["payment_offer"]).format("0,0")
                            const amount = item["amount"];
                            const quantity = item["quantity"];
                            const amount_sum = numeral((amount * quantity) + (extras - deductions)).format("0,0");
                            total_body.append(`<tr><td><div class="payment_offer_container">${item["user"]["role"]["name"]}</td><td>${amount_sum}</td><td><span class="text-center iran_yekan bg-success white_color p-1" style="width: 25%">${item["payment_offer_percent"]} %</span>
                                    <span class="text-center iran_yekan bold_font" style="width: 75%;">${payment_offer}</span></div></td></tr>`);
                        }
                    }
                );
            }
            $("#invoice_details").modal("show");
        },
        clear_invoice_details(){
            $("#roles_titles,#roles_head,#amounts_body,#extras_body,#deductions_body,#totals_body").html('');
        },
        contact_information(e){
            e.stopPropagation();
            $("#contact_id").val(e.currentTarget.children[0].innerText);
            $("#e_name").val(e.currentTarget.children[1].innerText);
            $("#e_phone_number_1").val(e.currentTarget.children[2].innerText);
            $("#e_phone_number_2").val(e.currentTarget.children[3].innerText);
            $("#e_phone_number_3").val(e.currentTarget.children[4].innerText);
            $("#e_job_title").val(e.currentTarget.children[5].innerText);
            $("#e_email").val(e.currentTarget.children[6].innerText);
            $("#e_address").val(e.currentTarget.children[8].innerText);
            $("#e_note").val(e.currentTarget.children[7].innerText);
            $("#update_form").attr("action",e.currentTarget.dataset.route);
            $("#contact_information").modal("show");
        },
        edit_bank_information(e){
            let data = {};
            const self = this;
            data["contractor_id"] = e.target.dataset.contractor_id;
            data["bank_id"] = e.target.dataset.bank_id;
            switch (e.target.id){
                case "edit_card":{
                    data["type"] = "card";
                    data["value"] = $("#deposit_kind_card_number").cleanVal();
                    break;
                }
                case "edit_sheba":{
                    data["type"] = "sheba";
                    data["value"] = $("#deposit_kind_sheba_number").cleanVal();
                    break;
                }
                case "edit_account":{
                    data["type"] = "account";
                    data["value"] = $("#deposit_kind_account_number").cleanVal();
                    break;
                }
            }
            if (data["value"]) {
                bootbox.confirm({
                    message: "آیا برای ویرایش اطلاعات بانکی پیمانکار اطمینان دارید؟",
                    closeButton: false, centerVertical: true,
                    buttons: {
                        confirm: {
                            label: 'بله',
                            className: 'btn-success',
                        },
                        cancel: {
                            label: 'خیر',
                            className: 'btn-danger',
                        }
                    },
                    callback: function (result) {
                        if (result === true) {
                            bootbox.hideAll();
                            self.loading_window_active = true;
                            axios.post("/update_bank_information", data)
                                .then(function (response) {
                                    self.loading_window_active = false;
                                    if (response.data === "ok")
                                        alerify.success("ویرایش اطلاعات بانکی با موفقیت انجام شد");
                                }).catch(function (error) {
                                self.loading_window_active = false;
                                if (error.response) {
                                    console.log(error.response.data);
                                    console.log(error.response.status);
                                    console.log(error.response.headers);
                                }
                            });
                        }
                    }
                });
            }
        },
        invoice_details_navigation(e){
            location.replace(e.currentTarget.dataset.details_route);
        },
        check_menu_action(e) {
            e.stopPropagation();
            $(e.currentTarget).children('input[type="checkbox"]').prop('checked',!$(e.currentTarget).children('input[type="checkbox"]').prop('checked'));
        },
        select_all_checkboxes(e){
            $(e.target).closest('ul').find('input[type="checkbox"]').prop("checked",true);
        },
        deselect_all_checkboxes(e){
            $(e.target).closest('ul').find('input[type="checkbox"]').prop("checked",false);
        },
        add_value_to_input(e){
            $("#type").val(e.currentTarget.dataset.value);
        },
        showPosition(position) {
            return [position.coords.latitude , position.coords.longitude];
        },
        update_software(){
            this.loading_window_active = true;
            if (navigator.serviceWorker.controller) {
                navigator.serviceWorker.controller.postMessage('update-cache');
            }
        },
        version() {
            return new Promise((resolve) => {
                const openingRequest = indexedDB.open('FrontDB-arikeh');
                openingRequest.onsuccess = (event) => {
                    let db = event.target.result;
                    try {
                        const transaction = db.transaction("application_settings", "readwrite");
                        const store = transaction.objectStore("application_settings");
                        const index = store.index("app_version");
                        let version = index.get(1);
                        return version.onsuccess = function () {
                            if (version.result !== null) {
                                resolve(version.result.ver);
                            } else
                                resolve(new Date().getTime());
                        }
                    } catch (e) {
                        resolve(new Date().getTime());
                    }
                }
            });
        },
        resize_window() {
            const width = window.innerWidth;
            switch (true){
                case (width > 1024):{
                    if (window.location.href.includes("Phone"))
                        window.location.replace(window.location.href.replace("Phone","Desktop"));
                    break;
                }
                case (width < 1024):{
                    if (window.location.href.includes("Desktop"))
                        window.location.replace(window.location.href.replace("Desktop","Phone"));
                    break;
                }
            }
        },
        calender_selection(e){
            const target = $(e.currentTarget).find(".cover");
            let checked = !$(target).hasClass("active");
            // if($(".cover.active").length === 3 && checked === true) {
            //     bootbox.alert("حداکثر 3 روز قابل انتخاب می باشد");
            // }
            // else {
                $(target).toggleClass("active");
                $(target).find("input[name='selected_dates[]']").prop("checked", checked);
            // }
        },
        show_modal(e){
            const type = typeof e.currentTarget.dataset.type !== "undefined" ? e.currentTarget.dataset.type : null;
            this.leave_date='';this.departure_time='';this.arrival_time='';
            if(type !== null && type === "daily") {
                this.leave_type_show = false;
                this.leave_type_hidden = "daily";
                this.leave_type = "روزانه";
            }
            else if(type !== null && type === "hourly") {
                this.leave_type_show = true;
                this.leave_type_hidden = "hourly";
                this.leave_type = "ساعتی";
            }
            $(`#${e.currentTarget.dataset.modal_name}`).modal("show");
        },
        add_leave_information(){
            if (this.leave_type_hidden === "daily" && this.leave_date || this.leave_type_hidden === "hourly" && this.leave_date && this.departure_time && this.arrival_time){
                let tmp = {"type":this.leave_type_hidden,"leave_type":this.leave_type,"leave_date":this.leave_date,"departure_time":this.departure_time,"arrival_time":this.arrival_time};
                this.leave_items.push(tmp);
            }
        },
        set_work_shift(){
            const work_shift_id = $("#staff_id").find("option:selected").data("work_shift");
            $("#work_shift_id").val(work_shift_id).selectpicker('refresh');
        },
        worker_payment_automation_details(e){
            const automation_id = e.currentTarget.dataset.id;
            automation_items.forEach(function (item,index) {
                if(item.id === parseInt(automation_id)){
                    $("#project_name").val(automation_items[index]["project"]["name"]);
                    $("#worker_name").val(automation_items[index]["contractor"]["name"]);
                    $("#amount").val(automation_items[index]["amount"]);
                    $(".comments_container,.sign_container").html('');
                    automation_items[index]["comments"].forEach(function (item){
                        let date = moment.from(item.created_at, 'en', 'YYYY/MM/DD').format('YYYY/MM/DD')
                        $(".comments_container").append(`
                        <div class="comment_box iran_yekan mt-2">
                                        <div class="commenter">
                                            <i class="fa fa-user-circle fa-2x mr-2"></i>
                                            <span class="text-muted">${item.user.name}(${item.user.role.name})</span>
                                        </div>
                                        <p class="mt-2 comment">${item.comment}</p>
                                        <span class="time-left" dir="ltr">${date}</span>
                                    </div>
                        `);
                    });
                    automation_items[index]["signs"].forEach(function (item){
                        let date = moment.from(item.created_at, 'en', 'YYYY/MM/DD').format('YYYY/MM/DD')
                        $(".sign_container").append(`
                        <div class="sign_box iran_yekan bg-light mr-4">
                                        <i class="fa fa-user-circle fa-2x mr-2"></i>
                                        <span class="text-muted">${item.user.role.name}</span>
                                        <span>${item.user.name}</span>
                                        <span class="text-muted" dir="ltr" style="font-size: 10px">${date}</span>
                                    </div>
                        `);
                    });
                }
            });
            $("#automation_details").modal("show");
            $("#send_form").prop("action",e.currentTarget.dataset.route);
            $("#refer_form").prop("action",e.currentTarget.dataset.refer);
            $("#pay_window_form").prop("action",e.currentTarget.dataset.pay);
        }
    }
});
