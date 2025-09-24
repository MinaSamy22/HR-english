$(document).ready(function() {
    // Make table sortable
    const sortableList = document.getElementById('sortable-criteria');
    if (sortableList) {
        new Sortable(sortableList, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onStart: function(evt) {
                evt.item.style.opacity = '0.5';
            },
            onEnd: function(evt) {
                evt.item.style.opacity = '1';

                const rows = Array.from(sortableList.querySelectorAll('tr[data-id]'));
                const orderData = rows.map((row, index) => ({
                    id: parseInt(row.getAttribute('data-id')),
                    sort_order: index + 1
                }));

                $.ajax({
                    url: criteriaTranslations.updateUrl,
                    method: 'POST',
                    data: {
                        _token: criteriaTranslations.token,
                        criteria: orderData
                    },
                    success: function(response) {
                        showToast(criteriaTranslations.orderUpdated, 'success');
                    },
                    error: function(xhr, status, error) {
                        showToast(criteriaTranslations.orderFailed, 'error');
                        setTimeout(() => {
                            if (confirm(criteriaTranslations.restoreConfirm)) {
                                window.location.reload();
                            }
                        }, 2000);
                    }
                });
            }
        });
    }

    // Hover effects
    $('#sortable-criteria').on('mouseenter', 'tr', function() {
        $(this).addClass('table-active');
    }).on('mouseleave', 'tr', function() {
        $(this).removeClass('table-active');
    });
});

// Toast helper
function showToast(message, type = 'info') {
    const toastClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';

    const toastTitle = {
        'success': criteriaTranslations.success,
        'error': criteriaTranslations.error,
        'warning': criteriaTranslations.warning,
        'info': criteriaTranslations.info
    }[type] || criteriaTranslations.info;

    const toast = $(`
        <div class="alert ${toastClass} alert-dismissible fade show position-fixed"
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <strong>${toastTitle}</strong> ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);

    $('body').append(toast);

    setTimeout(() => {
        toast.alert('close');
    }, 5000);
}

// Delete handler
$(document).on('click', '.delete-btn', function () {
    let deleteId = $(this).data('id');

    Swal.fire({
        title: criteriaTranslations.delete,
        text: criteriaTranslations.confirmation,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: criteriaTranslations.delete,
        cancelButtonText: criteriaTranslations.cancel
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: criteriaTranslations.deleteUrl + "/" + deleteId,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: criteriaTranslations.token
                },
                success: function () {
                    $('button.delete-btn[data-id="' + deleteId + '"]').closest('tr').fadeOut();
                    Swal.fire({
                        title: criteriaTranslations.deleted,
                        text: criteriaTranslations.successMsg,
                        icon: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function () {
                    Swal.fire({
                        title: criteriaTranslations.error,
                        text: criteriaTranslations.failed,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        }
    });
});
