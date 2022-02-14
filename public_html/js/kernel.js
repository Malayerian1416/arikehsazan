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
        related_data_select: "",
        related_data_search_loading: false,
        searches: [],
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
        new_invoice_automation_show: false,
        new_invoice_automation_text: "",
        new_worker_payment_automation_show: false,
        new_worker_payment_automation_text: "",
        invoice_total_previous_quantity: 0,
        linklist:'',
    },
    mounted() {
        const self = this;
        this.loading_window_active = false;
        this.linkList = document.getElementsByTagName('a');
        for( let i=0; i < this.linkList.length; i++ )
            this.linkList[i].onclick = this.linkAction;
        let notification = document.getElementsByClassName("badge");
        if (notification.length !== 0) {
            let get_notification = new EventSource("/Dashboard/Desktop/get_new_notification");
            get_notification.onmessage = function (event) {
                let result = JSON.parse(event.data);
                for (const [key, value] of Object.entries(result)) {
                    switch (key) {
                        case "new_invoice_automation": {
                            value ? self.new_invoice_automation_text = value : "";
                            value ? self.new_invoice_automation_show = true : false;
                            break;
                        }
                        case "new_worker_payment_automation": {
                            value ? self.new_worker_payment_automation_text = value : "";
                            value ? self.new_worker_payment_automation_show = true : false;
                            break;
                        }
                    }
                }
            }
        }
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
        submit_create_form(e) {
            console.log(this.linklist.length);
            let form = e.target;
            e.preventDefault();
            bootbox.confirm({
                message: "آیا برای ذخیره اطلاعات اطمینان دارید؟",
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
                        let self = this;
                        bootbox.hideAll();
                        $(".masked").length > 0 ? $(".masked").unmask() : "";
                        $(".number_format").length > 0 ? AutoNumeric.getAutoNumericElement('.number_format').formUnformat() : '';
                        self.button_loading = true;
                        self.button_not_loading = false;
                        self.loading_window_active = true;
                        form.submit();
                    }
                }
            });
        },
        submit_update_form(e) {
            let self = this;
            let form = e.target;
            e.preventDefault();
            bootbox.confirm({
                message: "آیا برای ویرایش اطلاعات اطمینان دارید؟",
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
                        bootbox.hideAll();
                        $(".masked").length > 0 ? $(".masked").unmask() : "";
                        $(".number_format").length > 0 ? AutoNumeric.getAutoNumericElement('.number_format').formUnformat() : '';
                        self.button_loading = true;
                        self.button_not_loading = false;
                        self.loading_window_active = true;
                        form.submit();
                    }
                }
            });
        },
        submit_delete_form(e) {
            let self = this;
            let form = e.target;
            e.preventDefault();
            bootbox.confirm({
                message: "آیا برای حذف این رکورد اطمینان دارید؟",
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
                        bootbox.hideAll();
                        self.loading_window_active = true;
                        form.submit();
                    }
                }
            });
        },
        submit_pay_form(e) {
            let form = e.target;
            e.preventDefault();
            bootbox.confirm({
                message: "آیا برای تایید و پرداخت اطمینان دارید؟",
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
                        let self = this;
                        bootbox.hideAll();
                        $(".masked").length > 0 ? $(".masked").unmask() : "";
                        $(".number_format").length > 0 ? AutoNumeric.getAutoNumericElement('.number_format').formUnformat() : '';
                        self.button_loading = true;
                        self.button_not_loading = false;
                        self.loading_window_active = true;
                        form.submit();
                    }
                }
            });
        },
        submit_activation_form(e) {
            let self = this;
            let form = e.target;
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
            e.preventDefault();
            bootbox.confirm({
                message: `آیا برای ${status} کردن این رکورد اطمینان دارید؟`,
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
                        bootbox.hideAll();
                        form.submit();
                    }
                }
            });
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
                            axios.post("/Dashboard/Desktop/live_adding_data", self.live_adding_data_content)
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
                });
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
                $("#file_browser_box").val('فایلی انتخاب نشده است');
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
                $("#file_browser_box").val(file_names.toString());
        },
        related_data_search(e) {
            const self = this;
            const target = e.target;
            if (target.value) {
                self.related_data_search_loading = true;
                const values = {"id": target.value, "type": target.dataset.type};
                axios.post("/Dashboard/Desktop/related_data_search", values)
                    .then(response =>
                    {
                        self.related_data_search_loading = false;
                        if (response["data"].length === 0){
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
                            self.related_data_select = '';
                        }else {
                            self.searches = response["data"];
                            if (target.dataset.related_id)
                                self.related_data_select = target.dataset.related_id;
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
                axios.post("/Dashboard/Desktop/change_extra_deduction_content", values)
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
            table = document.getElementById("main_table");
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
                    $(this).remove();
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
                let cloned_item = $(item).clone(true);
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
                $(cloned_item).click(function (){self.list_item_select(event)});
                $("#origin_list").append(cloned_item);
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
                axios.post("/Dashboard/Desktop/get_new_invoice_information", values)
                    .then(response =>
                    {
                        self.contract_branch = response["data"]["category"]["branch"]["branch"];
                        self.contract_category = response["data"]["category"]["category"];
                        self.contractor_name = response["data"]["contractor"]["name"];
                        self.invoices_count = parseInt(response["data"]["invoices_count"]) + 1;
                        self.new_invoice_frame = true;
                        self.new_invoice_unit = response["data"]["unit"]["name"];
                        self.new_invoice_amount = response["data"]["amount"];
                        self.invoice_total_previous_quantity = response["data"]["automation_amounts_sum_quantity"] ? response["data"]["automation_amounts_sum_quantity"] : 0;
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
                $("#deposit_kind_card").val("card").prop("disabled",false).attr("data-copy",$(e.target).find(":selected").data("options")["card"]);
                $("#deposit_kind_card_label").attr("data-copy",$(e.target).find(":selected").data("options")["card"]);
                $("#deposit_kind_account").val('account').prop("disabled",false).attr("data-copy",$(e.target).find(":selected").data("options")["account"]);
                $("#deposit_kind_account_label").attr("data-copy",$(e.target).find(":selected").data("options")["account"]);
                $("#deposit_kind_sheba").val('sheba').prop("disabled",false).attr("data-copy",$(e.target).find(":selected").data("options")["sheba"]);
                $("#deposit_kind_sheba_label").attr("data-copy",$(e.target).find(":selected").data("options")["sheba"]);
            }
        },
        deposit_kind_change(e){
            if (e.target.checked === true)
                this.deposit_kind_number = e.target.dataset.copy;
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
            $(".header_menu_button").toggleClass("fa-times").toggleClass("fa-bars");
            window.dispatchEvent(new Event('resize'));
        },
        submit_login(){
            this.loading_window_active = true;
        },
        linkAction (e) {
            if (e.currentTarget.getAttribute('href') !== "" && e.currentTarget.getAttribute('href') !== null && e.currentTarget.getAttribute('href').startsWith("#") === false) {
                this.loading_window_active = true;
            }
        }
    }
});
