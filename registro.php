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
              <form class="user">
                <div class="form-group">
                    <input type="text" class="form-control form-control-user" id="exampleFirstName" placeholder="Usuario">
                </div>
                <div class="form-group">
                  <input type="email" class="form-control form-control-user" id="exampleInputEmail" placeholder="Correo electrónico">
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Contraseña">
                  </div>
                  <div class="col-sm-6">
                    <input type="password" class="form-control form-control-user" id="exampleRepeatPassword" placeholder="Repetir contraseña">
                  </div>
                </div>
				<p class="mb-4">Al registrarte aceptas nuestras <a data-toggle="modal" data-target="#condiciones" href="#">Condiciones de uso</a></p>
                <a href="login.html" class="btn btn-primary btn-user btn-block">
                  Regístrate
                </a>
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
