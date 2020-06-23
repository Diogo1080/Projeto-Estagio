<?php 
  //Prepara a ligação
    require ('ligacao.php');
?>
<!DOCTYPE html>
<html lang="pt">
  <?php include('head.php'); ?>
  <header>
    <script src="//code.jquery.com/jquery.min.js"></script>
    <script src="toastr/toastr.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
    <meta name="Addport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Teste</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/album/">
    <!-- Bootstrap core CSS -->
    <link href="assets/dist/css/bootstrap.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="album.css" rel="stylesheet">
  </head>
  <body>

</header>

<main role="main">
  <div class="album py-5 bg-light">
    <div class="container">

      <div class="row">
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <img src="camisola.png" width="100%" height="225">
            <div class="card-body">
              <p class="card-text">Descrição</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">Add</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                </div>
                <small class="text-muted">Camisola</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <img src="calcoes.png" width="100%" height="225">
            <div class="card-body">
              <p class="card-text">Descrição</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">Add</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                </div>
                <small class="text-muted">Calção</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <img src="meias.png" width="100%" height="225">
            <div class="card-body">
              <p class="card-text">Descrição</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">Add</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                </div>
                <small class="text-muted">Meias</small>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <img src="hoodie.png" width="100%" height="225">
            <div class="card-body">
              <p class="card-text">Descrição</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">Add</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                </div>
                <small class="text-muted">Hoodie</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <img src="caneleiras.png" width="100%" height="225">
            <div class="card-body">
              <p class="card-text">Descrição</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">Add</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                </div>
                <small class="text-muted">Caneleiras</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <img src="chuteiras.png" width="100%" height="225">
            <div class="card-body">
              <p class="card-text">Descrição</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">Add</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                </div>
                <small class="text-muted">Chuteiras</small>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <img src="mala.png" width="100%" height="225">
            <div class="card-body">
              <p class="card-text">Descrição</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">Add</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                </div>
                <small class="text-muted">Mala</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <img src="gagua.png" width="100%" height="225">
            <div class="card-body">
              <p class="card-text">Descrição</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">Add</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                </div>
                <small class="text-muted">Garrafa</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4 shadow-sm">
            <img src="bola.png" width="100%" height="225">
            <div class="card-body">
              <p class="card-text">Descrição</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">Add</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                </div>
                <small class="text-muted">Bola</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.js"></script></body>
</html>
