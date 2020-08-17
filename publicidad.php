<?php @session_start();
	// Control de sesión inciada
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {}
include_once("inc/conDB.php");
conexionDB();
mysqli_set_charset($_SESSION['con'], 'utf8');
require("inc/class.upload.php");
function GuardarAnuncio () {
	if (isset($_POST['nuevo_anuncio_enviar'])){
		// Tratamiento imagen
		//Implementar class.upload.php
			//https://github.com/verot/class.upload.php
			$handle = new \Verot\Upload\upload($_FILES['nuevo_anuncio_imagen']);
			if ($handle->uploaded) {
			  $handle->file_name_body_pre		= 'anuncio_';
			  $handle->file_safe_name 			= true;
			  $handle->image_resize         = true;
			  $handle->image_x              = 500;
			  $handle->image_y              = 500;
			  $handle->image_ratio        	= true;
			  $handle->process('img/anuncios/');
			  if ($handle->processed) {
				$imagen_anuncio = $handle->file_dst_pathname;
				$handle->clean();
			  } else {
				echo 'error : ' . $handle->error;
			  }
			}
		// FIN tratamiento imagen
		if (!empty($_POST['nuevo_anuncio_url'])) {
			$url_anuncio = mysqli_real_escape_string($_SESSION['con'], $_POST['nuevo_anuncio_url']);
			}else {
				$url_anuncio = "#";
			}
		$descripcion_anuncio = mysqli_real_escape_string($_SESSION['con'], $_POST['nuevo_anuncio_descripcion']);
		$fecha_inicio = $_POST['nuevo_anuncio_inicio'];
		$fecha_final = $_POST['nuevo_anuncio_final'];
		$usuario_anuncio = $_SESSION["nick"];
		$qry = "INSERT INTO ap_publicidad ( usuario, imagen, url, descripcion, inicio, fin, aprobado ) VALUES
		('$usuario_anuncio','$imagen_anuncio', '$url_anuncio','$descripcion_anuncio','$fecha_inicio','$fecha_final', '0')";
		mysqli_query($_SESSION['con'], $qry);
		echo "<div class=\"alert alert-success\" role=\"alert\">El anuncio se ha enviado para aprobación</div>";
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
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
          <?php include "header.php" ?>
        <div class="container-fluid">
					<?php GuardarAnuncio(); ?>

          <!-- Content Row -->

          <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Publicitarse</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
					Con más de 1500 usuarios registrados y +5000 visitas mensuales, Apuntomatic puede ser un buen escaparate si tienes algo que contar.</br>
					Y de forma <strong>totalmente gratuita</strong>.</br>
					Rellena el formulario de la derecha con la imagen a mostrar (de 500x500 px, preferentemente), el enlace al que apunta (si es que apunta a algún sitio) y un texto descriptivo para que sepamos de qué nos estás hablando.(No se mostrará)</br>
					Todas las solicitudes se revisan a mano, por lo que pueden tardar un poco en aparecer. Cualquier duda, te la comentaremos por correo electrónico.</br>
					Apuntomatic se reserva el derecho a no publicar o eliminar cualquier campaña que considere inapropiada.</br>
					<hr>
					Para peticiones especiales, puedes contactarnos por email en <strong>hola@apuntomatic.com</strong>
                </div>
              </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Enviar propuesta</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
					<form class="form" id="nuevo-anuncio" action="publicidad.php" enctype="multipart/form-data" method="post" name="nuevo-anuncio">
						<div class="form-group">
							<label for="Imagen">Imagen (Las dimensiones óptimas son 500x500 px)</label>
							<input type="file" class="form-control-file" name="nuevo_anuncio_imagen" required>
						</div>
						<div class="form-group">
							<label for="url">URL</label>
							<input type="text" class="form-control" name="nuevo_anuncio_url" placeholder="Dirección a la que apuntará la imagen (puede estar vacío)">
						</div>
						
						<div class="form-group">
							<label for="Descripcion">Descripción</label>
							<textarea class="form-control" name="nuevo_anuncio_descripcion" placeholder="Explícanos brevemente qué nos envías. No se mostrará con el anuncio." rows="3" required></textarea>
						</div>
						<div class="form-group">
							<label for="nuevo_anuncio_inicio">Fecha inicio:</label>
							<input type="date" id="nuevo_anuncio_inicio" name="nuevo_anuncio_inicio" required>
						</div>
						<div class="form-group">
							<label for="nuevo_anuncio_final">Fecha final:</label>
							<input type="date" id="nuevo_anuncio_final" name="nuevo_anuncio_final" required>
						</div>
						<div class="form-check">
							<input type="checkbox" class="form-check-input" id="Condiciones" required>
							<label class="form-check-label" for="Condiciones">Acepto que he leído y entendido las condiciones</label>
						</div>
						<hr>
						<button type="submit" class="btn btn-primary" name="nuevo_anuncio_enviar">Enviar para aprobación</button>
					</form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Apuntomatic &copy; 2019 - GitHub</span>
          </div>
        </div>
      </footer>
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
