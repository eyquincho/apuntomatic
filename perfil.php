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
	if ($_GET['id']==$_SESSION['id']){
		$docs_tabla = mysqli_query($_SESSION['con'], "SELECT id, creado_ts, usuario_id, asignatura_id, file, size, nombre, descripcion, tipo, descargas, anonimo FROM `ap_documentos` WHERE `relacion`=0 AND `usuario_id`=".$_GET['id']." ORDER BY `id` DESC");
	}
	else {
		$docs_tabla = mysqli_query($_SESSION['con'], "SELECT id, creado_ts, usuario_id, asignatura_id, file, size, nombre, descripcion, tipo, descargas, anonimo FROM `ap_documentos` WHERE `relacion`=0 AND `usuario_id`=".$_GET['id']." AND `anonimo`=0 AND NOT (`asignatura_id`= 4 OR `asignatura_id`= 38) ORDER BY `id` DESC");
	}
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
		echo '<td><center><a href="'. $seleccionada->file .'" onclick="contador'. $seleccionada->id .'()" target="_blank"><i class="fa fa-chevron-circle-down"></i></a>';
		if ($_GET['id']==$_SESSION['id']){
			?>
			<a href="#" title="Editar documento" data-target="#modal_edit<?php echo $seleccionada->id;?>" data-toggle="modal"><i class="fas fa-edit"></i></a></center></td>
				<!-- Modal editar documento -->
				<div class="modal fade" id="modal_edit<?php echo $seleccionada->id;?>" tabindex="-1" role="dialog" aria-labelledby="ModalDenuncia" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="titulo-modal">Editar documento </h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							</div>
							<div class="modal-body">
								<form name="enviaredit" id="form-edit" action="<?php $_SERVER['PHP_SELF']?>?id=<?php echo $_SESSION['id']?>" enctype="multipart/form-data" method="post">
								<div class="form-group">
									<strong>Nombre del documento</strong>
									<input type="text" class="form-control" id="EDIT-titulo" name="EDIT-titulo" value="<?php echo urldecode($seleccionada->nombre);?>">
								</div>
								<div class="form-group">
									<strong>Descripción del documento</strong>
									<textarea class="form-control" id="EDIT-descripcion" name="EDIT-descripcion" rows="3"><?php echo urldecode($seleccionada->descripcion); ?></textarea>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="" name="EDIT-anonimo" id="EDIT-anonimo" <?php if(isset($seleccionada->anonimo) && $seleccionada->anonimo == '1') {	echo 'checked';}	else {}?>>
									<label class="form-check-label" for="anonimo">
										Mostrar como anónimo
									</label>
								</div>
								<input type="hidden" id="EDIT-id" name="EDIT-id" value="<?php echo $seleccionada->id;?>">
							</div>
							<div class="modal-footer">
								<a href="#" data-target="#EliminarDocumento<?php echo $seleccionada->id;?>" data-toggle="modal"><i class="fas fa-trash-alt text-danger"></i></a>
								<input class="btn btn-info" name="EDIT-boton" type="submit" value="Enviar edición" id="EDIT-boton">
								<a href="#" class="btn" data-dismiss="modal">Cancelar</a>
							</div></form>
						</div>
					</div>
				</div>
				<!-- Modal eliminar documento -->
				<div class="modal fade" id="EliminarDocumento<?php echo $seleccionada->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="myModalLabel">Eliminar documento</h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							</div>
							<div class="modal-body">
							<p>Recuerda que <strong>puedes poner los documentos que has subido como anónimos</strong>, editarlos y hacer tu <strong>perfil privado</strong>. ¿Puede que eso sea lo que buscas?</p>
							<hr>
							<center><strong class="text-danger">Ten en cuenta que esta acción es irreversible.</strong></center>
							<hr>
							<form class="form center-block" action="<?=$_SERVER['PHP_SELF']?>?id=<?php echo $_SESSION['id']?>" method="post">
								<div class="form-group">
									<input type="password" class="form-control input-lg" name="clave_borrado_documento"  placeholder="Confirma el borrado del documento con tu contraseña" required>
								</div>
								<div class="form-group form-check">
									<input type="checkbox" class="form-check-input" value="confirmar" name="confirmar_borrado_documento" id="confirmar_borrado_documento" required>
									<label class="form-check-label" for="confirmar_borrado_documento">Confirmo que quiero eliminar el documento</label>
								</div>
								<input type="hidden" id="eliminar-doc-id" name="eliminar-doc-id" value="<?php echo $seleccionada->id;?>">
								<hr>
								<input type="submit" name="enviar_borrado_documento" value="Eliminar documento" class="btn btn-primary btn-lg btn-block"/>
							</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
							</div>
						</div>
					</div>
				</div>
			<?php
		}else {
			echo '</center></td>';
			}
		echo '</tr>'; 
		?><script>
			function contador<?php echo $seleccionada->id; ?>() {
				$.ajax({
						 type: "POST",
						 url: <?php echo '\'descargar.php?id='. $seleccionada->id.'\''?>,
						 data:{action:'contar'},
						 success:function(html) {
							 alert(html);
						 }

				});
				}
		</script><?php
  }
}

