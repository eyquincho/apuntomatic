<?php @session_start();
	// Control de sesión inciada
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {}
include_once("inc/conDB.php");
conexionDB();
mysqli_set_charset($_SESSION['con'], 'utf8');
  if($_SESSION["admin"]==0){
    header("Location: main.php");
    die();
  } else {}

//Lista Denuncias
function admin_mostrar_lista_denuncias() {
    $sql_tabla_denuncias = mysqli_query($_SESSION['con'], "SELECT * FROM `ap_denuncias` ORDER BY `id` DESC");    
	while ($denuncia = mysqli_fetch_object($sql_tabla_denuncias)) {
		$archivo_denunciado = mysqli_query($_SESSION['con'], "SELECT * FROM `ap_documentos` WHERE `ID` = ". $denuncia->den_archivo . "");
		$archivo = mysqli_fetch_object($archivo_denunciado);
    echo '<tr class="text-center">';
    echo '<td>' . urldecode($archivo->nombre) . '</td>';
    echo '<td>' . $denuncia->den_denunciado . '</td>';
    echo '<td>' . $denuncia->den_denunciante . '</td>';
    echo '<td>' . date("d-m-Y", strtotime($denuncia->den_fecha)) . '</td>';
    echo '<td><a href="' . $archivo->file . '"><i class="fas fa-download"></i></a>'; ?>
			<a href="#" title="Resolver Denuncia" data-target="#resolver_denuncia<?php echo $denuncia->id;?>" data-toggle="modal"><i class="fas fa-question-circle"></i></a></td>
				<!-- Modal editar documento -->
				<div class="modal fade" id="resolver_denuncia<?php echo $denuncia->id;?>" tabindex="-1" role="dialog" aria-labelledby="ModalDenuncia" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="titulo-modal">Resolver denuncias </h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							</div>
							<div class="modal-body">
                <p><strong>Documento: </strong><?php echo urldecode($archivo->nombre);?></p>
                <p><strong>Motivo: </strong><?php echo urldecode($denuncia->den_motivo);?></p>
							</div>
							<div class="modal-footer">
								<a class="btn btn-danger" href="#" data-target="#EliminarDocumento<?php echo $denuncia->den_Archivo;?>" data-toggle="modal">Borrar archivo</a>
                <a class="btn btn-info" href="#" data-target="#IgnorarDenuncia<?php echo $denuncia->den_Archivo;?>" data-toggle="modal">Ignorar denuncia</a>
								<a href="#" class="btn" data-dismiss="modal">Cancelar</a>
							</div>
						</div>
					</div>
				</div>			
      <?php
		echo '</td></tr>'; 
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Apuntomatic</title>

  <!-- Llamadas CSS -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="css/sb-admin-2.css" rel="stylesheet">
  <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

  <?php include "sidebar.php" ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!----------------->
		<!----Cabecera----->
		<!----------------->
    <?php include "header.php" ?>
        <!----------------->
		<!---Fin Cabecera-->
		<!----------------->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Content Row -->
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Denuncias no resueltas</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">135</div>
                    </div>
                    <div class="col-auto sinleer">
                      <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Anuncios pendientes aprobación</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">1.235</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-comment-alt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Row -->

          <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Denuncias</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Archivo</th>
                      <th>Denunciado</th>
                      <th>Denunciante</th>
                      <th>Fecha</th>
                      <th>Opciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Archivo</th>
                      <th>Denunciado</th>
                      <th>Denunciante</th>
                      <th>Fecha</th>
                      <th>Opciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php admin_mostrar_lista_denuncias(); ?>                    
                  </tbody>
                </table>
              </div>
                </div>
              </div>
            </div>

            <!-- Area Chart -->
            <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Anuncios</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Doc</th>
                      <th>Usuario</th>
                      <th>Documento</th>
                      <th>Asignatura</th>
                      <th>Titulación</th>
                      <th>Enlace</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Doc</th>
                      <th>Usuario</th>
                      <th>Documento</th>
                      <th>Asignatura</th>
                      <th>Titulación</th>
                      <th>Enlace</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <tr>
                      <td>Tiger Nixon</td>
                      <td>System Architect</td>
                      <td>Edinburgh</td>
                      <td>61</td>
                      <td>2011/04/25</td>
                      <td><center><i class="fas fa-file-download"></i> 14 Mb</center></td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
                </div>
              </div>
            </div>
            <!-- Area Chart -->
            <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Usuarios</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Nick</th>
                      <th>Email</th>
                      <th>Fecha registro</th>
                      <th>Enlace</th>
                      <th>Subidas</th>
                      <th>Descargas</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Nick</th>
                      <th>Email</th>
                      <th>Fecha registro</th>
                      <th>Enlace</th>
                      <th>Subidas</th>
                      <th>Descargas</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <tr>
                      <td>Tiger Nixon</td>
                      <td>System Architect</td>
                      <td>Edinburgh</td>
                      <td>61</td>
                      <td>2011/04/25</td>
                      <td><center><i class="fas fa-file-download"></i> 14 Mb</center></td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
                </div>
              </div>
            </div>
            <!-- Area Chart -->
            <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Documentos</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Asignatura</th>
                      <th>Fecha</th>
                      <th>Nombre</th>
                      <th>Descargas</th>
                      <th>Anonimo</th>
                      <th>Tipo</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>Asignatura</th>
                      <th>Fecha</th>
                      <th>Nombre</th>
                      <th>Descargas</th>
                      <th>Anonimo</th>
                      <th>Tipo</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <tr>
                      <td>Tiger Nixon</td>
                      <td>System Architect</td>
                      <td>Edinburgh</td>
                      <td>61</td>
                      <td>2011/04/25</td>
                      <td><center><i class="fas fa-file-download"></i> 14 Mb</center></td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php include "footer.php" ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Boton volver arriba-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- JavaScript Externo -->
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.js"></script>
  <script src="js/jquery.dataTables.min.js"></script>
  <script src="js/dataTables.bootstrap4.min.js"></script>

  <!-- JavaScript propio -->
  <script src="js/tablas-apuntomatic.js"></script>

</body>

</html>
