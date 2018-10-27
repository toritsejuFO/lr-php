<?php
require_once 'constants.php';

function email_exists($db, $email) {

 $sql = "SELECT count(*) FROM  users  WHERE email = '" . $email ."'"; 
 $query = $db->prepare($sql); 
 $query->execute(); 
 $count = $query->fetchColumn(); 


 return $count == 0 ? false : true;

}

if( !empty($_POST) ){
  $_errors = array();

  foreach( $_POST as $input => $value ){
    switch( $input ){
      case 'firstname':
        if( $value == null ){
          $_errors[] = "firstanme is empty";
        }
      break;

      case 'lastname':
        if( $value == null ){
          $_errors[] = "lastname is empty";
        }
      break;

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

    if( $db && email_exists($db, $_POST['email'] ) == false){
      $sql = "INSERT INTO users(`firstname`, `lastname`, `email`, `password`)
            VALUES('{$_POST['firstname']}', '{$_POST['lastname']}', '{$_POST['email']}', '". password_hash($_POST['password'], PASSWORD_DEFAULT) ."')";

      $query = $db->prepare($sql);
      if( $query->execute() ){
      	$url = "register/";
        header("Location: $url");
        die();
      }
    }

  }
$msg = "Registration was unsucessful email exists";
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
          <form class="form form-control col-xs-12 my-form" action="register.php" method="post">
            <img src="img/1-150P4221T3.jpg" style="width:40px;height:40px;margin-top:5px;;" alt="img">
            <p><b>Sign up</b></p>

            <div class="form-group">
              <label class="" for="firstname">Firstname <span style="color:red;"><?php if( isset($_POST['firstname']) && empty($_POST['firstname']) ){ echo "*"; }?></span></label>
              <input type="text" name="firstname" value="<?php if( isset($_POST['firstname']) ){ echo $_POST['firstname']; }?>" id="firstname" placeholder="First Name" class="form-control input-lg">
            </div>

            <div class="form-group">
              <label class="" for="lastname">Lastname <span style="color:red;"><?php if( isset($_POST['lastname']) && empty($_POST['lastname']) ){ echo "*"; }?></span></label>
              <input type="text" name="lastname" value="<?php if( isset($_POST['lastname']) ){ echo $_POST['lastname']; }?>" id="lastname" placeholder="Last Name" class="form-control input-lg">
            </div>

            <div class="form-group">
              <label class="" for="email">Email <span style="color:red;"><?php if( isset($_POST['email']) && empty($_POST['email']) ){ echo "*"; }?></span></label>
              <input type="email" name="email" value="<?php if( isset($_POST['email']) ){ echo $_POST['email']; }?>" id="email" placeholder="Email" class="form-control input-lg">
            </div>

            <div class="form-group">
              <label class="" for="password">Password <span style="color:red;"><?php if( isset($_POST['password']) && empty($_POST['password']) ){ echo "*"; }?></span></label>
              <input type="password" min="8" name="password" value="" id="password" placeholder="password" class="form-control input-lg">
            </div>

            <input type="submit" name="submit" value="submit" class="btn btn-block my-form-submit"/>
            Registered? <a href="login.php" style="color:#246;">Login</a>
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
