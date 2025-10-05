$(document).on('click', '.delete-btn', function () {
    let deleteId = $(this).data('id');

    Swal.fire({
        title: deleteTranslations.delete,
        text: deleteTranslations.confirmation,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: deleteTranslations.delete,
        cancelButtonText: deleteTranslations.cancel
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: deleteTranslations.deleteUrl + "/" + deleteId,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: deleteTranslations.token
                },
                success: function () {
                    $('button.delete-btn[data-id="' + deleteId + '"]').closest('tr').fadeOut();

                    Swal.fire({
                        title: deleteTranslations.deleted,
                        text: deleteTranslations.success,
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function () {
                    Swal.fire({
                        title: deleteTranslations.error,
                        text: deleteTranslations.failed,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        }
    });
});
