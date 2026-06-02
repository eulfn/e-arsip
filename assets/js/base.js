/**
 * Base JavaScript for E-Archive
 * Handles global initializations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTables for elements with .js-datatable class
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('.js-datatable').each(function() {
            if (!$.fn.DataTable.isDataTable(this)) {
                $(this).DataTable({
                    "pageLength": 10,
                    "order": [[0, 'desc']],
                    "language": {
                        "paginate": {
                            "next": '<i class="bx bx-chevron-right"></i>',
                            "previous": '<i class="bx bx-chevron-left"></i>'
                        }
                    },
                    "columnDefs": [{
                        "targets": "no-sort",
                        "orderable": false
                    }]
                });
            }
        });
    }

    // Confirmation
    const confirmActions = document.querySelectorAll('.js-confirm');
    confirmActions.forEach(element => {
        element.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || 'Are you sure you want to perform this action?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
});
