<?php
/////////////////
// APUNTES.PHP //
/////////////////

// Si se denuncia un archivo, guardamos la denuncia en la base de datos
function registrar_denuncia() {
    $DENarchivo = $_POST['DENarchivo'];
    $DENacusado = $_POST['DENacusado'];
    $DENdenunciante = $_POST['DENdenunciante'];
    $DENmotivo = $_POST['DENmotivo'];
       if(!empty($DENmotivo)) { 
        $qry_denuncia = "INSERT INTO ap_denuncias ( den_archivo, den_denunciado, den_denunciante, den_fecha, den_motivo, den_resuelto ) VALUES 
        ('$DENarchivo', '$DENacusado','$DENdenunciante', NOW(), '$DENmotivo','0')";
        mysqli_query($_SESSION['con'], $qry_denuncia);
        echo "<div style=\"margin-top:40px;\" class=\"alert alert-success\" role=\"alert\">Denuncia enviada. Gracias!</div>";  }
        else {
            echo "<div style=\"margin-top:40px;\" class=\"alert alert-danger\" role=\"alert\">No escribiste un motivo de denuncia</div>";
        }
}
// Lanza el select de asignaturas
function generaCarreras()
{
    $consulta = mysqli_query($_SESSION['con'], "SELECT `id`, `opcion` FROM `ap_carreras`");
    // Voy imprimiendo el primer select compuesto por los carreras
    while($registro=mysqli_fetch_row($consulta))
    {
        echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
    }
}
function subir_archivo()
{
    $uploaddir = "documentos/";
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
		        $tipo = "archivo";
		        break;
		}
		$n = $_SESSION["nick"];
		$query = "SELECT `ID` FROM `ap_users` WHERE `user_nick`='$n'";
		$query2 = mysqli_query($_SESSION['con'], $query);
		$fila = mysqli_fetch_array($query2);
		$user_id = $fila["ID"];
		$descripcion = urlencode($_POST['descripcion']);
		$size = $_FILES["archivo"]["size"] / 1024;
		$original = $_POST['original'];
		if(isset($_POST['anon']))
			$anonimo = true;
		else
			$anonimo = false;
		if(isset($_POST['boton']) && $error==UPLOAD_ERR_OK) { 
		   $subido = copy($_FILES['archivo']['tmp_name'], $uploadfile); 
		   $check = $subido && !empty($descripcion);
		}
	   	if($check) { 
			$qry_edit = "INSERT INTO ap_documentos ( usuario_id, creado_ts, file, size, descripcion, tipo, anonimo, relacion, original ) VALUES 
			('$user_id', CURDATE(), '$uploadfile','$size','$descripcion','$tipo','$anonimo','1','$original')";
			mysqli_query($_SESSION['con'], $qry_edit);
			$update_user = "UPDATE ap_users SET user_edits=(user_edits + 1) WHERE id='$user_id'";
			mysqli_query($_SESSION['con'], $update_user);
			echo "<div style=\"margin-top:40px;\" class=\"alert alert-success\" role=\"alert\">Archivo subido correctamente</div>";  }
}


// Muestra el nombre de la asignatura

function mostrar_asignatura(){
    if (isset($_POST['asignaturas'])){
    $pet_asign = mysqli_query($_SESSION['con'], "SELECT opcion FROM `ap_asignaturas` WHERE `id` =". $_POST['asignaturas'] ."");
    $asign_elegida = mysqli_fetch_row($pet_asign);
    echo 'Documentos disponibles de '. $asign_elegida[0] . '.';
}else{}
}
?>
<?php
///////////////
// ADMIN.PHP //
///////////////

// [ADMIN] Mostrar lista Denuncias

function admin_mostrar_lista_denuncias() {
	$sql_tabla_denuncias = mysqli_query($_SESSION['con'], "SELECT * FROM `ap_denuncias` WHERE den_resuelto = '0' ORDER BY `id` DESC");    
	while ($denuncia = mysqli_fetch_object($sql_tabla_denuncias)) {
		$archivo_denunciado = mysqli_query($_SESSION['con'], "SELECT * FROM `ap_documentos` WHERE `ID` = ". $denuncia->den_archivo . "");
		$archivo = mysqli_fetch_object($archivo_denunciado);
		echo '<tr class="text-center">';
		echo '<td>' . urldecode($archivo->nombre) . '</td>';
		echo '<td>' . $denuncia->den_denunciado . '</td>';
		echo '<td>' . $denuncia->den_denunciante . '</td>';
		echo '<td>' . date("d-m-Y", strtotime($denuncia->den_fecha)) . '</td>';
		echo '<td><a href="' . $archivo->file . '"><i class="fas fa-download fa-2x"></i></a>'; ?>
			<a href="#" title="Resolver Denuncia" data-target="#resolver_denuncia<?php echo $denuncia->id;?>" data-toggle="modal"><i class="text-info fas fa-question-circle fa-2x"></i></a></td>
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
								<form name="resolver-denuncia" id="resolver-denuncia" action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="post">
								<div class="form-group form-check">
									<input type="checkbox" class="form-check-input" value="confirmar" name="confirmar_borrado_documento_denuncia" id="confirmar_borrado_documento_denuncia">
									<label class="form-check-label" for="confirmar_borrado_documento_denuncia">Confirmo que quiero eliminar el documento</label>
									<input class="btn btn-danger" name="id_documento_denuncia" type="hidden" value="<?php echo $denuncia->den_archivo;?>">
									<input class="btn btn-danger" name="id_denuncia" type="hidden" value="<?php echo $denuncia->id;?>">
								</div>
							</div>
							<div class="modal-footer">
									<input class="btn btn-danger" name="enviar_borrado_documento_denuncia" type="submit" value="Borrar archivo">
									<input class="btn btn-info" name="enviar_ignorado_documento_denuncia" type="submit" value="Ignorar denuncia">
								</form>
								<a href="#" class="btn" data-dismiss="modal">Cancelar</a>
							</div>
						</div>
					</div>
				</div>			
			<?php
		echo '</td></tr>'; 
	}
}

