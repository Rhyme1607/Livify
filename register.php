<?php

include 'components/connect.php';

$warning_msg = [];
$error_msg = [];
$success_msg = [];


if(isset($_POST['submit'])){
   $id = rand(1000001,9999999); // Replaced create_unique_id() with a simple random number
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING); 
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
   $dob = $_POST['dob'];
   $present_address = $_POST['present_address'];
   $permanent_address = $_POST['permanent_address'];
   if (DateTime::createFromFormat('Y-m-d', $dob) === FALSE) {
       $warning_msg[] = 'Invalid date of birth!';
   }
   $pass = sha1($_POST['pass']); // Corrected the field name
   $pass = filter_var($pass, FILTER_SANITIZE_STRING); 
   $c_pass = sha1($_POST['c_pass']); // Corrected the field name
   $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);   

   $select_users = $conn->prepare("SELECT * FROM `user` WHERE Email = ?");
   $select_users->execute([$email]);

   $select_users_username = $conn->prepare("SELECT * FROM `user` WHERE Username = ?");
   $select_users_username->execute([$username]);

   $member_since = date('Y-m-d'); // Current date

   if($select_users->rowCount() > 0){
      $warning_msg[] = 'Email Already Taken!';
   }elseif($select_users_username->rowCount() > 0){
      $warning_msg[] = 'Username Already Taken!';
   }else{
      if($pass != $c_pass){
         $warning_msg[] = 'Password not matched!';
     }else{
      $insert_user = $conn->prepare("INSERT INTO `user`(UserID, Username, DOB, FullName, PhoneNumber, Email, Password, MemberSince, PresentAddress, PermanentAddress) VALUES(?,?,?,?,?,?,?,?,?,?)");
      $insert_user->execute([$id, $username, $dob, $name, $number, $email, $c_pass, $member_since, $present_address, $permanent_address]);
     
      if($insert_user->rowCount() > 0){
         $success_msg[] = 'Registered successfully';

         $membership_type = 'Regular';
         $trader_user_id = $id;
         $role = 'Trader';
     
         // Insert into roles table
         $insert_role = $conn->prepare("INSERT INTO `roles`(UserID, Role) VALUES(?,?)");
         $insert_role->execute([$trader_user_id, $role]);
     
         // Insert into trader table first with null MembershipID
         $insert_trader = $conn->prepare("INSERT INTO `trader`(UserID, MembershipID, MembershipType) VALUES(?,?,?)");
         $insert_trader->execute([$trader_user_id, null, $membership_type]);
     
         if($insert_trader->rowCount() > 0){
             $membership_id = rand(1000001,9999999);
             while($membership_id == $id) {
                 $membership_id = rand(1000001,9999999);
             }
             $start_date = $member_since;
             $end_date = null;
     
             // Then insert into membership table
             $insert_membership = $conn->prepare("INSERT INTO `membership`(MembershipID, StartDate, EndDate, MembershipType, Trader_UserID) VALUES(?,?,?,?,?)");
             $insert_membership->execute([$membership_id, $start_date, $end_date, $membership_type, $trader_user_id]);
     
             if($insert_membership->rowCount() > 0){
                 // Finally, update trader table with MembershipID
                 $update_trader = $conn->prepare("UPDATE `trader` SET MembershipID = ? WHERE UserID = ?");
                 $update_trader->execute([$membership_id, $trader_user_id]);
             }
         }
    }
  }
 }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
       
<?php include 'components/user_header.php'; ?>

<!-- register section starts  -->

<section class="form-container">

      <form action="register.php" method="post">
      <h3>Create An Account!</h3>
      <input type="text" name="username" required maxlength="50" placeholder="Enter Username" class="box">
      <input type="text" name="name" required maxlength="50" placeholder="Enter Your Name" class="box">
      <input type="email" name="email" required maxlength="50" placeholder="Enter Your Email" class="box">
      <label for="dob" class="label">Enter Your Date of Birth:</label>
      <input type="date" name="dob" required placeholder="Enter Your Date of Birth" class="box">
      <input type="text" name="number" required maxlength="30" placeholder="Enter Your Phone Number" class="box">
      <input type="text" name="present_address" placeholder="Enter Your Present Address" class="box">
      <input type="text" name="permanent_address" placeholder="Enter Permanent Address" class="box">
      <div class="password-field">
         <input type="password" name="pass" id="pass" required maxlength="20" placeholder="Enter Your Password" class="box">
         <i class="fa fa-eye" id="togglePassword"></i>
      </div>

      <div class="password-field">
         <input type="password" name="c_pass" id="c_pass" required maxlength="20" placeholder="Confirm Your Password" class="box">
         <i class="fa fa-eye" id="toggleConfirmPassword"></i>
      </div>
      <p>Already Have An Account? <a href="login.html">Login Now</a></p>
      <input type="submit" value="Register Now" name="submit" class="btn">
   </form>

</section>

<!-- register section ends -->







<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>