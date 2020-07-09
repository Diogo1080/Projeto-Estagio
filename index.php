<?php
  session_start(); 
  $con = mysqli_connect("10.6.0.3", "root", "" , "estrela_azul");

	if(isset($_POST['Login'])){		
  //Login do treinador
    if (strpos($_POST['username'], 'T') !== false) {
      //prepara a query para o login do treinador,define a procura
        $querry = $con->prepare("SELECT * FROM recursos_humanos WHERE num_recurso_humano = ?");
        $querry -> bind_param("s",$_POST['username']);
        $querry -> execute();
        $resultado=$querry->get_result();

        if ($resultado->num_rows <> 0){
          $linha=$resultado->fetch_assoc();
          if (password_verify($_POST['password'],$linha['password'])) {
            $_SESSION['id']=$linha['id_recurso_humano'];
            $_SESSION['nome']=$linha['nome'];
            $_SESSION['permissao']=2;
            ?>
              <script type="text/javascript">
                window.location.href = "dashboard.php"
              </script>
            <?php
          }
        }
  //Login do Admin
    }elseif (strpos($_POST['username'], 'A') !== false) {
      //prepara a query para o login do admin,define a procura
        $querry = $con->prepare("SELECT * FROM admins WHERE username = ?");
        $querry -> bind_param("s",$_POST['username']);
        $querry -> execute();
        $resultado=$querry->get_result();
        if ($resultado->num_rows <> 0){
          $linha=$resultado->fetch_assoc();
          if (password_verify($_POST['password'],$linha['password'])) {
            $_SESSION['id']=$linha['id_admin'];
            $_SESSION['nome']=$linha['username'];
            $_SESSION['permissao']=1;
            ?>
              <script type="text/javascript">
                window.location.href = "dashboard.php"
              </script>
            <?php
          }
        }
  //Login do Socio
    }elseif (strpos($_POST['username'], 'S') !== false) {
      //prepara a query para o login do admin,define a procura
        $querry = $con->prepare("SELECT * FROM contribuintes WHERE num_socio = ?");
        $querry -> bind_param("s",$_POST['username']);
        $querry -> execute();
        $resultado=$querry->get_result();

        if ($resultado->num_rows <> 0) {
          $linha=$resultado->fetch_assoc();
          if (password_verify($_POST['password'],$linha['password'])) {
            $_SESSION['id']=$linha['id_colaborador'];
            $_SESSION['nome']=$linha['nome'];
            $_SESSION['permissao']=3;
            ?>
              <script type="text/javascript">
                window.location.href = "dashboard.php"
              </script>
            <?php
          }
        }
    }
    ?>
      <script>
        window.alert("Password ou username incorretos!");
        window.location.href = "index.php";
      </script>
    <?php
    if (isset($querry)) {
		  $querry->close();
    }
	}	
?>
<?php include('head.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <style>
    :root {
      --input-padding-x: 1.5rem;
      --input-padding-y: 0.75rem;
    }

    body{
      background-color: #21659e;
    }

    .login,
    .image {
      min-height: 100vh;
    }

    .bg-image {
      background-image: url('img/login.jpg');
      background-size: cover;
      background-position: center;
    }

    .login-heading {
      font-weight: 300;
      color: #ffffff;
    }

    .btn-login {
      background-color:#657786;
      font-size: 0.9rem;
      letter-spacing: 0.05rem;
      padding: 0.75rem 1rem;
      border-radius: 2rem;
    }

    .form-label-group {
      position: relative;
      margin-bottom: 1rem;
    }

    .form-label-group>input,
    .form-label-group>label {
      padding: var(--input-padding-y) var(--input-padding-x);
      height: auto;
      border-radius: 2rem;
    }

    .form-label-group>label {
      position: absolute;
      top: 0;
      left: 0;
      display: block;
      width: 100%;
      margin-bottom: 0;
      /* Override default `<label>` margin */
      line-height: 1.5;
      color: #495057;
      cursor: text;
      /* Match the input under the label */
      border: 1px solid transparent;
      border-radius: .25rem;
      transition: all .1s ease-in-out;
    }

    .form-label-group input::-webkit-input-placeholder {
      color: transparent;
    }

    .form-label-group input:-ms-input-placeholder {
      color: transparent;
    }

    .form-label-group input::-ms-input-placeholder {
      color: transparent;
    }

    .form-label-group input::-moz-placeholder {
      color: transparent;
    }

    .form-label-group input::placeholder {
      color: transparent;
    }

    .form-label-group input:not(:placeholder-shown) {
      padding-top: calc(var(--input-padding-y) + var(--input-padding-y) * (2 / 3));
      padding-bottom: calc(var(--input-padding-y) / 3);
    }

    .form-label-group input:not(:placeholder-shown)~label {
      padding-top: calc(var(--input-padding-y) / 3);
      padding-bottom: calc(var(--input-padding-y) / 3);
      font-size: 12px;
      color: #777;
    }

    /* Fallback for Edge
    -------------------------------------------------- */

    @supports (-ms-ime-align: auto) {
      .form-label-group>label {
        display: none;
      }
      .form-label-group input::-ms-input-placeholder {
        color: #777;
      }
    }

    /* Fallback for IE
    -------------------------------------------------- */

    @media all and (-ms-high-contrast: none),
    (-ms-high-contrast: active) {
      .form-label-group>label {
        display: none;
      }
      .form-label-group input:-ms-input-placeholder {
        color: #777;
      }
    }
  </style>
	<title>Clube estrela azul</title>
</head>
<body>


<div class="container-fluid">
  <div class="row no-gutter">
    <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
    <div class="col-md-8 col-lg-6">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
              <h3 class="login-heading mb-4">Bem-Vindo ao Estrela Azul!!!</h3>
              <form method="POST">
                <div class="form-label-group">
                  <input type="text" id="inputEmail" class="form-control" placeholder="Numero de utilizador" name="username" required autofocus>
                  <label for="inputEmail">Username</label>
                </div>

                <div class="form-label-group">
                  <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password" required>
                  <label for="inputPassword">Password</label>
                </div>

                <!-- <div class="custom-control custom-checkbox mb-3">
                  <input type="checkbox" class="custom-control-input" id="customCheck1">
                  <label class="custom-control-label" for="customCheck1">Remember password</label>
                </div> -->
                <button class="btn btn-lg btn-default btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit" name="Login">ENTRAR</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>