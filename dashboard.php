<?php  

include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
   header('Location: login.php'); // Redirect to login page
   exit();
}else{
  $user_id = $_SESSION['user_id'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">

      <div class="box">
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `user` WHERE UserID = ? LIMIT 1");
         $select_profile->execute([$user_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <h3>welcome!</h3>
      <p><?= $fetch_profile['FullName']; ?></p>
      <a href="update.php" class="btn">update profile</a>
      </div>

      <div class="box">
         <h3>filter search</h3>
         <p>Search your dream property</p>
         <a href="search.php" class="btn">search now</a>
      </div>

      <div class="box">
      <?php
      $count_properties = $conn->prepare("SELECT COUNT(`ad`.AdID) AS total_listings
      FROM `ad`
      WHERE `ad`.UserID = ?");
      $count_properties->execute([$user_id]);
      $row = $count_properties->fetch(PDO::FETCH_ASSOC);
      $total_properties = $row['total_listings'];
      ?>
      <h3><?= $total_properties; ?></h3>
      <p>properties listed</p>
      <a href="my_listings.php" class="btn">view all listings</a>
      </div>

   </div>

</section>






















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>