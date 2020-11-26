<?php 
session_start();
include("inc/conDB.php");
conexionDB();
if(isset($_POST['action']) && $_POST['action'] == 'contarpubli') {
	$idanuncio = $_GET['idpubli'];
	$update_clicanuncio = "UPDATE ap_publicidad SET clics=(clics + 1) WHERE id='$idanuncio'";
	mysqli_query($_SESSION['con'], $update_clicanuncio);
}else{
	echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
	echo "No deberías estar aqui";
}
 ?>