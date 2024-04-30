<?php  

if (session_status() == PHP_SESSION_NONE) {
   session_start(); // Start the session
}

if (!isset($_SESSION['user_id'])) {
   header('Location: login.php'); // Redirect to login page
   exit;
}

$user_id = $_SESSION['user_id'];

include 'components/connect.php';


$select_user = $conn->prepare("SELECT * FROM `user` WHERE UserID = ? LIMIT 1");
$select_user->execute([$user_id]);
$fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING); 
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $present_address = $_POST['present_address'];
   $present_address = filter_var($present_address, FILTER_SANITIZE_STRING);
   $permanent_address = $_POST['permanent_address'];
   $permanent_address = filter_var($permanent_address, FILTER_SANITIZE_STRING);

   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `user` SET FullName = ? WHERE UserID = ?");
      $update_name->execute([$name, $user_id]);
      $success_msg[] = 'Name Updated!';
   }

   if(!empty($email)){
      $verify_email = $conn->prepare("SELECT Email FROM `user` WHERE Email = ?");
      $verify_email->execute([$email]);
      if($verify_email->rowCount() > 0){
         $warning_msg[] = 'Email Already Taken!';
      }else{
         $update_email = $conn->prepare("UPDATE `user` SET Email = ? WHERE UserID = ?");
         $update_email->execute([$email, $user_id]);
         $success_msg[] = 'Email Updated!';
      }
   }

   if(!empty($number)){
      $verify_number = $conn->prepare("SELECT PhoneNumber FROM `user` WHERE PhoneNumber = ?");
      $verify_number->execute([$number]);
      if($verify_number->rowCount() > 0){
         $warning_msg[] = 'Number Already Taken!';
      }else{
         $update_number = $conn->prepare("UPDATE `user` SET PhoneNumber = ? WHERE UserID = ?");
         $update_number->execute([$number, $user_id]);
         $success_msg[] = 'Number updated!';
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $prev_pass = $fetch_user['Password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $c_pass = sha1($_POST['c_pass']);
   $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $warning_msg[] = 'Old Password Not Matched!';
      }elseif($new_pass != $c_pass){
         $warning_msg[] = 'Confirm Passowrd Not Matched!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `user` SET Password = ? WHERE UserID = ?");
            $update_pass->execute([$c_pass, $user_id]);
            $success_msg[] = 'Password Updated Successfully!';
         }else{
            $warning_msg[] = 'Please Enter New Password!';
         }
      }
   }

   if(!empty($present_address)){
      $update_present_address = $conn->prepare("UPDATE `user` SET PresentAddress = ? WHERE UserID = ?");
      $update_present_address->execute([$present_address, $user_id]);
      $success_msg[] = 'Present Address Updated!';
   }

   if(!empty($permanent_address)){
      $update_permanent_address = $conn->prepare("UPDATE `user` SET PermanentAddress = ? WHERE UserID = ?");
      $update_permanent_address->execute([$permanent_address, $user_id]);
      $success_msg[] = 'Permanent Address Updated!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>update your account!</h3>
      <input type="tel" name="name" maxlength="50" placeholder="<?= $fetch_user['FullName']; ?>" class="box">
      <input type="email" name="email" maxlength="50" placeholder="<?= $fetch_user['Email']; ?>" class="box">
      <input type="number" name="number" min="0" max="9999999999" maxlength="10" placeholder="<?= $fetch_user['PhoneNumber']; ?>" class="box">
      <input type="text" name="present_address" maxlength="255" placeholder="<?= $fetch_user['PresentAddress']; ?>" class="box">
      <input type="text" name="permanent_address" maxlength="255" placeholder="<?= $fetch_user['PermanentAddress']; ?>" class="box">
      <input type="password" name="old_pass" maxlength="20" placeholder="Enter Your Old Password" class="box">
      <input type="password" name="new_pass" maxlength="20" placeholder="Enter Your New Password" class="box">
      <input type="password" name="c_pass" maxlength="20" placeholder="Confirm Your New Password" class="box">
      <input type="submit" value="update now" name="submit" class="btn">
   </form>

</section>






<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>
</body>
</html>