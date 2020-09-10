<?php @session_start();
	// Control de sesión inciada
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {}
require_once("inc/conDB.php");
conexionDB();
mysqli_set_charset($_SESSION['con'], 'utf8'); ?>
<?php
	function imprimeTarjetas() {
		$consulta_tarjetas = mysqli_query($_SESSION['con'], "SELECT * FROM `ap_tablon` WHERE `aprobado`='1' AND NOW() BETWEEN `fecha_inicio` AND DATE_ADD(`fecha_final`, INTERVAL 1 DAY)");
			// Voy imprimiendo el primer select compuesto por los carreras
			while($tarjeta=mysqli_fetch_row($consulta_tarjetas))
			{
				?>
				<div class="card tablon-<?php echo $tarjeta[2]; ?>">
					<div class="card-body">
						<h4 class="card-title"><?php echo $tarjeta[1]; ?></h4>
						<h6 class="card-subtitle mb-2 text-muted"><?php echo ucfirst($tarjeta[2]); ?></h6>
						<p class="card-text"><?php echo $tarjeta[3]; ?></p>
					</div>
					<div class="card-footer"><?php echo date("d-m-Y", strtotime($tarjeta[4])); ?> - Publicado por <strong><?php echo $tarjeta[6]; ?></strong></div>
				</div>
				<?php
			}
	}
	function nuevaTarjeta() {
		if (isset($_POST['nueva_tarjeta_enviar'])){
			$titulo_tarjeta = mysqli_real_escape_string($_SESSION['con'], $_POST['nueva_tarjeta_titulo']);
			$categoria_tarjeta = mysqli_real_escape_string($_SESSION['con'], $_POST['nueva_tarjeta_categoria']);
			$contenido_tarjeta = mysqli_real_escape_string($_SESSION['con'], $_POST['nueva_tarjeta_contenido']);
			$fecha_inicio = $_POST['nueva_tarjeta_inicio'];
			$fecha_final = $_POST['nueva_tarjeta_final'];
			$usuario_tarjeta = $_SESSION["nick"];
			$usuario_id_tarjeta = $_SESSION["id"];
			$qry = "INSERT INTO ap_tablon ( titulo, categoria, contenido, fecha_inicio, fecha_final, usuario, usuario_id, aprobado) VALUES
			('$titulo_tarjeta','$categoria_tarjeta', '$contenido_tarjeta','$fecha_inicio','$fecha_final','$usuario_tarjeta', '$usuario_id_tarjeta', '0')";
			mysqli_query($_SESSION['con'], $qry);
			echo "<div class=\"alert alert-success\" role=\"alert\">Tu anuncio se ha enviado para aprobación</div>";
		}
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Apuntomatic - Tablón</title>

  <!-- Llamadas CSS -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="css/sb-admin-2.css" rel="stylesheet">
  <link href="css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <style>
	.card {
		display:none;
	}
	.show {
		display:block:
	}
	.tablon-alquiler {
		background-color: #ffcccc;
	}
	.tablon-compraventa {
		background-color: #ffffcc;
	}
	.tablon-evento {
		background-color: #ccffcc;
	}
	.tablon-otros {
		background-color: #ccffff;
	}
  </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <?php include "sidebar.php" ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php include "header.php" ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
				<?php nuevaTarjeta(); ?>
		<div class="row">
			<div class="col-sm-12 mb-3">
				<input type="text" id="FiltroAnuncios" class="form-control" onkeyup="FiltrarAnuncios()" placeholder="Buscar contenido...">
			</div>
			<div class="col-sm-12 mb-3">
				<button type="button" class="btn btn-info" data-toggle="modal" data-target="#NuevoAnuncioModal"><i class="fas fa-fw fa-plus-square "></i> Nuevo anuncio</button>
				<button type="button" class="active btn btn-outline-dark" id="all">Mostrar todo</button>
				<button type="button" class="btn btn-outline-danger" id="tablon-alquiler">Alquiler</button>
				<button type="button" class="btn btn-outline-warning" id="tablon-compraventa">Compraventa</button>
				<button type="button" class="btn btn-outline-success" id="tablon-evento">Evento</button>
				<button type="button" class="btn btn-outline-info" id="tablon-otros">Otros</button>
			</div>
		</div>
          <!-- Content Row -->
			<div class="row">
			<div class="card-columns" id="AnunciosPublicados">
				<?php imprimeTarjetas(); ?>				
			</div>
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
  
  <!-- Modal Añadir nuevo anuncio -->
	<div class="modal fade" id="NuevoAnuncioModal" tabindex="-1" role="dialog" aria-labelledby="NuevoAnuncio" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="NuevoAnuncio">Añadir nuevo anuncio</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <form class="form" id="nuevo-tablon" action="tablon.php" enctype="multipart/form-data" method="post" name="nuevo-tablon">
		  <div class="modal-body">
			  <div class="form-group">
				<label for="nueva_tarjeta_titulo">Titulo</label>
				<input type="text" class="form-control" name="nueva_tarjeta_titulo" placeholder="Titulo del anuncio" required>
			  </div>
			  <div class="form-group">
				<label for="nueva_tarjeta_categoria">Categoría</label>
				<select class="form-control" name="nueva_tarjeta_categoria" required>
				  <option value="">Selecciona....</option>
				  <option value="alquiler">Alquiler</option>
					<option value="compraventa">Compraventa</option>
					<option value="evento">Evento</option>
					<option value="otros">Otros</option>
				</select>
			  </div>
				<div class="form-group">
				<label for="nueva_tarjeta_contenido">Texto</label>
				<textarea class="form-control" name="nueva_tarjeta_contenido" rows="3" required></textarea>
				<small id="AvisoAnuncio" class="form-text text-muted">
					No publiques nada ilegal, ofensivo o que viole la intimidad de cualquier persona. Apuntomatic se reserva el derecho a no aprobar los anuncios si no son adecuados o a eliminarlos sin previo aviso.
				</small>
			  </div>
				<div class="form-group">
					<label for="nueva_tarjeta_inicio">Fecha inicio:</label>
					<input type="date" id="nueva_tarjeta_inicio" name="nueva_tarjeta_inicio" required>
				</div>
				<div class="form-group">
					<label for="nueva_tarjeta_final">Fecha final:</label>
					<input type="date" id="nueva_tarjeta_final" name="nueva_tarjeta_final" required>
				</div>
			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
			<button type="submit" class="btn btn-success" id="nueva_tarjeta_enviar" name="nueva_tarjeta_enviar">Enviar para revisión</button>
		  </div>
			</form>
		</form>
		</div>
	  </div>
	</div>
  

  <!-- JavaScript Externo -->
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.js"></script>
  <script src="js/jquery.dataTables.min.js"></script>
  <script src="js/dataTables.bootstrap4.min.js"></script>

  <!-- JavaScript propio -->
  <script src="js/tablas-apuntomatic.js"></script>
  <!-- Filtro de tarjetas en el tablón de anuncios -->
  <script>
	function FiltrarAnuncios() {
		var input, filter, cards, cardContainer, h4, title, i;
		input = document.getElementById("FiltroAnuncios");
		filter = input.value.toUpperCase();
		cardContainer = document.getElementById("AnunciosPublicados");
		cards = cardContainer.getElementsByClassName("card");
		for (i = 0; i < cards.length; i++) {
			title = cards[i].querySelector(".card-body");
			if (title.innerText.toUpperCase().indexOf(filter) > -1) {
				cards[i].style.display = "";
			} else {
				cards[i].style.display = "none";
			}
		}
	}
  </script>
  <!-- FIN Filtro de tarjetas en el tablón de anuncios -->
  <script>
	var $btns = $('.btn').click(function() {
	  if (this.id == 'all') {
		$('#AnunciosPublicados > div').fadeIn(450);
	  } else {
		var $el = $('.' + this.id).fadeIn(450);
		$('#AnunciosPublicados > div').not($el).hide();
	  }
	  $btns.removeClass('active');
	  $(this).addClass('active');
	})
  </script>
</body>

</html>
