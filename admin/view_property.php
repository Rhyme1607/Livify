<?php

include '../components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
   header('Location: login.php'); // Redirect to login page
   exit();
}else{
  $user_id = $_SESSION['user_id'];


if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location: login.php');
}
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>property details</title>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<section class="view-property">

   <h1 class="heading">property details</h1>

   <?php
      $select_properties = $conn->prepare("  SELECT `ad`.*, `property`.*, `property`.`Price/Rent` AS `Price_Rent`, `photos`.`Photo`
      FROM `ad`
      JOIN `property` ON `ad`.PropertyID = `property`.PropertyID AND `ad`.AdID = `property`.AdID
      LEFT JOIN (
         SELECT `PropertyID`, MIN(`PhotoID`) as `PhotoID`
         FROM `property_photos`
         GROUP BY `PropertyID`
      ) `photos_min` ON `ad`.PropertyID = `photos_min`.PropertyID
      LEFT JOIN `property_photos` `photos` ON `photos_min`.`PhotoID` = `photos`.`PhotoID`
      WHERE `ad`.PropertyID = ?
      ORDER BY `ad`.DatePosted DESC limit 1");
      $select_properties->execute([$get_id]);
      if($select_properties->rowCount() > 0){
         while($fetch_property = $select_properties->fetch(PDO::FETCH_ASSOC)){

         $property_id = $fetch_property['PropertyID'];

         $select_user = $conn->prepare("SELECT * FROM `user` WHERE UserID = ?");
         $select_user->execute([$fetch_property['UserID']]);
         $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

   ?>
   <div class="details">
      <?php
         $property_id = $fetch_property['PropertyID'];
         $query = $conn->prepare("SELECT `Photo` FROM `property_photos` WHERE `PropertyID` = ? ORDER BY `PhotoID`");
         $query->execute([$property_id]);
         $photos = $query->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <div class="swiper images-container">
         <div class="swiper-wrapper">
            <?php foreach($photos as $photo): ?>
               <img src="../uploaded_files/<?= $photo['Photo']; ?>" alt="" class="swiper-slide">
            <?php endforeach; ?>
         </div>
         <div class="swiper-pagination"></div>
      </div>
      <h3 class="name"><?= $fetch_property['PropertyName']; ?></h3>
      <p class="location"><i class="fas fa-map-marker-alt"></i><span><?= $fetch_property['Address']; ?></span></p>
      <div class="info">
         <p><i class="fas fa-indian-rupee-sign"></i><span><?= $fetch_property['Price_Rent']; ?></span></p>
         <p><i class="fas fa-user"></i><span><?= $fetch_user['FullName']; ?></span></p>
         <p><i class="fas fa-phone"></i><a href="tel:1234567890"><?= $fetch_user['PhoneNumber']; ?></a></p>
         <p><i class="fas fa-building"></i><span><?= $fetch_property['PropertyType']; ?></span></p>
         <p><i class="fas fa-house"></i><span><?= $fetch_property['Contract']; ?></span></p>
         <p><i class="fas fa-calendar"></i><span><?= $fetch_property['DatePosted']; ?></span></p>
      </div>
      <h3 class="title">details</h3>
      <div class="flex">
         <div class="box">
            <p><i>Area:</i><span><?= $fetch_property['Area']; ?></span></p>
            <p><i>bedroom :</i><span><?= $fetch_property['Bedrooms']; ?></span></p>
            <p><i>bathroom :</i><span><?= $fetch_property['Bathrooms']; ?></span></p>
         </div>
      <h3 class="title">amenities</h3>
      <div class="flex">
         <div class="box">
            <p><i class="fas fa-<?php if($fetch_property['Parking'] == 'Yes'){echo 'check';}else{echo 'times';} ?>"></i><span>parking area</span></p>
         </div>
      </div>
      <h3 class="title">description</h3>
      <p class="description"><?= $fetch_property['Description']; ?></p>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">property not found! <a href="listings.php" style="margin-top:1.5rem;" class="option-btn">go to listings</a></p>';
   }
   ?>

</section>


















<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<?php include '../components/message.php'; ?>

<script>

var swiper = new Swiper(".images-container", {
   effect: "coverflow",
   grabCursor: true,
   centeredSlides: true,
   slidesPerView: "auto",
   loop:true,
   coverflowEffect: {
      rotate: 0,
      stretch: 0,
      depth: 200,
      modifier: 3,
      slideShadows: true,
   },
   pagination: {
      el: ".swiper-pagination",
   },
});

</script>

</body>
</html>