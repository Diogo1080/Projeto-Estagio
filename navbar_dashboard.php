    <!-- Header -->
    <center>
        <img src="img/panel.png" style="width:60%;">
    </center> 

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <a class="navbar-brand" href="#"><i class="fa fa-user" aria-hidden="true" style="padding-right:5px;"></i><?php echo($_SESSION['nome']); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">

            <!-- Homepage -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php">Home</span></a>
            </li>

            <!-- Colaboradores -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Colaboradores
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="nav-link" href="listar_colaboradores.php">Ver Colaboradores</a>
                <div class="dropdown-divider"></div>
                <a class="nav-link" href="colaboradores.php">Inserir Colaborador</a>
              </div>
            </li>

            <!-- Contribuintes -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Contribuintes
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown2">
                    <a class="nav-link" href="listar_contribuintes.php"> Ver Contribuintes</a>
                    <div class="dropdown-divider"></div>
                    <a class="nav-link" href="listar_contribuintes.php"> Inserir Contribuinte</a>
                </div>
            </li>

            <!-- Calendário -->
                <li class="nav-item">
                    <a class="nav-link" href="calendario.php">Calendario</a>
              </li>

            <!-- Operações -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Ferramentas
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown3">
                    <a class="nav-link" href="cargos.php">Cargos</a>
                    <a class="nav-link" href="inserir_equipa.php">Equipas</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>

          </ul>

          <!-- Conexão ao Website -->
            <span class="navbar-text">
                <a href="homepage.html">Ver Website</strong></a>
            </span>

        </div>
      </nav>