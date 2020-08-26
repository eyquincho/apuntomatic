<?php 
session_start();
include("inc/conDB.php");
conexionDB();
if(isset($_POST['action']) && $_POST['action'] == 'contar') {
	$id = $_GET['id'];
	$id_activo = $_SESSION['id'];
	$update_archivo = "UPDATE ap_documentos SET descargas=(descargas + 1) WHERE id='$id'";
	mysqli_query($_SESSION['con'], $update_archivo);
	$update_user = "UPDATE ap_users SET user_downloads=(user_downloads + 1) WHERE id='$id_activo'";
	mysqli_query($_SESSION['con'], $update_user);
	
}else{
	echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
	echo "No deberías estar aqui";
}
 ?>