function eliminar_documento() {
	if(isset($_POST['enviar_borrado_documento'])){
		if(isset($_POST['confirmar_borrado_documento'])) {
			$id_archivo = $_POST['eliminar-doc-id'];
			$doc_activo_sql="SELECT * FROM `ap_documentos` WHERE id='".$id_archivo."'";
			$doc_activo_result=mysqli_query($_SESSION['con'], $doc_activo_sql);
			$doc_activo_sql = mysqli_fetch_object($doc_activo_result);
			$password=$_POST['clave_borrado_documento'];
			$password = MD5(stripslashes($password));
			$pet_oldkey2 = mysqli_query($_SESSION['con'], "SELECT user_pass FROM `ap_users` WHERE `user_nick` = '". $_SESSION['nick'] . "'");
			$oldkey2 = mysqli_fetch_object($pet_oldkey2);
			if ($oldkey2->user_pass != $password){
				echo "<div class=\"alert alert-danger\" role=\"alert\">La contraseña no es correcta.</div>";
			}
			else {
				$ar = $doc_activo_sql->file;
				$eliminar_archivo = unlink($ar);
				$borrar_archivo_bd = mysqli_query($_SESSION['con'], "DELETE FROM `ap_documentos` WHERE `id` = '".$id_archivo."'");
				if ($eliminar_archivo && $borrar_archivo_bd) {
					echo "<div class=\"alert alert-success\" role=\"alert\">Archivo eliminado correctamente</div>";
				}	else {
					echo "<div class=\"alert alert-danger\" role=\"alert\">No se ha podido borrar el archivo.</div>";
				}
			
			}
		} 
			else {
				echo "<div class=\"alert alert-danger\" role=\"alert\">No has confirmado que quieres borrar el documento.</div>";
			}
		}else{}
}

