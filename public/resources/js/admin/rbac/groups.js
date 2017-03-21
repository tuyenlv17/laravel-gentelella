/**
 Permission's Group
 **/
var AppPermissionGroup = function () {

    var baseUrl = $('#site-meta').attr('data-base-url');
    var groupTable = null;

    function loadGroupTable() {
        groupTable = $('#groups-table').DataTable({
//            "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            "serverSide": true,
            "processing": true,
            "language": {
                "processing": '<div class="loading-message"><i class="fa fa-spinner fa-spin"></i><span>&nbsp;&nbsp;&nbsp; Loading...</span></div>',
                "infoEmpty": "No record found",
            },
            "ajax": {
                "url": baseUrl + '/admin/groups/listing',
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
                        return '<a href="' + baseUrl + '/admin/groups/' + row['id'] + '/edit" class="table-action table-action-edit" title="Edit"><i class="fa fa-pencil"></i></a>'
                                + '<a href="javascript:;" class="table-action table-action-delete delete-group" data-id="' + row['id'] + '" title="Delete"><i class="fa fa-trash-o"></i></a>';
                    }
                }
            ]
        });

        groupTable.on('order.dt search.dt draw.dt', function () {
            var info = groupTable.page.info();
            var start = info.start;
            groupTable.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + start;
            });
        });

        $(".dataTables_length select").select2({
            minimumResultsForSearch: -1,
            width: '60px'
        });
    }

    /**
     * delete an Group
     * @returns {undefined}
     */
    function deleteGroup() {
        $(document).on('click', '.delete-group', function () {
            if (confirm("Delete?")) {
                var id = $(this).attr('data-id');
                var btn = $(this);
                jQuery.ajax({
                    url: baseUrl + '/admin/groups/' + id,
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
    }

    function initComponent() {
        $('select').select2({});
    }

    // public functions
    return {
        //main function
        init: function () {
            loadGroupTable();
            deleteGroup();
            initComponent();
        }

    };

};

$(document).ready(function () {
    AppPermissionGroup().init();
});