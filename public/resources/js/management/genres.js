/**
 Genre Module
 **/
var AppGenre = function () {
    
    var baseUrl = $('#site-meta').attr('data-base-url');
    var genreTable = null;    

    var loadGenreTable = function () {
        genreTable = $('#genres-table').DataTable({
            "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable table-responsive't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            "serverSide": true,
            "processing": true,
            responsive: true,
            "language": {
                "processing": '<div class="loading-message"><i class="fa fa-spinner fa-spin"></i><span>&nbsp;&nbsp;&nbsp; Loading...</span></div>',
                "infoEmpty": "No record found",                
            },
            "ajax": {
                "url": baseUrl + '/management/genres/listing',
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
                    'total_movies': 'total_movies'
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
                        return '<a href="' + baseUrl + '/management/genres/' + row['id'] + '/edit" class="table-action table-action-edit" title="Edit"><i class="fa fa-pencil"></i></a>'
                                + '<a href="javascript:;" class="table-action table-action-delete delete-genre" data-id="' + row['id'] + '" title="Delete"><i class="fa fa-trash-o"></i></a>';
                    }
                }
            ]
        });

        genreTable.on('order.dt search.dt draw.dt', function () {
            var info = genreTable.page.info();
            var start = info.start;
            genreTable.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + start;
            });
        });

        $(".dataTables_length select").select2({
            minimumResultsForSearch: -1,
            width: '60px'
        });
    };

    /**
     * delete an Genre
     * @returns {undefined}
     */
    var deleteGenre = function () {
        $(document).on('click', '.delete-genre', function () {
            if (confirm("Delete?")) {
                var id = $(this).attr('data-id');
                var btn = $(this);
                jQuery.ajax({
                    url: baseUrl + '/management/genres/' + id,
                    dataType: 'json',
                    type: 'DELETE',
                    data: {
                    },
                    success: function (data, textStatus, jqXHR) {
                        if (data.code == 0) {
                            btn.parents('tr').addClass('hidden selected');
                            if (genreTable != null) {
                                genreTable.row('.selected')
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
    var handleValidation = function () {
        var form = $('#genre-form');
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
    };

    
    function initComponent() {
        $('.select2-single').select2({});
    }
    
    // public functions
    return {
        //main function
        init: function () {
            loadGenreTable();
            deleteGenre();
//            _handleValidation();
//            initComponent();
        }

    };

};

$(document).ready(function () {
    AppGenre().init();
});