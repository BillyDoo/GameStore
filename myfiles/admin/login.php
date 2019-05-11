<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/myfiles/core/init.php';
include 'includes/head.php';




$email=((isset($_POST['email']))?sanitize($_POST['email']):'');
$password=((isset($_POST['password']))?sanitize($_POST['password']):'');
$email=trim($email);
$password=trim($password);

$errors=array();


?>

<style>
body{
  background-image:url("/myfiles/Images/Wallpaper.jpg");
  background-size: 100 vw 100 vh;
}

</style>

<div id="login-form">
  <div>
<?php
  if($_POST){
    //form validation
    if(empty($_POST['email']) || empty($_POST['password'])){
      $errors[]='Required email and password';
    }

    // validate email
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
      $errors[]='You must enter a valid email';
    }
    //check password
    if(strlen($password)<6){
      $errors[]='Small Password.At least 6.';
    }




    //check email if exists
    $query1 = "";
    $query = $db->query("SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($query);
    $userCount = mysqli_num_rows($query);

    if($userCount < 1){
      $errors[] = "Doesnt Exist in our database.";
    }
    if(!password_verify($password,$user['password'])){
      $errors[]='The password does not match.Please try again';
    }

    //check for errors
    if(!empty($errors)){
      echo display_errors($errors);
    }else{
      echo 'Succesfull';


    }


  }
 ?>

</div>
  <h2 class="text-center">Login</h2><hr>
  <form action="login.php" method="post">
      <div class="form-group">
          <label for="email">Email:</label>
          <input type="text" name="email" id="email" class="form-control" value="<?=$email?>">
          </div>
          <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" name="password" id="password" class="form-control" value="<?=$password?>">
              </div>
            <div class="form-group">
                <input type="submit" value="Login"class="btn btn-primary">
              </div>
            </form>
            <p class="text-right"><a href="/myfiles/index.php" alt="home">Visit Site</a></p>


</div>




<?php include 'includes/footer.php'; ?>
