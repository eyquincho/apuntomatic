<?php @session_start();
	// Control de sesión inciada
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {}
include_once("inc/conDB.php");
conexionDB();
mysqli_set_charset($_SESSION['con'], 'utf8');
  if($_SESSION["admin"]==0){
    header("Location: main.php");
    die();
  } else {}
  require("inc/funciones.php");

// Datos para la cabecera de la página principal

// Usuarios totales
$sql_usuarios_totales = "SELECT COUNT(id) AS num FROM `ap_users`";
$result_usuarios_totales = mysqli_query($_SESSION['con'],$sql_usuarios_totales);
$usuarios_totales = mysqli_fetch_assoc($result_usuarios_totales);

// Documentos totales
$sql_documentos_totales = "SELECT COUNT(id) AS num FROM `ap_documentos`";
$result_documentos_totales = mysqli_query($_SESSION['con'],$sql_documentos_totales);
$documentos_totales = mysqli_fetch_assoc($result_documentos_totales);

// Descargas totales
$sql_descargas_totales = "SELECT SUM(descargas) AS descargas FROM `ap_documentos`";
$result_descargas_totales = mysqli_query($_SESSION['con'],$sql_descargas_totales);
$head_descargas_totales = mysqli_fetch_assoc($result_descargas_totales);
$num_descargas_totales = $head_descargas_totales['descargas'];



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
					admin_gestionar_denuncias();
					admin_gestionar_tablon();
				?>
          <!-- Content Row -->
          <div class="row">
            <!-- Tarjeta usuarios -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Usuarios totales</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $usuarios_totales['num']; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Tarjeta documentos -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Documentos totales</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $documentos_totales['num']; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-copy fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Tarjeta descargas totales -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Descargas totales</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $num_descargas_totales; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-download fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Denuncias</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Archivo</th>
                      <th>Denunciado</th>
                      <th>Denunciante</th>
                      <th>Fecha</th>
                      <th>Opciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Archivo</th>
                      <th>Denunciado</th>
                      <th>Denunciante</th>
                      <th>Fecha</th>
                      <th>Opciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php admin_mostrar_lista_denuncias(); ?>                    
                  </tbody>
                </table>
              </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Tablón</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                <table class="table table-bordered display" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Titulo</th>
                      <th>Categoria</th>
											<th>Usuario</th>
                      <th>Inicio</th>
                      <th>Final</th>
											<th>Acciones</th> 											
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Titulo</th>
                      <th>Categoria</th>
											<th>Usuario</th>
                      <th>Inicio</th>
                      <th>Final</th>
											<th>Acciones</th> 
                    </tr>
                  </tfoot>
                  <tbody>
                    <tr>
                      <?php admin_mostrar_lista_tablon(); ?>   
                    </tr>
                    
                  </tbody>
                </table>
              </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Publicidad</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Asignatura</th>
                      <th>Fecha</th>
                      <th>Nombre</th>
                      <th>Descargas</th>
                      <th>Anonimo</th>
                      <th>Tipo</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>Asignatura</th>
                      <th>Fecha</th>
                      <th>Nombre</th>
                      <th>Descargas</th>
                      <th>Anonimo</th>
                      <th>Tipo</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <tr>
                      <td>Tiger Nixon</td>
                      <td>System Architect</td>
                      <td>Edinburgh</td>
                      <td>61</td>
                      <td>2011/04/25</td>
                      <td><center><i class="fas fa-file-download"></i> 14 Mb</center></td>
                    </tr>
                    
                  </tbody>
                </table>
              </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include "footer.php" ?>
    </div>
  </div>
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.js"></script>
  <script src="js/jquery.dataTables.min.js"></script>
  <script src="js/dataTables.bootstrap4.min.js"></script>
  <script src="js/tablas-apuntomatic.js"></script>
</body>
</html>
