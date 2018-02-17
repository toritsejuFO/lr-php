<?php
require_once 'constants.php';

if( !empty($_POST) ){
  $_errors = array();

  foreach( $_POST as $input => $value ){
    switch( $input ){
      case 'email':
        if( $value == null ){
          $_errors[] = "email is empty";
        }
        else if( !filter_var($value, FILTER_VALIDATE_EMAIL) ){
          $_errors[] = "email is invalid";
        }
      break;

      case 'password':
        if( $value == null ){
          $_errors[] = "password is empty";
        }
      break;
    }
  }

  if( empty($_errors) ){
    $db = new pdo("mysql:host=". DB_HOST .";dbname=". DB_NAME, DB_USER, DB_PASS);

    if( $db ){
      $sql = "SELECT * FROM users where email = '{$_POST['email']}'";

      $query = $db->prepare($sql);
      if( $query->execute() ){
        $user = $query->fetch(PDO::FETCH_OBJ);

        if( password_verify($_POST['password'], $user->password) ){
          $url = "welcome.php";
          header("Location: $url");
          die();
        }
      }
    }

  }
$msg = "Registration was unsucessful";
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
  </head>

  <body class="body">
    <div class="container-fluid main">

      <div class="row">
        <div class="col-xs-12 my-form-div">
          <form class="form form-control col-xs-12 my-form" action="login.php" method="post">
            <img src="img/1-150P4221T3.jpg" style="width:40px;height:40px;margin-top:5px;;" alt="img">
            <p><b>Login</b></p>

            <div class="form-group">
              <label class="" for="email">Email <span style="color:red;"><?php if( isset($_POST['email']) && empty($_POST['email']) ){ echo "*"; }?></span></label>
              <input type="email" name="email" value="<?php if( isset($_POST['email']) ){ echo $_POST['email']; }?>" id="email" placeholder="Email" class="form-control input-lg">
            </div>

            <div class="form-group">
              <label class="" for="password">Password <span style="color:red;"><?php if( isset($_POST['password']) && empty($_POST['password']) ){ echo "*"; }?></span></label>
              <input type="password" min="8" name="password" value="" id="password" placeholder="password" class="form-control input-lg">
            </div>

            <input type="submit" name="submit" value="submit" class="btn btn-block my-form-submit"/>
            Not registered? <a href="register.php" style="color:#246;">Sign Up</a>
            <p>&copy2018 TORIBOI, powered by TORIBOI</p>
          </form>

        </div>
      </div>

    </div>
    <?php if( isset($msg) ):?>
      <script type="text/javascript">
        alert("<?php echo $msg; ?>");
      </script>
    <?php endif ?>
  </body>
</html>
