/**
 User Module
 **/
var AppProfile = function () {

    var baseUrl = jQuery('#site-meta').attr('data-base-url');
    
    function initComponent() {
        $('.select2-mutiple').select2({});

        $('#birthday').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
            singleClasses: "picker_2",
            showDropdowns: true,
        }, function (start, end, label) {

        });
    }

    // public functions
    return {
        //main function
        init: function () {
            initComponent();
        }

    };

};

jQuery(document).ready(function () {
    AppProfile().init();
});