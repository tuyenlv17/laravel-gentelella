/**
 User Module
 **/
var AppUser = function () {

    var baseUrl = $('#site-meta').attr('data-base-url');
    var userTable = null;

    function loadUserTable() {

        userTable = $('#users-table').DataTable({
            "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable table-responsive't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            "serverSide": true,
            "processing": true,
            "language": {
                "processing": '<div class="loading-message"><i class="fa fa-spinner fa-spin"></i><span>&nbsp;&nbsp;&nbsp; Loading...</span></div>',
                "infoEmpty": "No record found",
            },
            "ajax": {
                "url": baseUrl + '/admin/rbac/users/listing',
                "type": 'POST',
                "dataType": 'json'
            },
            order: [1, 'asc'],
            columns: [
                {
                    'data': null,
                    'sortable': false,
                    'className': 'tb-no-sort tb-number'
                },
                {
                    'data': 'username'
                },
                {
                    'data': 'fullname'
                },
                {
                    'data': 'roles'
                },
                {
                    'data': null,
                    'sortable': false,
                    'className': 'text-center'
                }
            ],
            columnDefs: [
                {
                    targets: [4],
                    sortable: false,
                    render: function (data, type, row) {
                        return '<a href="' + baseUrl + '/admin/rbac/users/' + row['id'] + '/edit" class="table-action table-action-edit" title="Edit"><i class="fa fa-pencil"></i></a>'
                                + '<a href="javascript:;" class="table-action table-action-delete delete-user" data-id="' + row['id'] + '" title="Delete"><i class="fa fa-trash-o"></i></a>';
                    }
                }
            ]
        });

        userTable.on('order.dt search.dt draw.dt', function () {
            var info = userTable.page.info();
            var start = info.start;
            userTable.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + start;
            });
        });

        $(".dataTables_length select").select2({
            minimumResultsForSearch: -1,
            width: '60px'
        });
    }
    ;

    /**
     * delete an User
     * @returns {undefined}
     */
    function deleteUser() {
        $(document).on('click', '.delete-user', function () {
            var btn = $(this);
            var id = btn.attr('data-id');
            Custom.deleteRecord(btn, baseUrl + '/admin/rbac/users/' + id, userTable);
        });
    }

    /**
     * handle validation form
     * @returns {undefined}
     */
    var handleValidation = function () {
        var form = $('#user-form');
        var error = $('.alert-danger', form);
        var isPwRequired = ($('#is-adding-user').val() === 'true');
        form.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: false,
            ignore: "",
            messages: {
                password_confirmation: {
                    equalTo: 'Password does not match!'
                }
            },
            rules: {
                name: {
                    minlength: 2,
                    maxlength: 32,
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: isPwRequired
                },
                password_confirmation: {
                    required: isPwRequired,
                    equalTo: '[name=password]'
                }
            },
            invalidHandler: function (event, validator) {
                error.show();
                App.scrollTo(error, -200);
            },
            highlight: function (element) {
                $(element)
                        .closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element)
                        .closest('.form-group').removeClass('has-error');
            },
            success: function (label) {
                label
                        .closest('.form-group').removeClass('has-error');
            },
            submitHandler: function (form) {
                error.hide();
                form.submit();
            }
        });
    };

    function initComponent() {
        $('.select2-mutiple').select2({});

        $('#birthday').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
            singleClasses: "picker_2",
            showDropdowns: true,
            minDate: moment().add(-40, 'y'),
            maxDate: moment().add(-13, 'y'),
        }, function (start, end, label) {

        });
    }

    // public functions
    return {
        //main function
        init: function () {
            loadUserTable();
            deleteUser();
//            handleValidation();
            initComponent();
        }

    };

};

$(document).ready(function () {
    AppUser().init();
});