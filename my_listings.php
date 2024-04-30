<?php  

include 'components/connect.php';

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
   <title>my listings</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="my-listings">

   <h1 class="heading">my listings</h1>

   <div class="box-container">

   <?php
   $select_photo_count = $conn->prepare("
      SELECT `PropertyID`, COUNT(*) as `PhotoCount`
      FROM `property_photos`
      GROUP BY `PropertyID`
   ");
   $select_photo_count->execute();
   $photo_counts = $select_photo_count->fetchAll(PDO::FETCH_ASSOC);

   $photo_counts_assoc = array();
   foreach ($photo_counts as $photo_count) {
      $photo_counts_assoc[$photo_count['PropertyID']] = $photo_count['PhotoCount'];
   }

   $select_properties = $conn->prepare("
      SELECT `ad`.*, `property`.*, `property`.`Price/Rent` AS `Price_Rent`, `photos`.`Photo`
      FROM `ad`
      JOIN `property` ON `ad`.PropertyID = `property`.PropertyID AND `ad`.AdID = `property`.AdID
      LEFT JOIN (
         SELECT `PropertyID`, MIN(`PhotoID`) as `PhotoID`
         FROM `property_photos`
         GROUP BY `PropertyID`
      ) `photos_min` ON `ad`.PropertyID = `photos_min`.PropertyID
      LEFT JOIN `property_photos` `photos` ON `photos_min`.`PhotoID` = `photos`.`PhotoID`
      WHERE `ad`.UserID = ?
      ORDER BY `ad`.DatePosted DESC
   ");
   $select_properties->execute([$user_id]);
   if($select_properties->rowCount() > 0){
      while($fetch_property = $select_properties->fetch(PDO::FETCH_ASSOC)){
         $property_id = $fetch_property['PropertyID'];
         $total_images = $photo_counts_assoc[$property_id] ?? 0;
?>
   <form accept="" method="POST" class="box">
      <input type="hidden" name="property_id" value="<?= $property_id; ?>">
      <div class="thumb">
         <p><i class="far fa-image"></i><span><?= $total_images; ?></span></p> 
         <img src="uploaded_files/<?= $fetch_property['Photo']; ?>" alt="">      
      </div>
      <div class="price"><i class="fas fa-indian-rupee-sign"></i><span><?= $fetch_property['Price_Rent']; ?></span></div>
      <h3 class="name"><?= $fetch_property['AdTitle']; ?></h3>
      <p class="location"><i class="fas fa-map-marker-alt"></i><span><?= $fetch_property['Address']; ?></span></p>
      <div class="flex-btn">
         <a href="update_property.php?get_id=<?= $property_id; ?>" class="btn">update</a>
         <input type="submit" name="delete" value="delete" class="btn" onclick="return confirm('delete this listing?');">
      </div>
      <a href="view_property.php?get_id=<?= $property_id; ?>" class="btn">view property</a>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">No properties added yet! <a href="post_property.php" style="margin-top:1.5rem;" class="btn">add new</a></p>';
      }
      ?>

   </div>

</section>







<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>