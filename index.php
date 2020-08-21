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

// Comprobamos si se ha enviado el formulario
  if (isset ($_POST['nick'])){
    // Define $nick and $password
    $nick=$_POST['nick'];
    $password=$_POST['password'];

    // To protect MySQL injection (more detail about MySQL injection)
    $nick = stripslashes($nick);
    $password = MD5(stripslashes($password));
    $nick = mysqli_real_escape_string($_SESSION['con'], $nick);
    $password = mysqli_real_escape_string($_SESSION['con'], $password);

    $sql="SELECT * FROM $tbl_name WHERE user_nick='$nick' and user_pass='$password'";
    $result=mysqli_query($_SESSION['con'], $sql);

    $sql_id = mysqli_fetch_object($result);
    // mysqli_num_row is counting table row
    $count=mysqli_num_rows($result);
    // If result matched $nick and $password, table row must be 1 row

    if($count==1){
    // Register $nick, $password and redirect to file "login_success.php"
    $_SESSION['nick']= $_POST['nick'];
    $_SESSION["id"]= $sql_id->ID;
    $_SESSION["admin"]= $sql_id->user_admin;
	// Guardamos el hash del email para Gravatar
	$md5email = trim ($sql_id->user_email); // "MyEmailAddress@example.com"
	$md5email = strtolower( $md5email ); // "myemailaddress@example.com"
	$_SESSION["emailhash"]= md5( $md5email );
    header("location:../apuntomatic/main.php");
    }
    else {
    header("location:../apuntomatic/index.php?log_er=1");
    }
    ob_end_flush();
  }else{}
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Apuntomatic - Login</title>

  <!-- CSS-->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="css/sb-admin-2.css" rel="stylesheet">

</head>
<body class="bg-gradient-primary">
<?php 
include "modals/condiciones.html";
include "modals/contacto.html";
include "modals/galletas.html";
 ?>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
					<img style="width:40%"src="img/logo-apuntomatic.png"/>
					<hr>
                    <h1 class="h4 text-gray-900 mb-4">¿Nos conocemos?</h1>
                  </div>
                  <?php if (isset($_GET['log_er'])) {
										switch ($_GET['log_er']) {
											case 1: echo '<hr><div class="alert alert-danger" role="alert">Nombre de usuario o contraseña erróneos, inténtalo de nuevo.</div>';
											break;
											case 2: echo '<hr><div class="alert alert-success" role="alert">Registro realizado con éxito, ahora ya puedes iniciar sesión y empezar a compartir.</div>';
											break;
											case 4: echo '<hr><div class="alert alert-danger" role="alert">Tu cuenta ha sido eliminada correctamente.</div>';
											break;
									}}else{}?>
                  <form class="user" id="loginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" name="nick" placeholder="Usuario">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name="password" placeholder="Contraseña">
                    </div>
                    <button type="submit" name="send" id="send" class="btn btn-primary btn-user btn-block">
                      Entrar
                    </button>
                    <p class="mb-4">Al iniciar sesión aceptas nuestras <a data-toggle="modal" data-target="#condiciones" href="#">Condiciones de uso</a></p>
                  </form>
                  
                  <hr>
                  <div class="text-center">
                    <a class="small" href="recuperar.php">¿Has olvidado tu contraseña?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="registro.php">Crear una cuenta</a>
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