// [ADMIN] Gestionar denuncia
function admin_gestionar_denuncias() {
	if(isset($_POST['enviar_borrado_documento_denuncia'])){
		if(isset($_POST['confirmar_borrado_documento_denuncia'])) {
			eliminar_documento_denuncia();
		}
		else {
			echo "<div class=\"alert alert-danger\" role=\"alert\">No se ha podido confirmado el borrado del archivo.</div>";
		}
	}else {}
	if(isset($_POST['enviar_ignorado_documento_denuncia'])){
			$id_denuncia = $_POST['id_denuncia'];
			$actualizar_denuncia_bd = mysqli_query($_SESSION['con'], "UPDATE ap_denuncias SET den_resuelto = 1 WHERE `id` = '".$id_denuncia."'");
			echo "<div class=\"alert alert-danger\" role=\"alert\">Se ha ignorado la denuncia</div>";
	}
}

// [ADMIN] Borrar archivo denunciado
function eliminar_documento_denuncia() {
			$id_archivo = $_POST['id_documento_denuncia'];
			$id_denuncia = $_POST['id_denuncia'];
			$doc_activo_sql="SELECT * FROM `ap_documentos` WHERE id='".$id_archivo."'";
			$doc_activo_result=mysqli_query($_SESSION['con'], $doc_activo_sql);
			$doc_activo_sql = mysqli_fetch_object($doc_activo_result);
			$ar = $doc_activo_sql->file;
			$eliminar_archivo = unlink($ar);
			$borrar_archivo_bd = mysqli_query($_SESSION['con'], "DELETE FROM `ap_documentos` WHERE `id` = '".$id_archivo."'");
			$actualizar_denuncia_bd = mysqli_query($_SESSION['con'], "UPDATE ap_denuncias SET den_resuelto = 1 WHERE `id` = '".$id_denuncia."'");
			if ($eliminar_archivo && $borrar_archivo_bd) {
				echo "<div class=\"alert alert-success\" role=\"alert\">Archivo eliminado correctamente</div>";
			}	else {
				echo "<div class=\"alert alert-danger\" role=\"alert\">No se ha podido borrar el archivo.</div>";
			}
}

// [ADMIN] Mostrar lista Denuncias

function admin_mostrar_lista_tablon() {
	$sql_tabla_tablon = mysqli_query($_SESSION['con'], "SELECT * FROM `ap_tablon` WHERE aprobado = '0' ORDER BY `id` DESC");    
	while ($anuncio_tablon = mysqli_fetch_object($sql_tabla_tablon)) {
		echo '<tr class="text-center">';
		echo '<td>' . urldecode($anuncio_tablon->titulo) . '</td>';
		echo '<td>' . $anuncio_tablon->categoria . '</td>';
		echo '<td>' . $anuncio_tablon->usuario . '</td>';
		echo '<td>' . date("d-m-Y", strtotime($anuncio_tablon->fecha_inicio)) . '</td>';
		echo '<td>' . date("d-m-Y", strtotime($anuncio_tablon->fecha_final)) . '</td>';?>
			<td><a href="#" title="Resolver Publicación" data-target="#resolver_tablon<?php echo $anuncio_tablon->id;?>" data-toggle="modal"><i class="fas fa-cogs fa-2x"></i></a></td>
				<div class="modal fade" id="resolver_tablon<?php echo $anuncio_tablon->id;?>" tabindex="-1" role="dialog" aria-labelledby="ModalTablon" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="titulo-modal">Resolver anuncio</h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							</div>
							<div class="modal-body">
								<p><strong>Título: </strong><?php echo urldecode($anuncio_tablon->titulo);?></p>
								<p><strong>Categoría: </strong><?php echo urldecode($anuncio_tablon->categoria);?></p>
								<p><strong>Contenido: </strong><?php echo urldecode($anuncio_tablon->contenido);?></p>
								<form name="resolver-tablon" id="resolver-tablon" action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="post">
									<input class="btn btn-danger" name="id_tablon" type="hidden" value="<?php echo $anuncio_tablon->id;?>">
							</div>
							<div class="modal-footer">
									<input class="btn btn-success" name="respuesta_tablon" type="submit" value="Aprobar">
									<input class="btn btn-danger" name="respuesta_tablon" type="submit" value="Rechazar">
								</form>
								<a href="#" class="btn" data-dismiss="modal">Cancelar</a>
							</div>
						</div>
					</div>
				</div>		
			<?php		
		echo '</td></tr>'; 
	}
}

// [ADMIN] Gestionar tablon
function admin_gestionar_tablon() {
	if(isset($_POST['respuesta_tablon'])){
		switch ($_POST['respuesta_tablon']){
			case "Aprobar":
				$actualizar_tablon_bd = mysqli_query($_SESSION['con'], "UPDATE ap_tablon SET aprobado = 1 WHERE `id` = '".$_POST['id_tablon']."'");
				echo "<div class=\"alert alert-success\" role=\"alert\">Tarjeta aprobada</div>";
				break;
			case "Rechazar":
				$actualizar_tablon_bd = mysqli_query($_SESSION['con'], "DELETE FROM ap_tablon WHERE `id` = '".$_POST['id_tablon']."'");
				echo "<div class=\"alert alert-danger\" role=\"alert\">Tarjeta rechazada</div>";
				break;
		}
	}
}
?>