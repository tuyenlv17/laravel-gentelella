/**
 Role Module
 **/
var AppRole = function () {

    var baseUrl = $('#site-meta').attr('data-base-url');
    var roleTable = null;
//    $.fn.select2.defaults.set("theme", "bootstrap");
    // private functions & variables

    function loadRoleTable() {
        roleTable = $('#roles-table').DataTable({
            "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable table-responsive   't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            "serverSide": true,
            "processing": true,
            "language": {
                "processing": '<div class="loading-message"><i class="fa fa-spinner fa-spin"></i><span>&nbsp;&nbsp;&nbsp; Loading...</span></div>',
                "infoEmpty": "No record found",
            },
            "ajax": {
                "url": baseUrl + '/admin/rbac/roles/listing',
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
                    'data': 'name'
                },
                {
                    'data': 'display_name'
                },
                {
                    'data': 'description'
                },
                {
                    'data': 'default_url'
                },
                {
                    'data': null,
                    'sortable': false,
                    'className': 'text-center'
                }
            ],
            columnDefs: [
                {
                    targets: [0, 5],
                    sortable: false
                },
                {
                    targets: [5],
                    render: function (data, type, row) {
                        return '<a href="' + baseUrl + '/admin/rbac/roles/' + row['id'] + '/edit" class="table-action table-action-edit" title="Edit"><i class="fa fa-pencil"></i></a>'
                                + '<a href="javascript:;" class="table-action table-action-delete delete-role" data-id="' + row['id'] + '" title="Delete"><i class="fa fa-trash-o"></i></a>';
                    }
                }
            ]
        });

        roleTable.on('order.dt search.dt draw.dt', function () {
            var info = roleTable.page.info();
            var start = info.start;
            roleTable.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
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
     * delete an Role
     * @returns {undefined}
     */
    function deleteRole() {
        $(document).on('click', '.delete-role', function () {
            var id = $(this).attr('data-id');
            var btn = $(this);
            Custom.deleteRecord(btn, baseUrl + '/admin/rbac/roles/' + id, roleTable);
        });
    }
    ;

    /**
     * handle validation form
     * @returns {undefined}
     */
    function handleValidation() {
        var form = $('#role-form');
        var error = $('.alert-danger', form);

        form.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: false,
            ignore: "",
            rules: {
                name: {
                    minlength: 2,
                    maxlength: 64,
                    required: true
                },
                display_name: {
                    required: true
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
    }
    ;

    function initComponent() {
        $('select').select2({});
        $('input[type=checkbox]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
        });
    }

    // public functions
    return {
        //main function
        init: function () {
            loadRoleTable();
            deleteRole();
            initComponent();
        }

    };

};

$(document).ready(function () {
    AppRole().init();
});