function editar_documento() {
		if(isset($_POST['EDIT-boton'])) {
				$id_archivo = $_POST['EDIT-id'];
				$doc_activo_sql="SELECT * FROM `ap_documentos` WHERE id='".$id_archivo."'";
				$doc_activo_result=mysqli_query($_SESSION['con'], $doc_activo_sql);
				$doc_activo_sql = mysqli_fetch_object($doc_activo_result);
				if(isset($_POST['EDIT-titulo']) && (urlencode($_POST['EDIT-titulo']) != $doc_activo_sql->nombre)) {
					$nuevo_titulo_doc = urlencode($_POST['EDIT-titulo']);
					$sql_nuevo_titulo_doc = mysqli_query($_SESSION['con'], "UPDATE ap_documentos SET nombre='".$nuevo_titulo_doc."' WHERE id='".$id_archivo."'");
					if($sql_nuevo_titulo_doc) {
								echo "<div class=\"alert alert-success\" role=\"alert\">Titulo cambiado correctamente</div>";
							}else {
								echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error al tratar de modificar el titulo</div>";
							}
					}else{}
				if(isset($_POST['EDIT-descripcion']) && (urlencode($_POST['EDIT-descripcion']) != $doc_activo_sql->descripcion)) {
					$nuevo_descripcion_doc = urlencode($_POST['EDIT-descripcion']);
					$sql_nuevo_descripcion_doc = mysqli_query($_SESSION['con'], "UPDATE ap_documentos SET descripcion='".$nuevo_descripcion_doc."' WHERE id='".$id_archivo."'");
					if($sql_nuevo_descripcion_doc) {
								echo "<div class=\"alert alert-success\" role=\"alert\">Descripción cambiada correctamente</div>";
							}else {
								echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error al tratar de modificar la descripcion</div>";
							}
					}else{}
				if(isset($_POST['EDIT-anonimo'])) {
					$nuevo_anonimo = 1;
				}else {
					$nuevo_anonimo = 0;
				}
				if (isset($nuevo_anonimo) && ($nuevo_anonimo != $doc_activo_sql->anonimo)) {
						$sql_nuevo_anonimo_doc = mysqli_query($_SESSION['con'], "UPDATE ap_documentos SET anonimo = NOT anonimo WHERE id='".$id_archivo."'");
						if($sql_nuevo_anonimo_doc) {
									echo "<div class=\"alert alert-success\" role=\"alert\">Anonimato modificado correctamente</div>";
								}else {
									echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error al tratar de modificar el anonimato</div>";
								}
					}else{}
				}else{}
}
function editar_perfil() {
		if(isset($_POST['enviar_editar_perfil'])) {
				$activo_sql="SELECT * FROM `ap_users` WHERE user_nick='".$_SESSION['nick']."'";
				$activo_result=mysqli_query($_SESSION['con'], $activo_sql);
				$activo_sql = mysqli_fetch_object($activo_result);
				$usuario_nombre = $_SESSION['nick'];
				//Web
				if(isset($_POST['web']) && ($_POST['web'] != $activo_sql->web)) {
					if(!filter_var($_POST['web'], FILTER_VALIDATE_URL)) { 
            echo "<div class=\"alert alert-warning\" role=\"alert\">No has insertado una url correcta</div>";
					}else{
							$new_web = $_POST['web'];
							$sql = mysqli_query($_SESSION['con'], "UPDATE ap_users SET web='".$new_web."' WHERE user_nick='".$usuario_nombre."'");
							if($sql) {
								echo "<div class=\"alert alert-success\" role=\"alert\">Web cambiada correctamente</div>";
							}else {
								echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error al tratar de modificar tu web</div>";
							}
						}
					}else{}
				//Twitter
				if($_POST['twitter'] != $activo_sql->twitter) {
							$new_twitter = $_POST['twitter'];
							$sql = mysqli_query($_SESSION['con'], "UPDATE ap_users SET twitter='".$new_twitter."' WHERE user_nick='".$usuario_nombre."'");
							if($sql) {
								echo "<div class=\"alert alert-success\" role=\"alert\">Usuario de Twitter cambiado correctamente</div>";
							}else {
								echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error al tratar de modificar tu usuario de twitter</div>";
							}
					}else{}
				//Instagram
				if($_POST['instagram'] != $activo_sql->instagram) {
							$new_instagram = $_POST['instagram'];
							$sql = mysqli_query($_SESSION['con'], "UPDATE ap_users SET instagram='".$new_instagram."' WHERE user_nick='".$usuario_nombre."'");
							if($sql) {
								echo "<div class=\"alert alert-success\" role=\"alert\">Usuario de Instagram cambiado correctamente</div>";
							}else {
								echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error al tratar de modificar tu usuario de Instagram</div>";
							}
				}else{}
		} else{}
}


function cambio_pass() {
		if(isset($_POST['enviar_cambio_pass'])) {
				if($_POST['usuario_clave'] != $_POST['usuario_clave_conf']) {
					echo "<div class=\"alert alert-warning\" role=\"alert\">Las contraseñas insertadas no coinciden</div>";
				}else {
					$usuario_nombre = $_SESSION['nick'];
					$antigua_clave = mysqli_real_escape_string($_SESSION['con'], $_POST["antigua_clave"]);
					$antigua_clave = md5($antigua_clave); // encriptamos la nueva contraseña con md5
					$usuario_clave = mysqli_real_escape_string($_SESSION['con'], $_POST["usuario_clave"]);
					$usuario_clave = md5($usuario_clave); // encriptamos la nueva contraseña con md5
					$pet_oldkey = mysqli_query($_SESSION['con'], "SELECT user_pass FROM `ap_users` WHERE `user_nick` = '". $_SESSION['nick'] . "'");
					$oldkey = mysqli_fetch_object($pet_oldkey);
					if ($oldkey->user_pass != $antigua_clave) {
						echo "<div class=\"alert alert-warning\" role=\"alert\">Tu contreña actual no es correcta</div>";
					}else { 
						$sql = mysqli_query($_SESSION['con'], "UPDATE ap_users SET user_pass='".$usuario_clave."' WHERE user_nick='".$usuario_nombre."'");
						if($sql) {
							echo "<div class=\"alert alert-success\" role=\"alert\">Contraseña cambiada correctamente</div>";
						}else {
							echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error al tratar de modificar tu contraseña</div>";
						}
						}
				} 
				} 
				else {}
}

