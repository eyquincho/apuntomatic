        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>


          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
						<div class="topbar-divider d-none d-sm-block"></div>
						<!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
								<span class="mr-2 d-none d-lg-inline text-gray-600 small">Hola, <strong><?php echo $_SESSION['nick']; ?></strong></span>
                <img class="img-profile rounded-circle" src="https://www.gravatar.com/avatar/<?php echo $_SESSION["emailhash"]; ?>?s=50">
            </li>

          </ul>

        </nav>