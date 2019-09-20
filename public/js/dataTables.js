// DataTables
$(function () {
    $('.datatable').DataTable({
        language    	: {
            url     : 'http://cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
        },  
        paging      	: true,
        lengthChange	: true,
        searching   	: true,
        ordering    	: true,
        info        	: true,
        autoWidth   	: true,
        lengthMenu		: [
            [ 10, 25, 50, 100, -1 ],
            [ '10 linhas', '25 linhas', '50 linhas', '100 linhas', 'Mostar todos' ]
        ],
        pageLength  	: 25,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
})
