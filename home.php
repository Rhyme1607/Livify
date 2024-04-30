<?php  

include 'components/connect.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>


<!-- home section starts  -->

<div class="home">

   <video autoplay muted loop id="myVideo">
      <source src="images/intro r.mp4" type="video/mp4">
   </video>
   <section class="center">

      <form action="search.php" method="post">
         <h3>find your perfect home</h3>
         <div class="box">
            <p>Enter Location <span>*</span></p>
            <input type="text" name="h_location" required maxlength="100" placeholder="Enter City Name" class="input">
         </div>
         <div class="flex">
            <div class="box">
               <p>Property Type <span>*</span></p>
               <select name="h_type" class="input" required>
                  <option value="flat">Flat</option>
                  <option value="house">House</option>
                  <option value="shop">Shop</option>
               </select>
            </div>
            <div class="box">
               <p>Contract<span>*</span></p>
               <select name="h_contract" class="input" required>
                  <option value="sale">Sale</option>
                  <option value="rent">Rent</option>
               </select>
            </div>
            <div class="box">
               <p>Minimum Budget<span>*</span></p>
               <select name="h_min" class="input" required>
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
               <select name="h_max" class="input" required>
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
         </div>
         <input type="submit" value="search property" name="h_search" class="btn">
      </form>

   </section>

</div>

<!-- home section ends -->

<!-- services section starts  -->

<section class="services">

   <h1 class="heading">our services</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/icon-1.png" alt="">
         <h3>buy house</h3>
         <p>Find your dream home from our extensive collection of properties.</p>
      </div>

      <div class="box">
         <img src="images/icon-2.png" alt="">
         <h3>rent house</h3>
         <p>Explore rental options that fit your budget and lifestyle. We got your back!</p>
      </div>

      <div class="box">
         <img src="images/icon-3.png" alt="">
         <h3>sell house</h3>
         <p>Get the best market price for your property to sell or rent with our help. </p>
      </div>

      <div class="box">
         <img src="images/icon-4.png" alt="">
         <h3>flats and buildings</h3>
         <p>Discover a wide range of flats and buildings suitable for all needs and budgets.</p>
      </div>

      <div class="box">
         <img src="images/icon-5.png" alt="">
         <h3>shops and malls</h3>
         <p>Find the perfect commercial space for your business in our selection of shops and malls.</p>
      </div>

      <div class="box">
         <img src="images/icon-6.png" alt="">
         <h3>24/7 service</h3>
         <p>Our dedicated team is available around the clock to assist with all your real estate needs.</p>
      </div>

   </div>

</section>

<!-- services section ends -->

<!-- listings section starts  -->

<section class="listings">

   <h1 class="heading">latest listings</h1>

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
         ORDER BY CASE `membership`.`MembershipType` WHEN 'Premium' THEN 1 ELSE 2 END, `ad`.DatePosted DESC LIMIT 6
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
         echo '<p class="empty">no properties added yet! <a href="post_property.php" style="margin-top:1.5rem;" class="btn">add new</a></p>';
      }
      ?>
      
   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="listings.php" class="inline-btn">view all</a>
   </div>

</section>

<!-- listings section ends -->








<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

<script>

   let range = document.querySelector("#range");
   range.oninput = () =>{
      document.querySelector('#output').innerHTML = range.value;
   }

</script>

</body>
</html>