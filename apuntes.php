<?php @session_start();
	// Control de sesión inciada
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {
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
				<td><center><i class="fa fa-file-<?php echo $seleccionada->tipo; ?>-o fa-2x"></i></center></td>
				<td><center><?php echo urldecode($seleccionada->nombre);?></center></td>
				<td><center><?php echo urldecode($seleccionada->descripcion);?></center></td>
				<td><center><?php echo number_format($seleccionada->size/1024,2,".",",");?> Mb</center></td>
				<td><center><a href="<?php echo $seleccionada->file?>" onclick="window.open(\'descargar.php?id=<?php echo $seleccionada->id;?>\')" target="_blank"><i class="fa fa-cloud-download fa-2x"></i></a></center></td>
				<td><center><?php echo $uploader;?></center></td>
				<td><center><?php echo $seleccionada->descargas;?></center></td>
				<td><button title="Denunciar documento" data-target="#modal_denuncia<?php echo $div_id;?>" data-toggle="modal"><i class="fa fa-exclamation-circle text-danger"></i></button></td>
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
				<!-- ******************* -->
				<!-- Tabla extensible de ediciones de documento -->
				<tbody id="edits<?php echo $div_id;?>" class="collapse hiddenrow accordion-toggle">
						<?php
						while ($sel_ed = mysqli_fetch_object($edits)) {
							$petnomuser_ed = mysqli_query($_SESSION['con'], "SELECT user_nick FROM `ap_users` WHERE `ID` = ". $sel_ed->usuario_id . "");
							$consnomuser_ed = mysqli_fetch_object($petnomuser_ed);
							if(isset($sel_ed->anonimo) && $sel_ed->anonimo == '1')
							{
							$uploader_ed = "Anónimo";
							}  
							else {
							$uploader_ed = $consnomuser_ed->user_nick;
							}?>
								<tr class="success">
									<td><center><i class="fa fa-file-<?php echo $sel_ed->tipo; ?>-o fa-2x"></i></center></td>
									<td><center><?php echo urldecode($sel_ed->nombre);?></center></td>
									<td><center><?php echo urldecode($sel_ed->descripcion);?></center></td>
									<td><center><?php echo number_format($sel_ed->size/1024,2,".",",");?> Mb</center></td>
									<td><center><a href="<?php echo $sel_ed->file ?>" onclick="window.open(\'descargar.php?id=<?php echo $sel_ed->id;?>\')" target="_blank"><i class="fa fa-chevron-circle-down fa-2x"></i></a></center></td>
									<td><center><?php echo $uploader_ed;?></center></td>
									<td></td>
									<td><center><?php echo $sel_ed->descargas;?></center></td>
									<td><button title="Denunciar documento" data-target="#modal_denuncia<?php echo $div_id;?>" data-toggle="modal"><i class="fa fa-exclamation-circle text-danger"></i></button></td>
								</tr>	
									<!-- ******************* -->
									<!-- Modal denunciar ediciones -->
									<div class="modal fade" id="modal_denuncia<?php echo $sel_ed->id;?>" tabindex="-1" role="dialog" aria-labelledby="ModalDenuncia" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													<h4 class="modal-title" id="titulo-modal">Denunciar documento </h4> <?php echo urldecode($sel_ed->nombre);?>
												</div>
												<div class="modal-body">
													<form name="enviardenuncia" id="form-denuncia" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post">
													<div class="form-group">
														<label>Motivos</label>
														<textarea class="form-control" rows="3" id="DENmotivo" name="DENmotivo" placeholder="Describe brevemente los motivos de denuncia"></textarea>
													</div>
													<input type="hidden" id="DENarchivo" name="DENarchivo" value="<?php echo $sel_ed->id;?>">
													<input type="hidden" id="DENdenunciante" name="DENdenunciante" value="<?php echo $_SESSION['id']; ?>">
													<input type="hidden" id="DENacusado" name="DENacusado" value="<?php echo $consnomuser_ed->user_nick; ?>"> 
												</div>
												<div class="modal-footer">
													<input class="btn btn-danger" name="DENboton" type="submit" value="Enviar denuncia" id="DENboton">
													<a href="#" class="btn" data-dismiss="modal">Cancelar</a>
												</div></form>
											</div>
										</div>
									</div>
									<!-- ******************* -->								
							<?php
						}
						?>
				</tbody>
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
                    <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post" name="form">
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
					<div class="form-group col-md-4">
					<button type="submit" name="buscar" class="btn btn-primary">Buscar</button>
					</div>
                    </form>
                  <!-- Selector asignaturas FIN -->
                </div>
              </div>
            </div>
          </div>

          <!-- Content Row -->

          <div class="row">

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
                      <th>Descargas</th>
                      <th>Enlace</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
					  <th>Formato</th>
                      <th>Nombre</th>
                      <th>Descripción</th>
                      <th>Fecha</th>
                      <th>Descargas</th>
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
                    <tr>
                      <td>Garrett Winters</td>
                      <td>Accountant</td>
                      <td>Tokyo</td>
                      <td>63</td>
                      <td>2011/07/25</td>
                      <td><center><i class="fas fa-file-download"></i> 14 Mb</center></td>
                    </tr>
                    <tr>
                      <td>Ashton Cox</td>
                      <td>Junior Technical Author</td>
                      <td>San Francisco</td>
                      <td>66</td>
                      <td>2009/01/12</td>
                      <td><center><i class="fas fa-file-download"></i> 14 Mb</center></td>
                    </tr>
                    <tr>
                      <td>Cedric Kelly</td>
                      <td>Senior Javascript Developer</td>
                      <td>Edinburgh</td>
                      <td>22</td>
                      <td>2012/03/29</td>
                      <td><center><i class="fas fa-file-download"></i> 14 Mb</center></td>
                    </tr>
                    <tr>
                      <td>Airi Satou</td>
                      <td>Accountant</td>
                      <td>Tokyo</td>
                      <td>33</td>
                      <td>2008/11/28</td>
                      <td><center><i class="fas fa-file-download"></i> 14 Mb</center></td>
                    </tr>
                    <tr>
                      <td>Brielle Williamson</td>
                      <td>Integration Specialist</td>
                      <td>New York</td>
                      <td>61</td>
                      <td>2012/12/02</td>
                      <td><center><i class="fas fa-file-download"></i> 14 Mb</center></td>
                    </tr>
                    <tr>
                      <td>Herrod Chandler</td>
                      <td>Sales Assistant</td>
                      <td>San Francisco</td>
                      <td>59</td>
                      <td>2012/08/06</td>
                      <td><center><i class="fas fa-file-download"></i> 14 Mb</center></td>
                    </tr>
                  </tbody>
                </table>
              </div>
                </div>
              </div>
            </div>

          
          </div>
		  <div class="row">
            <div class="col-lg-12">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Documentos disponibles de ###</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
				  No hay documentos disponibles. <a href="subir.html">¿Quieres subir uno?</a>
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
<?php } ?>   