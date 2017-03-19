/**
 Permission Module
 **/
var AppPermission = function () {
    
    var baseUrl = jQuery('#site-meta').attr('data-base-url');
    var table = null;
    $.fn.select2.defaults.set("theme", "bootstrap");
    // private functions & variables

    var _loadPermissionTable = function () {
        table = jQuery('#permission-table').DataTable({
            "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            "serverSide": true,
            "processing": true,
            "language": {
                "processing": '<div class="loading-message"><img src="' + baseUrl + '/global/img/loading-spinner-grey.gif"/><span>&nbsp;&nbsp;&nbsp; Loading...</span></div>',
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
                    targets: [0, 4],
                    sortable: false
                },
                {
                    targets: [4],
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
            var btn = jQuery(this);
            var id = btn.attr('data-id');

            bootbox.dialog({
                message: 'Are you sure to delete this record?',
                title: 'Notification',
                buttons: {
                    success: {
                        label: 'Cancel',
                        className: 'btn btn-default'
                    },
                    danger: {
                        label: 'Delete',
                        className: 'btn btn-primary',
                        callback: function () {
                            jQuery.ajax({
                                url: baseUrl + '/admin/permissions/' + id,
                                dataType: 'json',
                                type: 'DELETE',
                                data: {
                                },
                                success: function (data, textStatus, jqXHR) {
                                    if (data.code == 0) {
                                        btn.parents('tr').addClass('hidden selected');
                                        if (table != null) {
                                            table.row('.selected')
                                                    .remove()
                                                    .draw(false);
                                        }
                                        toastr.success('Delete record successfully!', 'Norification');
                                    } else {
                                        toastr.error('Error, please try again later!', 'Norification');
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    toastr.error('Error, please try again later!', 'Norification');
                                }
                            });
                        }
                    }
                }
            });
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


    // public functions
    return {
        //main function
        init: function () {
            _loadPermissionTable();
            _deletePermission();
            _handleValidation();
        }

    };

};

jQuery(document).ready(function () {
    AppPermission().init();
});