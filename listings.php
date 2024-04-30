<?php  

include 'components/connect.php';

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
}else{
  $user_id = '';
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>All Listings</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- listings section starts  -->

<section class="listings">

   <h1 class="heading">all listings</h1>

   <div class="box-container">
      <?php
         $total_images = 0;
         $select_properties = $conn->prepare("
         SELECT `ad`.*, `property`.*, `property`.`Price/Rent` AS `Price_Rent`, `photos`.`Photo`, `membership`.`MembershipType`
         FROM `ad`
         JOIN `property` ON `ad`.PropertyID = `property`.PropertyID AND `ad`.AdID = `property`.AdID
         LEFT JOIN (
             SELECT `PropertyID`, MIN(`PhotoID`) as `PhotoID`
             FROM `property_photos`
             GROUP BY `PropertyID`
         ) `photos_min` ON `ad`.PropertyID = `photos_min`.PropertyID
         LEFT JOIN `property_photos` `photos` ON `photos_min`.`PhotoID` = `photos`.`PhotoID`
         JOIN `trader` ON `ad`.UserID = `trader`.UserID
         JOIN `membership` ON `trader`.UserID = `membership`.Trader_UserID
         WHERE `membership`.EndDate >= NOW() OR `membership`.EndDate IS NULL
         ORDER BY CASE `membership`.`MembershipType` WHEN 'Premium' THEN 1 ELSE 2 END, `ad`.DatePosted DESC
         ");
         $select_properties->execute();
         if($select_properties->rowCount() > 0){
            while($fetch_property = $select_properties->fetch(PDO::FETCH_ASSOC)){
            $select_user = $conn->prepare("SELECT * FROM `user` WHERE UserID = ?");
            $select_user->execute([$fetch_property['UserID']]);
            $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

            $select_images = $conn->prepare("SELECT COUNT(*) as total_images FROM `property_photos` WHERE PropertyID = ?");
            $select_images->execute([$fetch_property['PropertyID']]);
            $fetch_images = $select_images->fetch(PDO::FETCH_ASSOC);
            $total_images = $fetch_images['total_images'];

      ?>
      <form action="" method="POST">
         <div class="box">
            <div class="thumb">
               <p class="total-images"><i class="far fa-image"></i><span><?= $total_images; ?></span></p> 
               <img src="uploaded_files/<?= $fetch_property['Photo']; ?>" alt="">
            </div>
            <div class="admin">
               <h3><?= substr($fetch_user['FullName'], 0, 1); ?></h3>
               <div>
                  <p><?= $fetch_user['FullName']; ?></p>
                  <span><?= $fetch_property['DatePosted']; ?></span>
               </div>
            </div>
         </div>
         <div class="box">
            <div class="price"><i class="fas fa-indian-rupee-sign"></i><span><?= $fetch_property['Price_Rent']; ?></span></div>
            <h3 class="name"><?= $fetch_property['PropertyName']; ?></h3>
            <p class="location"><i class="fas fa-map-marker-alt"></i><span><?= $fetch_property['Address']; ?></span></p>
            <div class="flex">
               <p><i class="fas fa-house"></i><span><?= $fetch_property['PropertyType']; ?></span></p>
               <p><i class="fas fa-tag"></i><span><?= $fetch_property['Contract']; ?></span></p>
            </div>
            <div class="flex-btn">
               <a href="view_property.php?get_id=<?= $fetch_property['PropertyID']; ?>" class="btn">view property</a>
               <input type="submit" value="send enquiry" name="send" class="btn">
            </div>
         </div>
      </form>
      <?php
         }
      }else{
         echo '<p class="empty">No properties added yet! <a href="post_property.php" style="margin-top:1.5rem;" class="btn">add new</a></p>';
      }
      ?>
      
   </div>

</section>

<!-- listings section ends -->












<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>