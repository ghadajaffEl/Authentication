$('.dataTable-product').dataTable({
    dom: '<"html5buttons">lTfgitprB',
    "lengthMenu": [10, 25, 50, 100],
    "language": {
        "zeroRecords": "Aucun produit  n'est trouvé",
        "infoFiltered": "(filtré de  _MAX_ produit)",
        "loadingRecords": "Loading...",
        "processing": '<div class="loader">\n' +
            ' <svg class="circular" viewBox="25 25 50 50">\n' +
            ' <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>\n' +
            ' </svg>\n' +
            ' </div>',
        "search": "Rechercher:",
        "lengthMenu": "Afficher _MENU_ produit",
        "paginate": {
            "first": "First",
            "last": "Last",
            "next": "Suivant",
            "previous": "Précédent"
        },
    },

    "columnDefs": [

        {"name": "id", "width": "30%", "targets": 0},
        {"name": "name", "width": "30%", "targets": 1, },
        {"name": "price", "width": "20%", "targets": 2},
    ],

    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": '/productListDataTable',
        "type": "POST"
    },

    "paging": true,
    "info": true,
    "searching": true,
    "pageLength": 10,
});


