    <!------------->
	<!-- Sidebar -->
	<!------------->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Logo -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-text mx-3">Apuntomatic</div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Portada</span></a>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Apuntomatic
      </div>
		<li class="nav-item">
        <a class="nav-link" href="apuntes.php">
          <i class="fas fa-fw fa-folder"></i>
          <span>Buscar apuntes</span></a>
		</li>
		<li class="nav-item">
        <a class="nav-link" href="subir.php">
          <i class="fas fa-fw fa-file-upload "></i>
          <span>Subir apuntes</span></a>
		</li>
		<li class="nav-item">
        <a class="nav-link" href="tablon.php">
          <i class="fas fa-fw fa-sign "></i>
          <span>Tablón de anuncios</span></a>
		</li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Config
      </div>
			<li class="nav-item">
        <a class="nav-link" href="perfil.php?id=<?php echo $_SESSION['id']; ?>">
          <i class="fas fa-fw fa-user"></i>
          <span>Perfil</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="publicidad.php">
          <i class="fas fa-fw fa-ad"></i>
          <span>Publicidad</span></a>
      </li>
		<?php if ($_SESSION["admin"]==1){?>
		<li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-user"></i>
          <span>Administración</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="admin.php">Panel de aprobación</a>
						<a class="collapse-item" href="#">Estadísticas</a>
          </div>
        </div>
      </li>
		<?php } else {}
		?>
	  <li class="nav-item">
        <a class="nav-link" href="privacidad.php">
          <i class="fas fa-fw fa-user-shield"></i>
          <span>Privacidad</span></a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="inc/cerrar.php">
          <i class="fas fa-fw fa-sign-out-alt "></i>
          <span>Cerrar sesión</span></a>
    </li>
      <hr class="sidebar-divider d-none d-md-block">
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!----------------->
	<!-- Fin Sidebar -->
	<!----------------->