$(document).ready(function() {
    $('table#categories, table#albums').dataTable({
        order: [2, 'desc'],
        aoColumnDefs: [{
            'bSortable': false,
            'aTargets': ['action']
        }],
        language: {
            search: 'Search: '
        }
    });
    $('table#users').dataTable({
        order: [3, 'desc'],
        aoColumnDefs: [{
            'bSortable': false,
            'aTargets': ['action']
        }],
        language: {
            search: 'Search: '
        }
    });
    $('div.dataTables_filter input').focus();

    $('.popup').magnificPopup({
        type: 'image',
        gallery: {enabled: true},
        removalDelay: 300,
        mainClass: 'mfp-fade'
    });
});
