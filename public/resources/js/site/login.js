/**
 Login Module
 **/
var AppLogin = function () {

    var baseUrl = $('#site-meta').attr('data-base-url');
    
    function initComponent() {
        $('.select2-mutiple').select2({});

        $('[name=birthday]').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
            singleClasses: "picker_2",
            showDropdowns: true,
        }, function (start, end, label) {
            console.log(start + ' ' + end);
        });
        
        $('input[type=checkbox]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
        });
    }
    
    function handleAction() {
        $('#captcha-reload').click(function (e) {
            var newCaptchaUrl = baseUrl + '/captcha/default?' + Math.random();
            $('#captcha-img').attr('src', newCaptchaUrl);
        });
    }

    // public functions
    return {
        //main function
        init: function () {
            initComponent();
            handleAction();
        }

    };

};

$(document).ready(function () {
    AppLogin().init();
});