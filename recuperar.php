<?php
session_start();
ob_start();
include("inc/conDB.php");
conexionDB();

// Comprobamos si hay una sesión iniciada
if(isset($_SESSION['nick'])){
  header("Location: main.php");
  die();
} else {}

function valid_email($str)
{
return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
} ?> 
<?php
    if(isset($_POST['rec_con'])) { // comprobamos que se han enviado los datos del formulario
		$correo = mysqli_real_escape_string($_SESSION['con'], $_POST['correo']);
		$correo = trim($correo);
		$sql = mysqli_query($_SESSION['con'], "SELECT user_nick, user_pass, user_email FROM ap_users WHERE user_email='".$correo."'");
		if(mysqli_num_rows($sql)) {
			$row = mysqli_fetch_assoc($sql);
			$num_caracteres = "6"; // asignamos el número de caracteres que va a tener la nueva contraseña
			$nueva_clave = substr(md5(rand()),0,$num_caracteres); // generamos una nueva contraseña de forma aleatoria
			$usuario_nombre = $row['user_nick'];
			$usuario_clave = $nueva_clave; // la nueva contraseña que se enviará por correo al usuario
			$usuario_clave2 = md5($usuario_clave); // encriptamos la nueva contraseña para guardarla en la BD
			$usuario_email = $row['user_email'];
			// actualizamos los datos (contraseña) del usuario que solicitó su contraseña
			mysqli_query($_SESSION['con'], "UPDATE ap_users SET user_pass='".$usuario_clave2."' WHERE user_email='".$correo."'");
			// Enviamos por email la nueva contraseña
			$remite_nombre = "Apuntomatic"; // Tu nombre o el de tu página
			$remite_email = "hola@apuntomatic.com"; // tu correo
			$asunto = "[Apuntomatic] Recuperación de contraseña"; // Asunto (se puede cambiar)
			$mensaje = "Has solicitado cambiar tu clave de acceso a Apuntomatic, por si dudabas, tu nombre de usuario es ".$usuario_nombre.". Y tu nueva clave: ".$usuario_clave.".";
			$cabeceras = "From: ".$remite_nombre." <".$remite_email.">rn";
			$enviar_email = mail($usuario_email,$asunto,$mensaje,$cabeceras);
			if($enviar_email) {
				header("location:../apuntomatic/recuperar.php?rec_er=3");
			}else {
				header("location:../apuntomatic/recuperar.php?rec_er=2");
			}
		}else {
			header("location:../apuntomatic/recuperar.php?rec_er=1");
		}
    }else {}
?>+
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Recuperar contraseña</title>

  <!-- Custom fonts for this template-->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">
  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                <img style="width:40%"src="img/logo-apuntomatic.png"/>
				      	<hr>
                    <h1 class="h4 text-gray-900 mb-2">¿Has olvidado tu contraseña?</h1>
                    <p class="mb-4">Introduce tu correo electrónico a continuación y te enviaremos un correo desde el que establecer una nueva contraseña</p>
                  </div>
                  <?php if (isset($_GET['rec_er'])) {
                    switch ($_GET['rec_er']){
                      case 1: ?>
                      <div class="alert alert-danger" role="alert">
                        Ese correo electrónico no existe en nuestra base de datos, si crees que es un error ponte en contacto con nosotros.
                      </div><?php
                      break;
                      case 2: ?>
                      <div class="alert alert-warning" role="alert">
                        Ha ocurrido un error enviando el correo, por favor, ponte en contacto con nosotros.
                      </div><?php
                      break;
                      case 3: ?>
                      <div class="alert alert-success" role="alert">
                        Se ha enviado un correo electrónico a esa dirección con una nueva contraseña.
                      </div><?php
                      break;
                    }}else{}?>  
                  
                  <form class="user" action="recuperar.php" method="post">
                    <div class="form-group">
                      <input type="email" class="form-control form-control-user" id="emailInput" name="correo" aria-describedby="emailHelp" placeholder="Correo electrónico">
                    </div>
                    <button type="submit" name="rec_con" id="rec_con" class="btn btn-primary btn-user btn-block">
                      Recuperar contraseña
                    </button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="registro.php">Crear una cuenta</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="index.php">¿Ya tienes una cuenta? ¡Inicia sesión!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="js/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.js"></script>

</body>

</html>
