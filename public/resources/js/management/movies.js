/**
 Movie Module
 **/
var AppMovie = function () {

    var baseUrl = $('#site-meta').attr('data-base-url');
    var movieTable = null;

    function loadMovieTable() {

        movieTable = $('#movies-table').DataTable({
            "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable table-responsive't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            "serverSide": true,
            "processing": true,
            "bDestroy": true,
            "language": {
                "processing": '<div class="loading-message"><i class="fa fa-spinner fa-spin"></i><span>&nbsp;&nbsp;&nbsp; Loading...</span></div>',
                "infoEmpty": "No record found",
            },
            "ajax": {
                "url": baseUrl + '/management/movies/listing',
                "data": {genres: $('.genres-filter').val()},
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
                    'data': 'title'
                },
                {
                    'data': 'year'
                },
                {
                    'data': 'price'
                },
                {
                    'data': 'dis_price'
                },
                {
                    'data': 'plot'
                },
                {
                    'data': 'genres'
                },
                {
                    'data': null,
                    'sortable': false,
                    'className': 'text-center'
                }
            ],
            columnDefs: [
                {
                    targets: [7],
                    width: '60px',
                    sortable: false,
                    render: function (data, type, row) {
                        return '<a href="' + baseUrl + '/management/movies/' + row['id'] + '/edit" class="table-action table-action-edit" title="Edit"><i class="fa fa-pencil"></i></a>'
                                + '<a href="javascript:;" class="table-action table-action-delete delete-movie" data-id="' + row['id'] + '" title="Delete"><i class="fa fa-trash-o"></i></a>';
                    }
                }
            ]
        });

        movieTable.on('order.dt search.dt draw.dt', function () {
            var info = movieTable.page.info();
            var start = info.start;
            movieTable.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
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
     * delete an Movie
     * @returns {undefined}
     */
    function deleteMovie() {
        $(document).on('click', '.delete-movie', function () {
            var btn = $(this);
            var id = btn.attr('data-id');

            if (confirm("Delete?")) {
                jQuery.ajax({
                    url: baseUrl + '/management/movies/' + id,
                    dataType: 'json',
                    type: 'DELETE',
                    data: {
                    },
                    success: function (data, textStatus, jqXHR) {
                        if (data.code == 0) {
                            btn.parents('tr').addClass('hidden selected');
                            if (movieTable != null) {
                                movieTable.row('.selected')
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
        var form = $('#movie-form');
        var error = $('.alert-danger', form);
        var isPwRequired = ($('#is-adding-movie').val() === 'true');
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
        $('.genres-filter').change(function () {
            loadMovieTable();
        });
        $('input[type=checkbox]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    }

    // public functions
    return {
        //main function
        init: function () {
            loadMovieTable();
            deleteMovie();
//            handleValidation();
            initComponent();
        }

    };

};

$(document).ready(function () {
    AppMovie().init();
});