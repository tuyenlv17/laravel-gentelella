/**
 User Module
 **/
var AppProfile = function () {

    var baseUrl = $('#site-meta').attr('data-base-url');
    
    function initComponent() {
        $('.select2-mutiple').select2({});

        $('#birthday').daterangepicker({
            singleDatePicker: true,
            singleClasses: "picker_2",
            showDropdowns: true,
            minDate: moment().add(-40, 'y'),
            maxDate: moment().add(-13, 'y'),
//            yearRange: "-0:+0",
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

$(document).ready(function () {
    AppProfile().init();
});