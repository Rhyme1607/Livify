<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session
}
?>

<!-- header section starts  -->

<header class="header">

   <div class="welcome-message">Welcome to Livify</div>
   
   <nav class="navbar nav-1">
      <section class="flex">
      <a href="home.php" class="logo"><img src="images/real logo.png" alt="Livify Logo" /></a>

         <ul>
            <li><a href="post_property.php">Post Ad<i class="fas fa-paper-plane"></i></a></li>
         </ul>
      </section>
   </nav>

   <nav class="navbar nav-2">
      <section class="flex">
         <div id="menu-btn" class="fas fa-bars"></div>

         <div class="menu">
            <ul>
               <li><a href="#">My Listings<i class="fas fa-angle-down"></i></a>
                  <ul>
                     <li><a href="dashboard.php">Dashboard</a></li>
                     <li><a href="post_property.php">Post Ad</a></li>
                     <li><a href="my_listings.php">My Listings</a></li>
                  </ul>
               </li>
               <li><a href="#">Options<i class="fas fa-angle-down"></i></a>
                  <ul>
                     <li><a href="search.php">Filter Search</a></li>
                     <li><a href="listings.php">All listings</a></li>
                  </ul>
               </li>
               <li><a href="#">Help<i class="fas fa-angle-down"></i></a>
                  <ul>
                     <li><a href="about.php">About Us</a></i></li>
                     <li><a href="contact.php">Contact Us</a></i></li>
                     <li><a href="contact.php#faq">FAQ</a></i></li>
                  </ul>
               </li>
            </ul>
         </div>

         <ul>
            <li><a href="#">
            <?php 
               if(isset($_SESSION['user_id'])){
                  // Fetch the username from the database
                  $stmt = $conn->prepare("SELECT `Username` FROM `User` WHERE `UserID` = ?");
                  $stmt->execute([$_SESSION['user_id']]);
                  $user = $stmt->fetch();

                  if($user){
                     echo $user['Username'];
                  } else {
                     echo 'Account';
                  }
               } else {
                  echo 'Account';
               }
               ?>
               <i class="fas fa-angle-down"></i></a>
               <ul>
                  <li><a href="login.php">Login Now</a></li>
                  <li><a href="register.php">Register New</a></li>
                  <?php if(isset($_SESSION['user_id'])){ ?>
                  <li><a href="update.php">Update Profile</a></li>
                  <li><a href="membership.php">Premium Membership</a></li>
                  <li><a href="components/user_logout.php" onclick="return confirm('Do you want to log out?');">Logout</a></li>
                  <?php } ?>
               </ul>
            </li>
         </ul>
      </section>
   </nav>

</header>

<!-- header section ends -->