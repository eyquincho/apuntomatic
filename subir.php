<?php @session_start();
	// Control de sesión inciada
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {}
require_once("inc/conDB.php");
conexionDB();
mysqli_set_charset($_SESSION['con'], 'utf8'); ?>
<?php
		function generaCarreras()
		{
			$consulta = mysqli_query($_SESSION['con'], "SELECT `id`, `opcion` FROM `ap_carreras`");
			// Voy imprimiendo el primer select compuesto por los carreras
			while($registro=mysqli_fetch_row($consulta))
			{
				echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
			}
		}
		function tramitarsubida () {
		if(isset($_FILES['archivo'])){
		
 		$uploaddir = "documentos/";
 		// 
		$file = time() . '-' . $_FILES['archivo']['name'];
		$file = preg_replace('/[^0-9a-zA-Z-_.]+/','',$file);
 		$uploadfile = $uploaddir . $file;
 		$error = $_FILES['archivo']['error'];
 		$subido = false;
 		$tipoch = str_replace("application/","",$_FILES["archivo"]["type"]);
		switch ($tipoch) {
		    case "vnd.openxmlformats-officedocument.wordprocessingml.document":
		        $tipo = "docx";
		        break;
		    case "vnd.ms-excel":
		        $tipo = "xls";
		        break;
		    case "vnd.openxmlformats-officedocument.spreadsheetml.sheet":
		        $tipo = "xlsx";
		        break;
		    case "vnd.ms-powerpoint":
		        $tipo = "ppt";
		        break;
		    case "ax-rar-compressed":
		        $tipo = "rar";
		        break;
		}
		$n = $_SESSION["nick"];
		$query = "SELECT `ID` FROM `ap_users` WHERE `user_nick`='$n'";
		$query2 = mysqli_query($_SESSION['con'], $query);
		$fila = mysqli_fetch_array($query2);
		$user_id = $fila["ID"];
		$titulo = urlencode($_POST['titulo']);
		$asignatura = $_POST['asignaturas'];
		$descripcion = urlencode($_POST['descripcion']);
		$size = $_FILES["archivo"]["size"] / 1024;
		if(isset($_POST['anon'])){
		$anonimo = true;}
		else {
		$anonimo = false;}
		if(isset($_POST['boton']) && $error==UPLOAD_ERR_OK) { 
		   $subido = copy($_FILES['archivo']['tmp_name'], $uploadfile); 
		   $check = $subido && !empty($titulo) && !empty($asignatura);
		   $qry = "INSERT INTO ap_documentos ( usuario_id, asignatura_id, creado_ts, file, size, nombre, descripcion, tipo, anonimo ) VALUES
			('$user_id','$asignatura', CURDATE(), '$uploadfile','$size','$titulo','$descripcion', '$tipo', '$anonimo')";
			mysqli_query($_SESSION['con'], $qry);
			$update_user = "UPDATE ap_users SET user_files=(user_files + 1) WHERE id='$user_id'";
			mysqli_query($_SESSION['con'], $update_user);
			echo "<div class=\"alert alert-success\" role=\"alert\">Archivo subido correctamente</div>";  }
		else {echo "<div class=\"alert alert-success\" role=\"alert\">Ha ocurrido un error al subir el archivo</div>";}
		}}
?>
<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Apuntomatic - Subir</title>

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
				<?php tramitarsubida(); ?>
		<form class="form" id="form1" action="subir.php" enctype="multipart/form-data" method="post" name="form">
          <!-- Content Row -->
          <div class="row">
            <div class="col-lg-4">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Selecciona una titulación</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
					<div class="form-group" >
						<label class="sr-only" for="carreras">Elige titulación</label>
						<select class="custom-select col-lg-12" name="carreras" id="carreras" onChange="cargaContenido(this.id)">
							<option value='0'>Selecciona Titulación</option>
							<?php generaCarreras(); ?>
						</select>
					</div>
					<div class="form-group" >
						<label class="sr-only" for="cursos">Curso</label>
						<select class="custom-select col-lg-12" name="cursos" id="cursos" disabled>
							<option value='0'>Selecciona Curso</option>
						</select>
					</div>
					<div class="form-group" >
						<label class="sr-only" for="asignaturas">Asignatura</label>
						<select class="custom-select col-lg-12" id="asignaturas" class="form-control" disabled>
							<option value='0'>Selecciona Asignatura</option>
						</select>
				  </div>
                </div>
              </div>
            </div>
            <!-- Area Chart -->
            <div class="col-lg-8">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Documento</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
					<div class="form-group">
						<label>Título</label>
						<input type="text" class="form-control input-lg" name="titulo"  placeholder="Titulo" required>
					</div>
					<div class="form-group">
					<label>Descripción</label>
					<textarea class="form-control" rows="3" id="descripcion" name="descripcion"></textarea>
					</div>
					<div class="custom-file">
					  <input type="file" class="custom-file-input" id="archivo" name="archivo" required>
					  <label class="custom-file-label" for="customFile">Seleccionar archivo</label>
					</div>
					<div class="form-group custom-control custom-checkbox">
						<input type="checkbox" class="custom-control-input" name="anon" id="anon" value="1" >
						<label class="custom-control-label" for="anon">Subir de forma anónima</label>
					</div>
					<button type="submit" class="btn btn-primary" name="boton" id="subir_doc" value="Subir Documento" >Enviar</button>
                </div>
              </div>
            </div>
          </div>
		  </form>
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
  <script src="inc/seleccionar_subida.js"></script>
  <!-- JavaScript propio -->
  <script src="js/tablas-apuntomatic.js"></script>

</body>

</html>
