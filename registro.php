<?php
session_start();
ob_start();
include("inc/conDB.php");
conexionDB();
$tbl_name="ap_users"; // Table name
// Comprobamos si hay una sesión iniciada
if(isset($_SESSION['nick'])){
  header("Location: main.php");
  die();
} else {}

function guardar_registro(){
        // Procedemos a comprobar que los campos del formulario no estén vacíos
        $sin_espacios = count_chars($_POST['usuario_nombre'], 1);
        if(!empty($sin_espacios[32])) { // comprobamos que el campo usuario_nombre no tenga espacios en blanco
            echo "<div class=\"alert alert-warning\" role=\"alert\">El nick no puede contener espacios en blanco. <a href='javascript:history.back();'>Reintentar</a></div>";
        }elseif(empty($_POST['usuario_nombre'])) { // comprobamos que el campo usuario_nombre no esté vacío
            echo "<div class=\"alert alert-warning\" role=\"alert\">No has ingresado un nombre de usuario. <a href='javascript:history.back();'>Reintentar</a></div>";
        }elseif(empty($_POST['usuario_clave'])) { // comprobamos que el campo usuario_clave no esté vacío
            echo "<div class=\"alert alert-warning\" role=\"alert\">No has puesto ninguna contraseña. <a href='javascript:history.back();'>Reintentar</a></div>";
        }elseif($_POST['usuario_clave'] != $_POST['usuario_clave_conf']) { // comprobamos que las contraseñas ingresadas coincidan
            echo "<div class=\"alert alert-warning\" role=\"alert\">Las contraseñas ingresadas no coinciden. <a href='javascript:history.back();'>Reintentar</a></div>";
        }elseif(!filter_var($_POST['usuario_email'], FILTER_VALIDATE_EMAIL)) { // validamos que el email ingresado sea correcto
            echo "<div class=\"alert alert-warning\" role=\"alert\">No has insertado una dirección de email válida. <a href='javascript:history.back();'>Reintentar</a></div>";
        }else {
            // "limpiamos" los campos del formulario de posibles códigos maliciosos
            $usuario_nombre = mysqli_real_escape_string($_SESSION['con'], $_POST['usuario_nombre']);
            $usuario_clave = mysqli_real_escape_string($_SESSION['con'], $_POST['usuario_clave']);
            $usuario_email = mysqli_real_escape_string($_SESSION['con'], $_POST['usuario_email']);
            // comprobamos que el usuario ingresado no haya sido registrado antes
            $sql_nick = mysqli_query($_SESSION['con'], "SELECT user_nick FROM ap_users WHERE user_nick='".$usuario_nombre."'");
			$sql_mail = mysqli_query($_SESSION['con'], "SELECT user_email FROM ap_users WHERE user_email='".$usuario_email."'");
            if(mysqli_num_rows($sql_nick) > 0) {
                echo "<div class=\"alert alert-danger\" role=\"alert\">El nombre usuario elegido ya ha sido registrado anteriormente. <a href='javascript:history.back();'>Reintentar</a></div>";
            }else {
				if(mysqli_num_rows($sql_mail) > 0) {
                echo "<div class=\"alert alert-danger\" role=\"alert\">El email que has utilizado ya está siendo utilizado. <br />¿Es tu cuenta? ¡Recupera tu contraseña! <a href='javascript:history.back();'>Reintentar</a></div>";
				}else {
					$usuario_clave = md5($usuario_clave); // encriptamos la contraseña ingresada con md5
					// ingresamos los datos a la BD
					$reg = mysqli_query($_SESSION['con'],"INSERT INTO ap_users (user_nick, user_pass, user_email, user_registered) VALUES ('".$usuario_nombre."', '".$usuario_clave."', '".$usuario_email."', NOW())");
					if($reg) {
						echo "<div class=\"alert alert-success\" role=\"alert\">Registro realizado con éxito, ahora ya puedes iniciar sesión y empezar a compartir.</div>";
					}else {
						echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error, por favor, ponte en contacto con el Administrador.</div>";
					}
				}
			}
        }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Registro</title>

  <!-- Custom fonts for this template-->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">
<?php 
include "modals/condiciones.html";
include "modals/contacto.html";
include "modals/galletas.html";
 ?>
  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <img style="width:40%"src="img/logo-apuntomatic.png"/>
					<hr>
				<h1 class="h4 text-gray-900 mb-4">Crear una cuenta nueva</h1>
              </div>
							<?php
								if(isset($_POST['enviar'])) { 	
									guardar_registro();
								}else {}
							?>
              <form class="user" id="regForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
                <div class="form-group">
                    <input type="text" class="form-control form-control-user" name="usuario_nombre" id="exampleFirstName" placeholder="Usuario" required>
                </div>
                <div class="form-group">
                  <input type="email" class="form-control form-control-user" name="usuario_email" id="exampleInputEmail" placeholder="Correo electrónico" required>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" class="form-control form-control-user" name="usuario_clave" id="exampleInputPassword" placeholder="Contraseña" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="password" class="form-control form-control-user" name="usuario_clave_conf" id="exampleRepeatPassword" placeholder="Repetir contraseña" required>
                  </div>
                </div>
				<p class="mb-4">Al registrarte aceptas nuestras <a data-toggle="modal" data-target="#condiciones" href="#">Condiciones de uso</a></p>
                <button type="submit" name="enviar" id="enviar" class="btn btn-primary btn-user btn-block">
                  Enviar
                </button>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="recuperar.php">¿Has olvidado tu contraseña?</a>
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

  <!-- Bootstrap core JavaScript-->
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="js/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.js"></script>

</body>

</html>
