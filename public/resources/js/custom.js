var Custom = {
    /**
     * delete a record
     * @returns {undefined}
     */
    deleteRecord: function (btnDelete, url, table) {

        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "50000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        $.confirm({
            icon: 'fa fa-warning',
            title: Lang.Message.confirmq,
            content: Lang.Message.deleteConfirm,
            type: 'red',
            draggable: true,
            autoClose: 'cancel|10000',
            closeIcon: true,
//                escapeKey: true,
//                backgroundDismiss: true,
            buttons: {
                delete: {
                    btnClass: 'btn-danger',
                    text: Lang.Message.delete,
                    action: function () {
                        $.ajax({
                            url: url,
                            dataType: 'json',
                            type: 'DELETE',
                            data: {
                            },
                            success: function (data, textStatus, jqXHR) {
                                if (data.code == 0) {
                                    $(btnDelete).closest('tr').addClass('hidden selected');
                                    if (table != null) {
                                        table.row('.selected')
                                                .remove()
                                                .draw(false);
                                    }
                                    toastr['success'](Lang.Message.success);
                                } else {
                                    toastr['error'](Lang.Message.error);
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                toastr['error'](Lang.Message.error);
                            }
                        });
                    }
                },
                cancel: {
                    text: Lang.Message.cancel,
                },
            }
        });
    }
};

if (!String.prototype.format) {
    String.prototype.format = function () {
        var args = arguments;
        return this.replace(/{(\d+)}/g, function (match, number) {
            return typeof args[number] != 'undefined' ? args[number] : match;
        });
    };
}