function delete_account(){
		if(isset($_POST['enviar_borrado'])){
		if(isset($_POST['confirmar_borrado'])) {
			$password=$_POST['clave_borrado'];
			$password = MD5(stripslashes($password));
			$pet_oldkey2 = mysqli_query($_SESSION['con'], "SELECT user_pass FROM `ap_users` WHERE `user_nick` = '". $_SESSION['nick'] . "'");
			$oldkey2 = mysqli_fetch_object($pet_oldkey2);
			if ($oldkey2->user_pass != $password){
				echo "<div class=\"alert alert-danger\" role=\"alert\">La contraseña no es correcta.</div>";
			}
			else {
				mysqli_query($_SESSION['con'], "DELETE FROM `ap_users` WHERE `user_nick` = '".$_SESSION['nick']."'");
				session_destroy();
				echo "<script language=\"javascript\">
				window.location.href=\"index.php?log_er=4\";
				</script>";
			}
		} 
			else {
				echo "<div class=\"alert alert-danger\" role=\"alert\">No has confirmado que quieres borrar tu cuenta.</div>";
			}
		}else{}
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
						cambio_pass();
						delete_account();
						editar_perfil();
						editar_documento();
						eliminar_documento();
						if (isset($_GET['ec']) && $_GET['ec']==1){
							echo "<div class=\"alert alert-warning\" role=\"alert\">El usuario que buscas no existe o es privado</div>";
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
													<?php 
														$sql_rrss="SELECT * FROM $tbl_name WHERE ID='$id_usuario'";
														$result_rrss=mysqli_query($_SESSION['con'], $sql_rrss);
														$user_sql_rrss = mysqli_fetch_object($result_rrss);
														$placeholder_web = $user_sql_rrss->web;
														$placeholder_twitter = $user_sql_rrss->twitter;
														$placeholder_instagram = $user_sql_rrss->instagram;
													?>
                          <?php
														if (!empty($placeholder_web)){
															echo '<a href='.$user_sql->web.' target="_blank"><i class="fas fa-globe fa-2x"></i></a>';
														} else {}
													?>
													<?php
														if (!empty($placeholder_twitter)){
															echo '<a href="http://twitter.com/'.$user_sql->twitter.'" target="_blank"><i class="fab fa-twitter-square fa-2x"></i></a>';
														} else {}
													?>
													<?php
														if (!empty($placeholder_instagram)){
															echo '<a href="http://instagram.com/'.$user_sql->instagram.'" target="_blank"><i class="fab fa-instagram fa-2x"></i></a>';
														} else {}
													?>
													
												</p>
                    </div>
									</div>
									<?php if ($_GET['id']==$_SESSION['id']){ ?>
									<div class="row">
										<div class="col-12">
										<hr>
											<p><a href="#" data-toggle="modal" data-target="#CambiarFoto"><i class="fas fa-user"></i> ¿Cómo cambio mi foto?</a></p>
											<p><a href="#" data-toggle="modal" data-target="#EditarPerfil"><i class="fas fa-tools"></i> Editar perfil</a></p> <!-- Modal editar perfil -->
											<p><a href="#" data-toggle="modal" data-target="#CambiarPass"><i class="fas fa-key"></i> Cambiar contraseña</a></p>
											<p><a href="#" data-toggle="modal" data-target="#BorrarCuenta" class="text-danger"><i class="fas fa-trash-alt"></i> Eliminar cuenta</a></p> <!-- Modal confirmación -->
										</div>
									</div>
									<?php }else{} ?>
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
                <table class="table table-bordered" id="dataTableProfile" width="100%" cellspacing="0">
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
	
	<!-- MODAL CAMBIO FOTO -->
	<div class="modal fade" id="CambiarFoto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">¿Cómo cambio mi foto?</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					<p>Apuntomatic utiliza Gravatar para gestionar las imágenes de perfil. Regístrate en <a href="https://es.gravatar.com/" target="_blank">Gravatar</a> con el mismo email que en Apuntomatic y sube ahi tu imagen de perfil. Esta imagen aparecerá en otros muchos servicios que utilizan Gravatar como gestor de avatares, como StackOverflow, Disqus o la web de American Idol (quién sabe...)</p>
					<a type="button" class="btn btn-outline-info btn-lg btn-block"" href="https://es.gravatar.com/" target="_blank"><i class="fas fa-external-link-alt"></i> Ir a Gravatar</a>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- MODAL EDITAR PERFIL -->
		<div class="modal fade" id="EditarPerfil" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Editar perfil</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					<form class="form" action="<?php $_SERVER['PHP_SELF']?>?id=<?php echo $_SESSION['id']?>" method="post">
						<div class="form-group">
							<label for="web"><i class="fas fa-globe"></i> Página web</label>
							<input type="text" class="form-control" name="web" id="web" <?php if (!empty($placeholder_web)) {echo 'value="'.$placeholder_web.'"';} else{echo 'placeholder="http://www.ejemplo.com"';} ?>>
						</div>
						<div class="form-group">
							<label for="web"><i class="fab fa-twitter-square"></i> Twitter</label>
							<input type="text" class="form-control" name="twitter" <?php if (!empty($placeholder_twitter)) {echo 'value="'.$placeholder_twitter.'"';} else{echo 'placeholder="Usuario de Twitter"';} ?>>
						</div>
						<div class="form-group">
							<label for="web"><i class="fab fa-instagram"></i> Instagram</label>
							<input type="text" class="form-control" name="instagram" <?php if (!empty($placeholder_instagram)) {echo 'value="'.$placeholder_instagram.'"';} else{echo 'placeholder="Usuario de Instagram"';} ?>>
						</div>
							<input type="submit" name="enviar_editar_perfil" value="Enviar" class="btn btn-primary btn-lg btn-block"/>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- MODAL CAMBIAR CONTRASEÑA -->
	<div class="modal fade" id="CambiarPass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Cambiar contraseña</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
					<form class="form center-block" action="<?php $_SERVER['PHP_SELF']?>?id=<?php echo $_SESSION['id']?>" method="post">
						<div class="form-group">
							<input type="password" class="form-control input-lg" name="antigua_clave"  placeholder="Contraseña actual" required>
						</div>
						<div class="form-group">
							<input type="password" class="form-control input-lg" name="usuario_clave"  placeholder="Nueva contraseña" required>
						</div>
						<div class="form-group">
							<input type="password" class="form-control input-lg" name="usuario_clave_conf"  placeholder="Confirmar" required>
						</div>
							<input type="submit" name="enviar_cambio_pass" value="Enviar" class="btn btn-primary btn-lg btn-block"/>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- MODAL ELIMINAR CUENTA -->
	<div class="modal fade" id="BorrarCuenta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Eliminar cuenta</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
				<p>No estás aqui secuestrada/o, si deseas eliminar tu cuenta no tienes más que insertar tu contraseña en el campo de abajo, y automáticamente se borrarán tus datos de Apuntomatic.</p>
				<p>Recuerda que desde tu perfil <strong>puedes poner los documentos que has subido como anónimos</strong>, y hacer tu <strong>perfil privado</strong>. ¿Puede que eso sea lo que buscas?</p>
				<hr>
				<center><strong class="text-danger">Ten en cuenta que esta acción es irreversible.</strong></center>
				<hr>
				<form class="form center-block" action="<?=$_SERVER['PHP_SELF']?>?id=<?php echo $_SESSION['id']?>" method="post">
					<div class="form-group">
						<input type="password" class="form-control input-lg" name="clave_borrado"  placeholder="Confirma el borrado con tu contraseña" required>
					</div>
					<div class="form-group form-check">
						<input type="checkbox" class="form-check-input" value="confirmar" name="confirmar_borrado" id="confirmar_borrado" required>
						<label class="form-check-label" for="confirmar_borrado">Confirmo que quiero eliminar mi cuenta</label>
					</div>
					<hr>
					<input type="submit" name="enviar_borrado" value="Borrar Cuenta" class="btn btn-primary btn-lg btn-block"/>
				</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
		
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