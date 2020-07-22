<?php @session_start();
	// Control de sesión inciada
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {}
require_once("inc/conDB.php");
conexionDB();
mysqli_set_charset($_SESSION['con'], 'utf8');
require("inc/funciones_apuntes.php");

if(isset($_POST['DENboton'])){
	registrar_denuncia();
}

if(isset($_FILES['archivo'])){
	subir_archivo();
}

if (isset($_POST['asignaturas'])) {

// Recoge las filas de documentos para una asignatura concreta.
			
		function mostrar_lista() {
			$docs = mysqli_query($_SESSION['con'], "SELECT id, usuario_id, asignatura_id, file, size, nombre, descripcion, tipo, descargas, anonimo FROM `ap_documentos` WHERE `asignatura_id` =". $_POST['asignaturas'] ." AND `relacion`=0");
			while ($seleccionada = mysqli_fetch_object($docs)) {
				$div_id= $seleccionada->id;
				$edits = mysqli_query($_SESSION['con'], "SELECT id, usuario_id, asignatura_id, file, size, nombre, descripcion, tipo, descargas, anonimo FROM `ap_documentos` WHERE `original` =". $div_id ." AND `relacion`=1");
				$num_edits = mysqli_num_rows($edits);
				$petnomuser = mysqli_query($_SESSION['con'], "SELECT id, user_nick FROM `ap_users` WHERE `ID` = ". $seleccionada->usuario_id . "");
				$consnomuser = mysqli_fetch_object($petnomuser);
				if(isset($seleccionada->anonimo) && $seleccionada->anonimo == '1')
				{
				$uploader = "Anónimo";
				}  
				else {
				$uploader = $consnomuser->user_nick;
				}
				
				?>
				<tr>
					<td><center><i class="fas fa-file-<?php echo $seleccionada->tipo; ?>"></i><br>(<?php echo number_format($seleccionada->size/1024,2,".",",");?> Mb)</center></td>
					<td><center><?php echo urldecode($seleccionada->nombre);?></center></td>
					<td><center><?php echo urldecode($seleccionada->descripcion);?></center></td>
					<td>Fecha</td>
					<td><center><?php echo $uploader;?></center></td>
					<td><center><?php echo $seleccionada->descargas;?></center></td>
					<td><center><a href="<?php echo $seleccionada->file?>" onclick="window.open(\'descargar.php?id=<?php echo $seleccionada->id;?>\')" target="_blank"><i class="fas fa-cloud-download-alt fa-2x"></i></a>
					<a href="#" title="Denunciar documento" data-target="#modal_denuncia<?php echo $div_id;?>" data-toggle="modal"><i class="fas fa-exclamation-circle fa-2x text-danger"></i></a></center></td>
				</tr>

				<!-- ******************* -->
				<!-- Modal denunciar documento -->
				<div class="modal fade" id="modal_denuncia<?php echo $div_id;?>" tabindex="-1" role="dialog" aria-labelledby="ModalDenuncia" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="titulo-modal">Denunciar documento </h4> <?php echo urldecode($seleccionada->nombre);?>
							</div>
							<div class="modal-body">
								<form name="enviardenuncia" id="form-denuncia" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post">
								<div class="form-group">
									<label>Motivos</label>
									<textarea class="form-control" rows="3" id="DENmotivo" name="DENmotivo" placeholder="Describe brevemente los motivos de denuncia"></textarea>
								</div>
								<input type="hidden" id="DENarchivo" name="DENarchivo" value="<?php echo $div_id;?>">
								<input type="hidden" id="DENdenunciante" name="DENdenunciante" value="<?php echo $_SESSION['id']; ?>">
								<input type="hidden" id="DENacusado" name="DENacusado" value="<?php echo $consnomuser->id; ?>"> 
							</div>
							<div class="modal-footer">
								<input class="btn btn-danger" name="DENboton" type="submit" value="Enviar denuncia" id="DENboton">
								<a href="#" class="btn" data-dismiss="modal">Cancelar</a>
							</div></form>
						</div>
					</div>
				</div>
				<?php			
			}
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
  <link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <?php 
		$perfil = mysqli_query($_SESSION['con'], "SELECT * FROM ap_users WHERE user_nick='".$_SESSION['nick']."'") or die(mysql_error());
		$row = mysqli_fetch_array($perfil);
		$id_sesion = $row["ID"];
		$nick_sesion = $row["user_nick"];
		$nom_sesion = $row["user_name"];
		$freg_sesion = $row["user_registered"];
		$sub_sesion = $row["user_files"];
		$desc_sesion = $row["user_downloads"];
		//gravatar
		$gravatarMd5 = md5($row["user_email"]);
		?>
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
            <div class="col-lg-12">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Selecciona una titulación</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <!-- Selector asignaturas -->
                    <form class="form-inline" id="SelAsig" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post" name="form">
											<div class="form-group col-md-4">
					  						<label class="sr-only" for="carreras">Titulación</label>
													<select class="custom-select col-sm-12" name="carreras" id="carreras" onChange="cargaContenido(this.id)" >
														<option value='0' >Selecciona Titulación</option>
														<?php generaCarreras(); ?>
													</select>
											</div>
										<div class="form-group col-md-4">
                      <label class="sr-only" for="cursos">Curso</label>
                        <select class="custom-select col-sm-12" name="cursos" id="cursos" disabled>
                          <option value="0">Selecciona Curso</option>
                        </select>
										</div>
										<div class="form-group col-md-4">
                      <label class="sr-only" for="asignaturas">Asignatura</label>
                        <select class="custom-select col-sm-12" name="asignaturas" id="asignaturas" disabled>
                          <option value="0">Selecciona Asignatura</option>
                        </select>
										</div>
                    </form>
                  <!-- Selector asignaturas FIN -->
                </div>
              </div>
            </div>
          </div>
          <!-- Content Row -->
          <div class="row">
						<?php
									if (!isset($_POST['asignaturas'])) {
									}
									else {
										?>
            <!-- Area Chart -->
            <div class="col-lg-12">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary"><?php mostrar_asignatura(); ?></h6>
                </div>
                <!-- Card Body -->
								
                <div class="card-body">
								
										
                  <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Formato</th>
                      <th>Nombre</th>
                      <th>Descripción</th>
                      <th>Fecha</th>
											<th>Usuario</th>
                      <th>Descargas</th>
											<th>Opciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
											<th>Formato</th>
                      <th>Nombre</th>
                      <th>Descripción</th>
                      <th>Fecha</th>
											<th>Usuario</th>
                      <th>Descargas</th>
											<th>Opciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php mostrar_lista(); ?>
                  </tbody>
                </table>
              </div>
							<?php
									}
								?>
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
  <script src="inc/seleccionar.js"></script>

  <!-- JavaScript propio -->
  <script src="js/tablas-apuntomatic.js"></script>
</body>

</html>