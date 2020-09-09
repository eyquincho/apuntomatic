<?php
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