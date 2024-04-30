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
      // Delete rows in the `ad` table that reference the `PropertyID`
      $delete_ad = $conn->prepare("DELETE FROM `ad` WHERE PropertyID = ?");
      $delete_ad->execute([$delete_id]);

      // Delete rows in the `property_photos` table that reference the `PropertyID`
      $delete_photos = $conn->prepare("DELETE FROM `property_photos` WHERE PropertyID = ?");
      $delete_photos->execute([$delete_id]);

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
   <title>Listings</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<section class="listings">

   <h1 class="heading">all listings</h1>

   <form action="" method="POST" class="search-form">
      <input type="text" name="search_box" placeholder="search listings..." maxlength="100" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>

   <div class="box-container">

   <?php
      if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
         $search_box = $_POST['search_box'];
         $search_box = filter_var($search_box, FILTER_SANITIZE_STRING);
         $select_listings = $conn->prepare("   SELECT `ad`.*, `property`.*, `property`.`Price/Rent` AS `Price_Rent`, `photos`.`Photo`
         FROM `ad`
         JOIN `property` ON `ad`.PropertyID = `property`.PropertyID AND `ad`.AdID = `property`.AdID
         LEFT JOIN (
            SELECT `PropertyID`, MIN(`PhotoID`) as `PhotoID`
            FROM `property_photos`
            GROUP BY `PropertyID`
         ) `photos_min` ON `ad`.PropertyID = `photos_min`.PropertyID
         LEFT JOIN `property_photos` `photos` ON `photos_min`.`PhotoID` = `photos`.`PhotoID`
         WHERE `property`.PropertyName LIKE '%{$search_box}%' 
         OR `property`.Address LIKE '%{$search_box}%'
         ORDER BY `ad`.DatePosted DESC");
         $select_listings->execute();
      }else{
         $select_listings = $conn->prepare("SELECT `ad`.*, `property`.*, `property`.`Price/Rent` AS `Price_Rent`, `photos`.`Photo`
         FROM `ad`
         JOIN `property` ON `ad`.PropertyID = `property`.PropertyID AND `ad`.AdID = `property`.AdID
         LEFT JOIN (
            SELECT `PropertyID`, MIN(`PhotoID`) as `PhotoID`
            FROM `property_photos`
            GROUP BY `PropertyID`
         ) `photos_min` ON `ad`.PropertyID = `photos_min`.PropertyID
         LEFT JOIN `property_photos` `photos` ON `photos_min`.`PhotoID` = `photos`.`PhotoID`
         ORDER BY `ad`.DatePosted DESC");
         $select_listings->execute();
      } 
      $total_images = 0;
       if($select_listings->rowCount() > 0){
         while($fetch_listing = $select_listings->fetch(PDO::FETCH_ASSOC)){

         $listing_id = $fetch_listing['PropertyID'];

         $select_user = $conn->prepare("SELECT * FROM `user` WHERE UserID = ?");
         $select_user->execute([$fetch_listing['UserID']]);
         $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);


         $select_images = $conn->prepare("SELECT COUNT(*) as total_images FROM `property_photos` WHERE PropertyID = ?");
         $select_images->execute([$fetch_listing['PropertyID']]);
         $fetch_images = $select_images->fetch(PDO::FETCH_ASSOC);
         $total_images = $fetch_images['total_images'];
   ?>
   <div class="box">
         <div class="thumb">
            <p class="total-images"><i class="far fa-image"></i><span><?= $total_images; ?></span></p> 
            <img src="../uploaded_files/<?= $fetch_listing['Photo']; ?>" alt="">
         </div>
      <p class="price"><i class="fas fa-indian-rupee-sign"></i><?= $fetch_listing['Price_Rent']; ?></p>
      <h3 class="name"><?= $fetch_listing['PropertyName']; ?></h3>
      <p class="location"><i class="fas fa-map-marker-alt"></i><?= $fetch_listing['Address']; ?></p>
      <form action="" method="POST">
         <input type="hidden" name="delete_id" value="<?= $listing_id; ?>">
         <a href="view_property.php?get_id=<?= $listing_id; ?>" class="btn">view property</a>
         <input type="submit" value="delete listing" onclick="return confirm('delete this listing?');" name="delete" class="delete-btn">
      </form>
   </div>
   <?php
         }
      }elseif(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
         echo '<p class="empty">no results found!</p>';
      }else{
         echo '<p class="empty">no property posted yet!</p>';
      }
   ?>

   </div>

</section>



















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>