<?php

include '../components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
   header('Location: login.php'); // Redirect to login page
   exit();
}else{
  $user_id = $_SESSION['user_id'];
}

if(isset($_POST['delete'])){

   $delete_id = $_POST['property_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $select_images = $conn->prepare("SELECT `Photo` FROM `property_photos` WHERE PropertyID = ?");
   $select_images->execute([$delete_id]);

   if($select_images->rowCount() > 0){
      $delete_listing = $conn->prepare("DELETE FROM `property` WHERE PropertyID = ?");
      $delete_listing->execute([$delete_id]);

      if ($delete_listing->rowCount() > 0) {
         while($fetch_images = $select_images->fetch(PDO::FETCH_ASSOC)){
            $image = $fetch_images['Photo'];
            unlink('uploaded_files/'.$image);
         }
         $success_msg[] = 'Listing deleted successfully!';
      } else {
         $warning_msg[] = 'Failed to delete listing!';
      }
   }else{
      $warning_msg[] = 'Listing deleted already!';
   }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<!-- admins section starts  -->

<section class="grid">

   <h1 class="heading">users</h1>

   <form action="" method="POST" class="search-form">
      <input type="text" name="search_box" placeholder="search users..." maxlength="100" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>

   <div class="box-container">

   <?php
      if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
         $search_box = $_POST['search_box'];
         $search_box = filter_var($search_box, FILTER_SANITIZE_STRING);
         $select_users = $conn->prepare("SELECT * FROM `user` WHERE FullName LIKE '%{$search_box}%' OR PhoneNumber LIKE '%{$search_box}%' OR Email LIKE '%{$search_box}%'");
         $select_users->execute();
      }else{
         $select_users = $conn->prepare("SELECT * FROM `user`");
         $select_users->execute();
      }
      if($select_users->rowCount() > 0){
         while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
            $count_property = $conn->prepare("
            SELECT `ad`.*, `property`.*, `property`.`Price/Rent` AS `Price_Rent`
            FROM `ad`
            JOIN `property` ON `ad`.PropertyID = `property`.PropertyID AND `ad`.AdID = `property`.AdID
            WHERE `ad`.UserID = ?
        ");
        $count_property->execute([$fetch_users['UserID']]);
        $total_properties = $count_property->rowCount();
   ?>
   <div class="box">
      <p>name : <span><?= $fetch_users['FullName']; ?></span></p>
      <p>number : <a href="tel:<?= $fetch_users['PhoneNumber']; ?>"><?= $fetch_users['PhoneNumber']; ?></a></p>
      <p>email : <a href="mailto:<?= $fetch_users['Email']; ?>"><?= $fetch_users['Email']; ?></a></p>
      <p>properties listed : <span><?= $total_properties; ?></span></p>
      <form action="" method="POST">
         <input type="hidden" name="delete_id" value="<?= $fetch_users['UserID']; ?>">
         <input type="submit" value="delete user" onclick="return confirm('delete this user?');" name="delete" class="delete-btn">
      </form>
   </div>
   <?php
      }
   }elseif(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
      echo '<p class="empty">Results not found!</p>';
   }else{
      echo '<p class="empty">No users accounts added yet!</p>';
   }
   ?>

   </div>

</section>

<!-- users section ends -->
















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>