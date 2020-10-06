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
								<form name="resolver-denuncia" id="resolver-denuncia" action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data" method="post">
								<div class="form-group form-check">
									<input type="checkbox" class="form-check-input" value="confirmar" name="confirmar_borrado_documento_denuncia" id="confirmar_borrado_documento">
									<label class="form-check-label" for="confirmar_borrado_documento">Confirmo que quiero eliminar el documento</label>
								</div>
							</div>
							<div class="modal-footer">
									<input class="btn btn-danger" name="enviar_borrado_documento_denuncia" type="submit" data-target="#BorrarDocumentoDenuncia<?php echo $denuncia->id;?>" data-toggle="modal" value="Borrar archivo">
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

// [ADMIN] Descartar denuncia


// [ADMIN] Borrar archivo denunciado

function gestionar_denuncias() {
	if(isset($_POST['enviar_borrado_documento_denuncia'])){
		if(isset($_POST['confirmar_borrado_documento_denuncia'])) {
			echo "Y el documento ya estaría borrado";
		}
		else {
			echo "No se confirmó el borrado";
		}
	}else {
		echo "No se quiere borrar nada";
	}
}

function eliminar_documento_Asdasdasd() {
	if(isset($_POST['enviar_borrado_documento_denuncia'])){
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


?>