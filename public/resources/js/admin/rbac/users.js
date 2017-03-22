/**
 User Module
 **/
var AppUser = function () {

    var baseUrl = jQuery('#site-meta').attr('data-base-url');
    var userTable = null;

    function loadUserTable() {

        userTable = jQuery('#users-table').DataTable({
            "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
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

        jQuery(".dataTables_length select").select2({
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
        jQuery(document).on('click', '.delete-user', function () {
            var btn = jQuery(this);
            var id = btn.attr('data-id');

            if (confirm("Delete?")) {
                jQuery.ajax({
                    url: baseUrl + '/admin/rbac/users/' + id,
                    dataType: 'json',
                    type: 'DELETE',
                    data: {
                    },
                    success: function (data, textStatus, jqXHR) {
                        if (data.code == 0) {
                            btn.parents('tr').addClass('hidden selected');
                            if (userTable != null) {
                                userTable.row('.selected')
                                        .remove()
                                        .draw(false);
                            }
                            alert('success!');
                        } else {
                            alert('error!');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('error!');
                    }
                });
            }
        });
    }

    /**
     * handle validation form
     * @returns {undefined}
     */
    var handleValidation = function () {
        var form = jQuery('#user-form');
        var error = jQuery('.alert-danger', form);
        var isPwRequired = (jQuery('#is-adding-user').val() === 'true');
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
                jQuery(element)
                        .closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                jQuery(element)
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

jQuery(document).ready(function () {
    AppUser().init();
});