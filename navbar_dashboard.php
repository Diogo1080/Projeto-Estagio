<!-- Title -->
      <center>
        <h1>ADMIN PANEL DASHBOARD</h1>
      </center> 
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-dark" style="border-radius: 5px;">
        <a class="navbar-brand" href="#"><?php echo($_SESSION['nome']); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php">Home</span></a>
            </li>
            <li class="nav-item">
              <?php if ($_SESSION['permissao']==2) { ?>
                <a class="nav-link" href="Colaboradores.php?id_colaborador=<?php echo($_SESSION['id']) ?>">O meu perfil</a>
              <?php }else{ ?>
                <a class="nav-link" href="listar_colaboradores.php">Colaboradores</a>
              <?php } ?>
            </li>
            <li class="nav-item">
              <?php if ($_SESSION['permissao']==2) { ?>
                <a class="nav-link" href="listar_contribuintes.php">Atletas</a>
              <?php }else{ ?>
                <a class="nav-link" href="listar_contribuintes.php">Contribuintes</a>
              <?php } ?>
            </li>
            <?php if ($_SESSION['permissao']==1) { ?>
              <li class="nav-item">
                <a class="nav-link" href="cargos.php">Cargos</a>
              </li>
            <?php } ?>
            <li class="nav-item">
              <a class="nav-link" href="calendario.php">Calendario</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Terminar sessão</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">CopyPaster</a>
            </li>
          </ul>
          <span class="navbar-text">
           <a href="homepage.html">Ver Website</strong></a>
          </span>
        </div>
      </nav>