<?php @session_start();
	// Control de sesión inciada
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {}
include_once("inc/conDB.php");
conexionDB();
mysqli_set_charset($_SESSION['con'], 'utf8');

$tbl_name ="ap_users";
$id_usuario = $_GET['id'];
$sql="SELECT * FROM $tbl_name WHERE ID='$id_usuario'";
$result=mysqli_query($_SESSION['con'], $sql);
$user_sql = mysqli_fetch_object($result);
$count=mysqli_num_rows($result);
if($count==0){
	//modificar para renvíe al perfil del usuario activo con aviso de usuario no encontrado
	header("Location: perfil.php?id=".$_SESSION['id']."&ec=1");
}else{}
	$md5email = trim ($user_sql->user_email); // "MyEmailAddress@example.com"
	$md5email = strtolower( $md5email ); // "myemailaddress@example.com"
	$emailhash= md5( $md5email );
function mostrar_lista() {
	$docs_tabla = mysqli_query($_SESSION['con'], "SELECT id, creado_ts, usuario_id, asignatura_id, file, size, nombre, descripcion, tipo, descargas, anonimo FROM `ap_documentos` WHERE `relacion`=0 AND `usuario_id`=".$_GET['id']." AND `anonimo`=0 AND NOT (`asignatura_id`= 4 OR `asignatura_id`= 38) ORDER BY `id` DESC");
	while ($seleccionada = mysqli_fetch_object($docs_tabla)) {
		$petnomuser = mysqli_query($_SESSION['con'], "SELECT user_nick FROM `ap_users` WHERE `ID` = ". $seleccionada->usuario_id . "");
		$consnomuser = mysqli_fetch_object($petnomuser);
		$sql_asignatura = mysqli_query($_SESSION['con'], "SELECT opcion, relacion FROM `ap_asignaturas` WHERE `ID` = ". $seleccionada->asignatura_id . "");
		$pet_asignatura = mysqli_fetch_object($sql_asignatura);
		$sql_curso = mysqli_query($_SESSION['con'], "SELECT opcion, relacion FROM `ap_cursos` WHERE `id` = ". $pet_asignatura->relacion . "");
		$pet_curso = mysqli_fetch_object($sql_curso);
		$sql_titulacion = mysqli_query($_SESSION['con'], "SELECT opcion FROM `ap_carreras` WHERE `id` = ". $pet_curso->relacion . "");
		$pet_titulacion = mysqli_fetch_object($sql_titulacion);
    echo '<tr>';
    echo '<td><center>' . urldecode($seleccionada->nombre) . '</center></td>';
    echo '<td><center>' . $pet_asignatura->opcion . '</center></td>';
		echo '<td><center>' . $pet_titulacion->opcion . '</center></td>';
		echo '<td><center>' . date("d-m-Y", strtotime($seleccionada->creado_ts)) . '</center></td>';
		echo '<td><center><a href="'. $seleccionada->file .'" onclick="window.open(\'descargar.php?id='. $seleccionada->id .'\')" target="_blank"><i class="fa fa-chevron-circle-down fa-2x"></i></a></center></td>';
		echo '</tr>';  
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
<div id="wrapper">
  <?php include "sidebar.php" ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
          <?php include "header.php" ?>
        <div class="container-fluid">
					<?php
						if (isset($_GET['ec']) && $_GET['ec']==1){
							echo "<div class=\"alert alert-warning\" role=\"alert\">El usuario que buscas no existe</div>";
						}else{}
					?>
          <!-- Content Row -->

          <div class="row">
						
            <!-- Pie Chart -->
            <div class="col-lg-4 col-md-12">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Perfil de <?php echo $user_sql->user_nick; ?></h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
									<div class="row">
									<div class="col-4">
                    <img class="img-profile rounded-circle" src="https://www.gravatar.com/avatar/<?php echo $emailhash; ?>?s=150" style="width:100%">
                  </div>
                  <div class="col-8">
                        <h4><?php echo $user_sql->user_nick; ?></h4>
                        <small>Miembro desde <?php echo date("d-m-Y", strtotime($user_sql->user_registered));?></small>
                        <p>
                          <a href="#" target="_blank"><i class="fas fa-globe fa-2x"></i></a>
													<a href="#" target="_blank"><i class="fab fa-twitter-square fa-2x"></i></a>
												</p>
                    </div>
									
                </div>
              </div>
            </div>
						</div>
            <!-- Area Chart -->
            <div class="col-lg-8 col-md-12">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Documentos de <?php echo $user_sql->user_nick; ?></h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Documento</th>
                      <th>Asignatura</th>
                      <th>Titulación</th>
					            <th>Fecha</th>
                      <th>Enlace</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Documento</th>
                      <th>Asignatura</th>
                      <th>Titulación</th>
					            <th>Fecha</th>
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
