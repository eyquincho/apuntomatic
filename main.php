<?php @session_start();
	// Control de sesión inciada
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {}
include_once("inc/conDB.php");
conexionDB();
mysqli_set_charset($_SESSION['con'], 'utf8');

function mostrar_lista() {
	$docs_tabla = mysqli_query($_SESSION['con'], "SELECT id, usuario_id, asignatura_id, file, size, nombre, descripcion, tipo, descargas, anonimo FROM `ap_documentos` WHERE `relacion`=0 AND NOT (`asignatura_id`= 4 OR `asignatura_id`= 38) ORDER BY `id` DESC LIMIT 10");
	while ($seleccionada = mysqli_fetch_object($docs_tabla)) {
		$petnomuser = mysqli_query($_SESSION['con'], "SELECT user_nick FROM `ap_users` WHERE `ID` = ". $seleccionada->usuario_id . "");
		$consnomuser = mysqli_fetch_object($petnomuser);
		$sql_asignatura = mysqli_query($_SESSION['con'], "SELECT opcion, relacion FROM `ap_asignaturas` WHERE `ID` = ". $seleccionada->asignatura_id . "");
		$pet_asignatura = mysqli_fetch_object($sql_asignatura);
		$sql_curso = mysqli_query($_SESSION['con'], "SELECT opcion, relacion FROM `ap_cursos` WHERE `id` = ". $pet_asignatura->relacion . "");
		$pet_curso = mysqli_fetch_object($sql_curso);
		$sql_titulacion = mysqli_query($_SESSION['con'], "SELECT opcion FROM `ap_carreras` WHERE `id` = ". $pet_curso->relacion . "");
		$pet_titulacion = mysqli_fetch_object($sql_titulacion);
		if(isset($seleccionada->anonimo) && $seleccionada->anonimo == '1')
      {
      $uploader = "Anónimo";
      }  
      else {
      $uploader = $consnomuser->user_nick;
      }
    echo '<tr>';
    echo '<td><center>' . urldecode($seleccionada->nombre) . '</center></td>';
    echo '<td><center>' . $pet_asignatura->opcion . '</center></td>';
		echo '<td><center>' . $pet_titulacion->opcion . '</center></td>';
		echo '<td><center>' . $uploader . '</center></td>';
		echo '<td><center><a href="'. $seleccionada->file .'" onclick="window.open(\'descargar.php?id='. $seleccionada->id .'\')" target="_blank"><i class="fa fa-chevron-circle-down fa-2x"></i></a></center></td>';
		echo '</tr>';  
  }
}

// Publicidad
$sql_anuncio = mysqli_query($_SESSION['con'], "SELECT * FROM `ap_publicidad` WHERE `aprobado`= 1 ORDER BY RAND() LIMIT 1");
$pet_anuncio = mysqli_fetch_object($sql_anuncio);
$mostrar_anuncio_url = $pet_anuncio->url;
$mostrar_anuncio_img = $pet_anuncio->imagen;

// Datos para la cabecera de la página principal
// Archivos subidos por el usuario
$sql_head_subidas = "SELECT COUNT(id) AS subidas FROM `ap_documentos` WHERE `usuario_id`= ".$_SESSION['id']."";
$result_head_subidas = mysqli_query($_SESSION['con'],$sql_head_subidas);
$head_subidas = mysqli_fetch_assoc($result_head_subidas);

// Descargas de los archivos del usuario
$sql_head_descargas = "SELECT SUM(descargas) AS descargas FROM `ap_documentos` WHERE `usuario_id`= ".$_SESSION['id']."";
$result_head_descargas = mysqli_query($_SESSION['con'],$sql_head_descargas);
$head_descargas = mysqli_fetch_assoc($result_head_descargas);


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
  <link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
  <?php include "sidebar.php" ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
          <?php include "header.php" ?>
        <div class="container-fluid">

          <!-- Content Row -->
          <div class="row">

            <!-- Tarjeta subidas -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Archivos subidos</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $head_subidas['subidas']; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-upload fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tarjeta descargas -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Descargas de tus archivos</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $head_descargas['descargas']; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-download fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tarjeta votos positivos, desactivado
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Votos positivos</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">##</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-star fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->

            <!-- Tarjeta ranking, desactivado
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Ranking</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">##</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-trophy fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
          </div>

          <!-- Content Row -->

          <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Última actividad </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="table-responsive">
                <table class="table table-bordered" id="dataTableHome" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Documento</th>
                      <th>Asignatura</th>
                      <th>Titulación</th>
					            <th>Usuario</th>
                      <th>Enlace</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Documento</th>
                      <th>Asignatura</th>
                      <th>Titulación</th>
					            <th>Usuario</th>
                      <th>Enlace</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php mostrar_lista(); ?>
                  </tbody>
                </table>
              </div>
                </div>
              </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Publicidad</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <a href="<?php echo $mostrar_anuncio_url; ?>" target="_blank" ><img src="<?php echo $mostrar_anuncio_img; ?>" style="width:100%" /></a>
				  ¿Quieres mostrar algo aqui? Consulta la sección <a href="publicidad.php">Publicidad</a>
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