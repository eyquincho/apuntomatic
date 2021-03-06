<?php @session_start();
	// Control de sesión inciada
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {}
include_once("inc/conDB.php");
conexionDB();
mysqli_set_charset($_SESSION['con'], 'utf8');
?>

<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Apuntomatic - Privacidad</title>

  <!-- Llamadas CSS -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="css/sb-admin-2.css" rel="stylesheet">
  <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
  <div id="wrapper">
  <?php include "sidebar.php" ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
          <?php include "header.php" ?>
        <div class="container-fluid">

          <!-- Content Row -->
          <div class="row">
			    <!-- Collapsable Card Example -->
              <div class="card shadow mb-4 col-md-12">
                <a href="#CondicionesUso" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="CondicionesUso">
                  <h6 class="m-0 font-weight-bold text-primary">Condiciones de uso</h6>
                </a>
                <div class="collapse" id="CondicionesUso">
                  <div class="card-body">
                    <ol>
					<li><strong>Apuntomatic</strong>, es una plataforma creada con el único propósito de facilitar, a sus usuarios, el acceso o intercambio de apuntes para las titulaciones impartidas en la Facultad de Ciencias Sociales y de la Comunicación de la Universidad de Vigo en Pontevedra.</li>
					<li><strong>Apuntomatic</strong> se rige, puesto que fue creada en suelo español, bajo la legislación española a todos los efectos.</li>
					<li><strong>Apuntomatic</strong>, en el momento de su registro, pide una serie de datos personales. El tratamiento que se le da a dichos datos es el que se establece en la Ley Orgánica de Protección de Datos (LOPD) 15/1999 DE 13 De Diciembre.</li>
					<li><strong>Apuntomatic</strong> garantiza que no se facilitará ningún dato de carácter personal a terceras personas, tal y como se dirime del articulado de la LOPD. Salvo que se requiera por mandato judicial.</li>
					<li><strong>Apuntomatic</strong>, no tiene ningún fin lucrativo. Por lo tanto, no se cobra ninguna cuantía económica en el momento del registro ni se retribuirá de ninguna forma a nadie por la subida de documentos o apuntes.</li>
					<li><strong>Apuntomatic</strong> garantiza el derecho a la eliminación de cualquier archivo que vulnere el derecho a la intimidad, el honor, etc. Para ello, se debe enviar un correo electrónico a hola@apuntomatic.com facilitando los datos que permitan llevar a cabo esta tarea.</li>
					<li><strong>Apuntomatic</strong> no busca socavar los derechos de Propiedad Intelectual ni Industrial de nadie. Si, por algún casual, estos se ven vulnerados, solamente tendrán que enviar un correo a la dirección de correo de la página (hola@apuntomatic.com) y se solventará a la mayor brevedad posible.</li>
					<li><strong>Apuntomatic</strong> no se hace responsable de los documentos que se suban. Se basa en la buena fe de sus Usuarios. Se establece que no se deben subir apuntes de terceras personas sin su conocimiento y consentimiento previo.</li>
					<li><strong>Apuntomatic</strong> no garantiza ningún aprobado. Los apuntes son subjetivos y pueden adolecer de algún defecto. No nos hacemos responsables de los posibles errores o fallos.</li>
					<li><strong>Apuntomatic</strong> es una plataforma digital y, al igual que el resto, debe llevar a cabo tareas de mantenimiento, actualizaciones, etc. Las fechas de estas tareas pueden variar, por lo tanto, no se debe esperar a fechas de exámenes para la descarga de los apuntes.</li>
					<li><strong>Apuntomatic</strong> tiene a disposición de sus Usuarios un correo electrónico para el envío de quejas, sugerencias, etc. hola@apuntomatic.com</li>
					</ol>
				  </div>
                </div>
              </div>			 
          </div>
		  <div class="row">
			    <!-- Collapsable Card Example -->
              <div class="card shadow mb-4 col-md-12">
                <a href="#Cookies" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="Cookies">
                  <h6 class="m-0 font-weight-bold text-primary">Política de Cookies</h6>
                </a>
                <div class="collapse" id="Cookies">
                  <div class="card-body">
                    Con el objetivo de mejorar el funcionamiento del sitio, esta web utiliza cookies de terceras partes. 
					Apuntomatic utiliza Google Analytics, un servicio analítico prestado por Google, Inc., una compañía 
					de Delaware cuya oficina principal está en 1600 Amphitheatre Parkway, Mountain View (California), 
					CA 94043, Estados Unidos (“Google”). Google Analytics utiliza “cookies”, que son archivos de texto
					ubicados en su ordenador, para ayudar al website a analizar el uso que hacen los usuarios del sitio 
					web. La información que genera la cookie acerca de su uso del website (incluyendo su dirección IP) 
					será directamente transmitida y archivada por Google en los servidores de Estados Unidos. Google 
					usará esta información por cuenta nuestra con el propósito de seguir la pista de su uso del website, 
					recopilando informes de la actividad del website y prestando otros servicios relacionados con la 
					actividad del website y el uso de Internet. Google podrá transmitir dicha información a terceros 
					cuando así se lo requiera la legislación, o cuando dichos terceros procesen la información por cuenta 
					de Google. Google no asociará su dirección IP con ningún otro dato del que disponga Google. Puede 
					Usted rechazar el tratamiento de los datos o la información rechazando el uso de cookies mediante 
					la selección de la configuración apropiada de su navegador, sin embargo, debe Usted saber que si 
					lo hace puede ser que no pueda usar la plena funcionabilidad de este website. Al utilizar este 
					website Usted consiente el tratamiento de información acerca de Usted por Google en la forma y 
					para los fines arriba indicados.
				  </div>
                </div>
              </div>			 
          </div>

          <!-- Content Row -->

          <div class="row">

          </div>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php include "footer.php" ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Boton volver arriba-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- JavaScript Externo -->
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.js"></script>
  <script src="js/jquery.dataTables.min.js"></script>
  <script src="js/dataTables.bootstrap4.min.js"></script>

  <!-- JavaScript propio -->
  <script src="js/tablas-apuntomatic.js"></script>

</body>

</html>
