// public/dist/js/branches.js

$(document).on('click', '.delete-btn', function () {
    let deleteId = $(this).data('id');
    let deleteUrl = $(this).data('url');

    Swal.fire({
        title: deleteTranslations.title,
        text: deleteTranslations.text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: deleteTranslations.confirm,
        cancelButtonText: deleteTranslations.cancel
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: deleteUrl,
                type: 'GET', // ✅ your route uses GET
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
