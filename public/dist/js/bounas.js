// File: public/dist/js/bounas.js
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const deleteSelectedButton = document.getElementById('deleteSelected');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('click', toggleAllCheckboxes);
    }

    if (deleteSelectedButton) {
        deleteSelectedButton.addEventListener('click', deleteSelectedRecords);
    }

    function toggleAllCheckboxes() {
        const checkboxes = document.querySelectorAll('.bounasCheckbox');
        checkboxes.forEach((checkbox) => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    function deleteSelectedRecords() {
        const selectedIds = Array.from(document.querySelectorAll('.bounasCheckbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            // Get the current locale from Laravel
            const locale = document.documentElement.lang || 'en';
            const noRowMessage = locale === 'ar' ? 'لم يتم تحديد أي صف.' : 'No row selected.';
            alert(noRowMessage);
            return;
        }

        const locale = document.documentElement.lang || 'en';
        const confirmMessage = locale === 'ar' ? 'هل أنت متأكد من حذف المحدد؟' : 'Are you sure you want to delete the selection?';
        const errorMessage = locale === 'ar' ? 'حدث خطأ. يرجى المحاولة مرة أخرى.' : 'An error occurred. Please try again.';

        if (confirm(confirmMessage)) {
            fetch("/admin/bounas/delete-multiple", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(errorMessage);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(errorMessage);
            });
        }
    }
});

$(function () {
    // ✅ Single delete
    $(document).on('click', '.delete-btn', function () {
        let deleteId = $(this).data('id');

        Swal.fire({
            title: deleteTranslations.delete + '?',
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
                    type: 'GET',
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

    // ✅ Select All checkboxes
    $('#selectAll').on('click', function () {
        $('.bounasCheckbox').prop('checked', this.checked);
    });

    // ✅ Bulk delete
    $('#deleteSelected').on('click', function (e) {
        e.preventDefault();
        let selected = $('.bounasCheckbox:checked').map(function () {
            return $(this).val();
        }).get();

        if (selected.length === 0) {
            Swal.fire(deleteTranslations.error, deleteTranslations.noSelection, "warning");
            return;
        }

        Swal.fire({
            title: deleteTranslations.delete + '?',
            text: deleteTranslations.bulkConfirmation,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: deleteTranslations.delete,
            cancelButtonText: deleteTranslations.cancel
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteTranslations.bulkDeleteUrl,
                    type: 'POST',
                    data: {
                        ids: selected,
                        _token: deleteTranslations.csrf
                    },
                    success: function () {
                        $('.bounasCheckbox:checked').each(function () {
                            $(this).closest('tr').fadeOut();
                        });

                        Swal.fire({
                            title: deleteTranslations.deleted,
                            text: deleteTranslations.success,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function () {
                        Swal.fire(deleteTranslations.error, deleteTranslations.failed, "error");
                    }
                });
            }
        });
    });
});
