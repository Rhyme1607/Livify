<?php  

include 'components/connect.php';


session_start(); // Start the session

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- search filter section starts  -->

<section class="filters" style="padding-bottom: 0;">

   <form action="" method="post">
      <div id="close-filter"><i class="fas fa-times"></i></div>
      <h3>filter your search</h3>
         
         <div class="flex">
            <div class="box">
               <p>enter location</p>
               <input type="text" name="location" required maxlength="50" placeholder="enter city name" class="input">
            </div>
            <div class="box">
               <p>Contract</p>
               <select name="contract" class="input" required>
                  <option value="sale">sale</option>
                  <option value="rent">rent</option>
               </select>
            </div>
            <div class="box">
               <p>property type</p>
               <select name="type" class="input" required>
                  <option value="flat">flat</option>
                  <option value="house">house</option>
                  <option value="shop">shop</option>
               </select>
            </div>
            <div class="box">
               <p>Minimum Budget<span>*</span></p>
               <select name="min" class="input" required>
                  <option value="5000">BDT 5,000</option>
                  <option value="10000">BDT 10,000</option>
                  <option value="30000">BDT 30,000</option>
                  <option value="30000">BDT 50,000</option>
                  <option value="100000">BDT 1,00,000</option>
                  <option value="500000">BDT 5,00,000</option>
                  <option value="1000000">BDT 10,00,000</option>
                  <option value="5000000">BDT 50,00,000</option>
                  <option value="10000000">BDT 1,00,00,000</option>
                  <option value="50000000">BDT 5,00,00,000</option>
               </select>
            </div>
            <div class="box">
               <p>Maximum Budget <span>*</span></p>
               <select name="max" class="input" required>
                  <option value="10000">BDT 10,000</option>
                  <option value="30000">BDT 30,000</option>
                  <option value="50000">BDT 50,000</option>
                  <option value="100000">BDT 1,00,000</option>
                  <option value="500000">BDT 5,00,000</option>
                  <option value="1000000">BDT 10,00,000</option>
                  <option value="5000000">BDT 50,00,000</option>
                  <option value="10000000">BDT 1,00,00,000</option>
                  <option value="100000000">BDT 10,00,00,000</option>
               </select>
            </div>
         <input type="submit" value="search property" name="filter_search" class="btn">
   </form>

</section>

<!-- search filter section ends -->

<div id="filter-btn" class="fas fa-filter"></div>

<?php

if(isset($_POST['h_search'])){

   $h_location = $_POST['h_location'];
   $h_location = filter_var($h_location, FILTER_SANITIZE_STRING);
   $h_type = $_POST['h_type'];
   $h_type = filter_var($h_type, FILTER_SANITIZE_STRING);
   $h_contract = $_POST['h_contract'];
   $h_contract = filter_var($h_contract, FILTER_SANITIZE_STRING);
   $h_min = $_POST['h_min'];
   $h_min = filter_var($h_min, FILTER_SANITIZE_STRING);
   $h_max = $_POST['h_max'];
   $h_max = filter_var($h_max, FILTER_SANITIZE_STRING);

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
   WHERE `property`.Address LIKE '%{$h_location}%' 
   AND `property`.PropertyType LIKE '%{$h_type}%' 
   AND `property`.Contract LIKE '%{$h_contract}%' 
   AND `property`.`Price/Rent` BETWEEN $h_min AND $h_max 
   AND (`membership`.EndDate >= NOW() OR `membership`.EndDate IS NULL)
   ORDER BY CASE `membership`.`MembershipType` WHEN 'Premium' THEN 1 ELSE 2 END, `ad`.DatePosted DESC
   ");
   $select_properties->execute();

}elseif(isset($_POST['filter_search'])){

   $location = $_POST['location'];
   $location = filter_var($location, FILTER_SANITIZE_STRING);
   $type = $_POST['type'];
   $type = filter_var($type, FILTER_SANITIZE_STRING);
   $contract = $_POST['contract'];
   $contract = filter_var($contract, FILTER_SANITIZE_STRING);
   $min = $_POST['min'];
   $min = filter_var($min, FILTER_SANITIZE_STRING);
   $max = $_POST['max'];
   $max = filter_var($max, FILTER_SANITIZE_STRING);
   
   $select_properties = $conn->prepare("
   SELECT `ad`.*, `property`.*, `property`.`Price/Rent` AS `Price_Rent`, `photos`.`Photo`
   FROM `ad`
   JOIN `property` ON `ad`.PropertyID = `property`.PropertyID AND `ad`.AdID = `property`.AdID
   JOIN `trader` ON `ad`.UserID = `trader`.UserID
   LEFT JOIN (
      SELECT `PropertyID`, MIN(`PhotoID`) as `PhotoID`
      FROM `property_photos`
      GROUP BY `PropertyID`
   ) `photos_min` ON `ad`.PropertyID = `photos_min`.PropertyID
   LEFT JOIN `property_photos` `photos` ON `photos_min`.`PhotoID` = `photos`.`PhotoID`
   LEFT JOIN `membership` ON `trader`.MembershipID = `membership`.MembershipID
   WHERE (`property`.Address LIKE '%{$location}%' 
   AND `property`.PropertyType LIKE '%{$type}%' 
   AND `property`.Contract LIKE '%{$contract}%' 
   AND `property`.`Price/Rent` BETWEEN $min AND $max)
   ORDER BY CASE WHEN `membership`.MembershipType = 'Premium' THEN 0 ELSE 1 END, `ad`.DatePosted DESC
");
   $select_properties->execute();

}else{
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
   ORDER BY CASE `membership`.`MembershipType` WHEN 'Premium' THEN 1 ELSE 2 END, `ad`.DatePosted DESC LIMIT 6
   ");
   $select_properties->execute();
}

?>

<!-- listings section starts  -->

<section class="listings">

   <?php 
      if(isset($_POST['h_search']) or isset($_POST['filter_search'])){
         echo '<h1 class="heading">search results</h1>';
      }else{
         echo '<h1 class="heading">latest listings</h1>';
      }
   ?>

   <div class="box-container">
      <?php
         $total_images = 0;
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
         echo '<p class="empty">no results found!</p>';
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

<script>

document.querySelector('#filter-btn').onclick = () =>{
   document.querySelector('.filters').classList.add('active');
}

document.querySelector('#close-filter').onclick = () =>{
   document.querySelector('.filters').classList.remove('active');
}

</script>

</body>
</html>