/**
 Permission Module
 **/
var AppPermission = function () {
    
    var baseUrl = jQuery('#site-meta').attr('data-base-url');
    var table = null;    

    var _loadPermissionTable = function () {
        table = jQuery('#permission-table').DataTable({
            "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            "serverSide": true,
            "processing": true,
            responsive: true,
            "language": {
                "processing": '<div class="loading-message"><i class="fa fa-spinner fa-spin"></i><span>&nbsp;&nbsp;&nbsp; Loading...</span></div>',
                "infoEmpty": "No record found",                
            },
            "ajax": {
                "url": baseUrl + '/admin/permissions/listing',
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
                        return '<a href="' + baseUrl + '/admin/permissions/' + row['id'] + '/edit" class="table-action table-action-edit" title="Edit"><i class="fa fa-pencil"></i></a>'
                                + '<a href="javascript:;" class="table-action table-action-delete delete-permission" data-id="' + row['id'] + '" title="Delete"><i class="fa fa-trash-o"></i></a>';
                    }
                }
            ]
        });

        table.on('order.dt search.dt draw.dt', function () {
            var info = table.page.info();
            var start = info.start;
            table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + start;
            });
        });

        jQuery(".dataTables_length select").select2({
            minimumResultsForSearch: -1,
            width: '60px'
        });
    };

    /**
     * delete an Permission
     * @returns {undefined}
     */
    var _deletePermission = function () {
        jQuery(document).on('click', '.delete-permission', function () {
            if (confirm("Delete?")) {
                var id = $(this).attr('data-id');
                var btn = $(this);
                jQuery.ajax({
                    url: baseUrl + '/admin/permissions/' + id,
                    dataType: 'json',
                    type: 'DELETE',
                    data: {
                    },
                    success: function (data, textStatus, jqXHR) {
                        if (data.code == 0) {
                            btn.parents('tr').addClass('hidden selected');
                            if (groupTable != null) {
                                groupTable.row('.selected')
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
    };

    /**
     * handle validation form
     * @returns {undefined}
     */
    var _handleValidation = function () {
        var form = jQuery('#permission-form');
        var error = jQuery('.alert-danger', form);

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
        $('select').select2({});
    }
    
    // public functions
    return {
        //main function
        init: function () {
            _loadPermissionTable();
            _deletePermission();
//            _handleValidation();
            initComponent();
        }

    };

};

jQuery(document).ready(function () {
    AppPermission().init();
});