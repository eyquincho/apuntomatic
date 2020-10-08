// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTableHome').DataTable( {
    "bSort": false,
		searching: false,
		paging: false,
		info: false,
	"language": {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ documentos",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando documentos del _START_ al _END_ de un total de _TOTAL_ documentos",
    "sInfoEmpty":      "Mostrando documentos del 0 al 0 de un total de 0 documentos",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ documentos)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
  }
  });
  $('#dataTable').DataTable( {
  "language": {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ documentos",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando documentos del _START_ al _END_ de un total de _TOTAL_ documentos",
    "sInfoEmpty":      "Mostrando documentos del 0 al 0 de un total de 0 documentos",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ documentos)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
  }
  });
	$('#dataTableProfile').DataTable( {
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ documentos",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando documentos del _START_ al _END_ de un total de _TOTAL_ documentos",
			"sInfoEmpty":      "Mostrando documentos del 0 al 0 de un total de 0 documentos",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ documentos)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
					"sFirst":    "Primero",
					"sLast":     "Último",
					"sNext":     "Siguiente",
					"sPrevious": "Anterior"
			},
			"oAria": {
					"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		 },
		 "order": [[ 3, "desc" ]]
	});
  });