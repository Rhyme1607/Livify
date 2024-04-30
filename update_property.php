<?php  

include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
   header('Location: login.php'); // Redirect to login page
   exit();
}else{
  $user_id = $_SESSION['user_id'];
}

if(isset($_GET['get_id'])){
   $get_id = $_GET['get_id'];
}else{
   $get_id = '';
   header('location:home.php');
}

if(isset($_POST['update'])){

   $update_id = $_POST['property_id'];
   $update_id = filter_var($update_id, FILTER_SANITIZE_STRING);
   $property_name = $_POST['property_name'];
   $property_name = filter_var($property_name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $area = $_POST['area'];
   $area = filter_var($area, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $contract = $_POST['contract'];
   $contract = filter_var($contract, FILTER_SANITIZE_STRING);
   $type = $_POST['type'];
   $type = filter_var($type, FILTER_SANITIZE_STRING);
   $bedroom = $_POST['bedroom'];
   $bedroom = filter_var($bedroom, FILTER_SANITIZE_STRING);
   $bathroom = $_POST['bathroom'];
   $bathroom = filter_var($bathroom, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);

   if(isset($_POST['parking_area'])){
      $parking_area = $_POST['parking_area'];
      $parking_area = filter_var($parking_area, FILTER_SANITIZE_STRING);
   }else{
      $parking_area = 'no';
   }

   $old_image_01 = $_POST['old_image_1'];
   $old_image_01 = filter_var($old_image_01, FILTER_SANITIZE_STRING);
   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_01_ext = pathinfo($image_01, PATHINFO_EXTENSION);
   $rename_image_01 = $update_id . 'pic-01.' . $image_01_ext;
   $image_01_tmp_name = $_FILES['image_01']['tmp_name'];
   $image_01_size = $_FILES['image_01']['size'];
   $image_01_folder = 'uploaded_files/'.$rename_image_01;

   if(!empty($image_01)){
      if($image_01_size > 2000000){
         $warning_msg[] = 'image 01 size is too large!';
      }else{
         // Fetch the photoID for the first photo of this property
         $select_photo = $conn->prepare("SELECT PhotoID FROM property_photos WHERE PropertyID = ? ORDER BY PhotoID LIMIT 1");
         $select_photo->execute([$update_id]);
         $photo = $select_photo->fetch(PDO::FETCH_ASSOC);
   
         // Update the photo in the property_photos table
         $update_image_01 = $conn->prepare("UPDATE `property_photos` SET Photo = ? WHERE PhotoID = ?");
         $update_image_01->execute([$rename_image_01, $photo['PhotoID']]);
         move_uploaded_file($image_01_tmp_name, $image_01_folder);
         if($old_image_01 != ''){
            unlink('uploaded_files/'.$old_image_01);
         }
      }
   }

   $old_image_02 = $_POST['old_image_2'];
   $old_image_02 = filter_var($old_image_02, FILTER_SANITIZE_STRING);
   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_02_ext = pathinfo($image_02, PATHINFO_EXTENSION);
   $rename_image_02 = $update_id . 'pic-02.' . $image_02_ext;
   $image_02_tmp_name = $_FILES['image_02']['tmp_name'];
   $image_02_size = $_FILES['image_02']['size'];
   $image_02_folder = 'uploaded_files/'.$rename_image_02;

   if(!empty($image_02)){
      if($image_02_size > 2000000){
         $warning_msg[] = 'image 02 size is too large!';
      }else{
         // Fetch the photoID for the second photo of this property
         $select_photo = $conn->prepare("SELECT PhotoID FROM property_photos WHERE PropertyID = ? ORDER BY PhotoID LIMIT 1, 1");
         $select_photo->execute([$update_id]);
         $photo = $select_photo->fetch(PDO::FETCH_ASSOC);
   
         // Update the photo in the property_photos table
         $update_image_02 = $conn->prepare("UPDATE `property_photos` SET Photo = ? WHERE PhotoID = ?");
         $update_image_02->execute([$rename_image_02, $photo['PhotoID']]);
         move_uploaded_file($image_02_tmp_name, $image_02_folder);
         if($old_image_02 != ''){
            unlink('uploaded_files/'.$old_image_02);
         }
      }
   }

   if(isset($_POST['old_image_3'])){
      $old_image_03 = $_POST['old_image_3'];
      $old_image_03 = filter_var($old_image_03, FILTER_SANITIZE_STRING);
      $old_image_03 = filter_var($old_image_03, FILTER_SANITIZE_STRING);
      $image_03 = $_FILES['image_03']['name'];
      $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
      $image_03_ext = pathinfo($image_03, PATHINFO_EXTENSION);
      $rename_image_03 = $update_id . 'pic-03.' . $image_03_ext;
      $image_03_tmp_name = $_FILES['image_03']['tmp_name'];
      $image_03_size = $_FILES['image_03']['size'];
      $image_03_folder = 'uploaded_files/'.$rename_image_03;
      }
   if(!empty($image_03)){
      if($image_03_size > 2000000){
         $warning_msg[] = 'image 03 size is too large!';
      }else{
         // Fetch the PhotoID for the third photo of this property
         $select_photo = $conn->prepare("SELECT PhotoID FROM property_photos WHERE PropertyID = ? ORDER BY PhotoID LIMIT 2, 1");
         $select_photo->execute([$update_id]);
         $photo = $select_photo->fetch(PDO::FETCH_ASSOC);
   
         // Update the photo in the property_photos table
         $update_image_03 = $conn->prepare("UPDATE `property_photos` SET Photo = ? WHERE PhotoID = ?");
         $update_image_03->execute([$rename_image_03, $photo['PhotoID']]);
         move_uploaded_file($image_03_tmp_name, $image_03_folder);
         if($old_image_03 != ''){
            unlink('uploaded_files/'.$old_image_03);
         }
      }
   }

   if(isset($_POST['old_image_4'])){
      $old_image_04 = $_POST['old_image_4'];
      $old_image_04 = filter_var($old_image_04, FILTER_SANITIZE_STRING);
      $image_04 = $_FILES['image_04']['name'];
      $image_04 = filter_var($image_04, FILTER_SANITIZE_STRING);
      $image_04_ext = pathinfo($image_04, PATHINFO_EXTENSION);
      $rename_image_04 = $update_id . 'pic-04.' . $image_04_ext;
      $image_04_tmp_name = $_FILES['image_04']['tmp_name'];
      $image_04_size = $_FILES['image_04']['size'];
      $image_04_folder = 'uploaded_files/'.$rename_image_04;
   }


   if(!empty($image_04)){
      if($image_04_size > 2000000){
         $warning_msg[] = 'image 04 size is too large!';
      }else{
         // Fetch the PhotoID for the fourth photo of this property
         $select_photo = $conn->prepare("SELECT PhotoID FROM property_photos WHERE PropertyID = ? ORDER BY PhotoID LIMIT 3, 1");
         $select_photo->execute([$update_id]);
         $photo = $select_photo->fetch(PDO::FETCH_ASSOC);
   
         // Update the photo in the property_photos table
         $update_image_04 = $conn->prepare("UPDATE `property_photos` SET Photo = ? WHERE PhotoID = ?");
         $update_image_04->execute([$rename_image_04, $photo['PhotoID']]);
         move_uploaded_file($image_04_tmp_name, $image_04_folder);
         if($old_image_04 != ''){
            unlink('uploaded_files/'.$old_image_04);
         }
      }
   }

   if(isset($_POST['old_image_5'])){
      $old_image_05 = $_POST['old_image_5'];
      $old_image_05 = filter_var($old_image_05, FILTER_SANITIZE_STRING);
      $image_05 = $_FILES['image_05']['name'];
      $image_05 = filter_var($image_05, FILTER_SANITIZE_STRING);
      $image_05_ext = pathinfo($image_05, PATHINFO_EXTENSION);
      $rename_image_05 = $update_id . 'pic-05.' . $image_05_ext;
      $image_05_tmp_name = $_FILES['image_05']['tmp_name'];
      $image_05_size = $_FILES['image_05']['size'];
      $image_05_folder = 'uploaded_files/'.$rename_image_05;
   }


   if(!empty($image_05)){
      if($image_05_size > 2000000){
         $warning_msg[] = 'image 05 size is too large!';
      }else{
         // Fetch the PhotoID for the fifth photo of this property
         $select_photo = $conn->prepare("SELECT PhotoID FROM property_photos WHERE PropertyID = ? ORDER BY PhotoID LIMIT 4, 1");
         $select_photo->execute([$update_id]);
         $photo = $select_photo->fetch(PDO::FETCH_ASSOC);
   
         // Update the photo in the property_photos table
         $update_image_05 = $conn->prepare("UPDATE `property_photos` SET Photo = ? WHERE PhotoID = ?");
         $update_image_05->execute([$rename_image_05, $photo['PhotoID']]);
         move_uploaded_file($image_05_tmp_name, $image_05_folder);
         if($old_image_05 != ''){
            unlink('uploaded_files/'.$old_image_05);
         }
      }
   }

   $update_listing = $conn->prepare("UPDATE `property` SET `PropertyName` = ?, `Area` = ?, `Address` = ?, `Price/Rent` = ?, `PropertyType` = ?, `Contract` = ?, `Bedrooms` = ?, `Bathrooms` = ?, `Parking` = ?, `Description` = ? WHERE `PropertyID` = ?");
   $update_listing->execute([$property_name, $area, $address, $price, $type, $contract, $bedroom, $bathroom, $parking_area, $description, $update_id]);

   $datePosted = date('Y-m-d H:i:s');
   $update_ad = $conn->prepare("UPDATE `ad` SET `AdTitle` = ?, `DatePosted` = ? WHERE `PropertyID` = ?");
   $update_ad->execute([$property_name, $datePosted, $update_id]);

   $success_msg[] = 'Listing Updated Successfully!';

}

if(isset($_POST['delete_image_02'])){

   $old_image_02 = $_POST['old_image_02'];
   $old_image_02 = filter_var($old_image_02, FILTER_SANITIZE_STRING);

   // Fetch the PhotoID for the second photo of this property
   $select_photo = $conn->prepare("SELECT PhotoID FROM property_photos WHERE PropertyID = ? ORDER BY PhotoID LIMIT 1, 1");
   $select_photo->execute([$get_id]);
   $photo = $select_photo->fetch(PDO::FETCH_ASSOC);

   // Delete the photo from the property_photos table
   $delete_image_02 = $conn->prepare("DELETE FROM `property_photos` WHERE PhotoID = ?");
   $delete_image_02->execute([$photo['PhotoID']]);

   if($old_image_02 != ''){
      unlink('uploaded_files/'.$old_image_02);
      $success_msg[] = 'image 02 deleted!';
   }

}

if(isset($_POST['delete_image_03'])){

   $old_image_03 = $_POST['old_image_03'];
   $old_image_03 = filter_var($old_image_03, FILTER_SANITIZE_STRING);

   // Fetch the PhotoID for the third photo of this property
   $select_photo = $conn->prepare("SELECT PhotoID FROM property_photos WHERE PropertyID = ? ORDER BY PhotoID LIMIT 2, 1");
   $select_photo->execute([$get_id]);
   $photo = $select_photo->fetch(PDO::FETCH_ASSOC);

   // Delete the photo from the property_photos table
   $delete_image_03 = $conn->prepare("DELETE FROM `property_photos` WHERE PhotoID = ?");
   $delete_image_03->execute([$photo['PhotoID']]);

   if($old_image_03 != ''){
      unlink('uploaded_files/'.$old_image_03);
      $success_msg[] = 'image 03 deleted!';
   }

}

if(isset($_POST['delete_image_04'])){

   $old_image_04 = $_POST['old_image_04'];
   $old_image_04 = filter_var($old_image_04, FILTER_SANITIZE_STRING);

   // Fetch the PhotoID for the fourth photo of this property
   $select_photo = $conn->prepare("SELECT PhotoID FROM property_photos WHERE PropertyID = ? ORDER BY PhotoID LIMIT 3, 1");
   $select_photo->execute([$get_id]);
   $photo = $select_photo->fetch(PDO::FETCH_ASSOC);

   // Delete the photo from the property_photos table
   $delete_image_04 = $conn->prepare("DELETE FROM `property_photos` WHERE PhotoID = ?");
   $delete_image_04->execute([$photo['PhotoID']]);

   if($old_image_04 != ''){
      unlink('uploaded_files/'.$old_image_04);
      $success_msg[] = 'image 04 deleted!';
   }

}

if(isset($_POST['delete_image_05'])){

   $old_image_05 = $_POST['old_image_05'];
   $old_image_05 = filter_var($old_image_05, FILTER_SANITIZE_STRING);

   // Fetch the PhotoID for the fifth photo of this property
   $select_photo = $conn->prepare("SELECT PhotoID FROM property_photos WHERE PropertyID = ? ORDER BY PhotoID LIMIT 4, 1");
   $select_photo->execute([$get_id]);
   $photo = $select_photo->fetch(PDO::FETCH_ASSOC);

   // Delete the photo from the property_photos table
   $delete_image_05 = $conn->prepare("DELETE FROM `property_photos` WHERE PhotoID = ?");
   $delete_image_05->execute([$photo['PhotoID']]);

   if($old_image_05 != ''){
      unlink('uploaded_files/'.$old_image_05);
      $success_msg[] = 'image 05 deleted!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update property</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="property-form">

   <?php
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
         WHERE `ad`.PropertyID = ?
         ORDER BY `ad`.DatePosted DESC
      ");
      $select_properties->execute([$get_id]);
      if($select_properties->rowCount() > 0){
         while($fetch_property = $select_properties->fetch(PDO::FETCH_ASSOC)){
         $property_id = $fetch_property['PropertyID'];
      
      $select_photos = $conn->prepare("SELECT Photo FROM property_photos WHERE PropertyID = ? ORDER BY photoID");
      $select_photos->execute([$get_id]);
      $fetch_photos = $select_photos->fetchAll(PDO::FETCH_ASSOC);
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="property_id" value="<?= $property_id; ?>">
      <?php foreach($fetch_photos as $index => $photo): ?>
         <input type="hidden" name="old_image_<?= $index + 1 ?>" value="<?= $photo['Photo']; ?>">
      <?php endforeach; ?>
      <h3>property details</h3>
      <div class="box">
         <p>property name <span>*</span></p>
         <input type="text" name="property_name" required maxlength="50" placeholder="enter property name" value="<?= $fetch_property['AdTitle']; ?>" class="input">
      </div>
      <div class="flex">
         <div class="box">
            <p>property price <span>*</span></p>
            <input type="number" name="price" required min="0" max="9999999999" maxlength="10" value="<?= $fetch_property['Price_Rent']; ?>" placeholder="enter property price" class="input">
         </div>
         <div class="box">
            <p>property address <span>*</span></p>
            <input type="text" name="address" required maxlength="100" placeholder="enter property full address" class="input" value="<?= $fetch_property['Address']; ?>">
         </div>
         <div class="box">
            <p>Contract<span>*</span></p>
            <select name="contract" required class="input">
               <option value="<?= $fetch_property['Contract']; ?>" selected><?= $fetch_property['Contract']; ?></option>
               <option value="sale">Sale</option>
               <option value="rent">Rent</option>
            </select>
         </div>
         <div class="box">
            <p>property type<span>*</span></p>
            <select name="type" required class="input">
               <option value="<?= $fetch_property['PropertyType']; ?>" selected><?= $fetch_property['PropertyType']; ?></option>
               <option value="flat">Flat</option>
               <option value="house">House</option>
               <option value="shop">Shop</option>
            </select>
         </div>
         <div class="box">
            <p>how many bedrooms <span>*</span></p>
            <select name="bedroom" required class="input">
               <option value="<?= $fetch_property['Bedrooms']; ?>" selected><?= $fetch_property['Bedrooms']; ?> bedroom</option>
               <option value="0">No Bedrooms</option>
               <option value="1">1 Bedroom</option>
               <option value="2">2 Bedroom</option>
               <option value="3">3 Bedroom</option>
               <option value="4">4 Bedroom</option>
               <option value="5">5 Bedroom</option>
               <option value="6">6 Bedroom</option>
               <option value="7">7 Bedroom</option>
               <option value="8">8 Bedroom</option>
               <option value="9">9 Bedroom</option>
            </select>
         </div>
         <div class="box">
            <p>how many bathrooms <span>*</span></p>
            <select name="bathroom" required class="input">
               <option value="<?= $fetch_property['Bathrooms']; ?>" selected><?= $fetch_property['Bathrooms']; ?> bathroom</option>
               <option value="1">No Bathroom</option>
               <option value="1">1 Bathroom</option>
               <option value="2">2 Bathroom</option>
               <option value="3">3 Bathroom</option>
               <option value="4">4 Bathroom</option>
               <option value="5">5 Bathroom</option>
               <option value="6">6 Bathroom</option>
               <option value="7">7 Bathroom</option>
               <option value="8">8 Bathroom</option>
               <option value="9">9 Bathroom</option>
            </select>
         </div>
      <div class="box">
         <p>property description <span>*</span></p>
         <textarea name="description" maxlength="1000" class="input" required cols="30" rows="10" placeholder="write about property..." ><?= $fetch_property['Description']; ?></textarea>
      </div>
      <div class="box">
         <p>Area<span>*</span></p>
         <select name="area" required class="input">
            <option value="Hatirjheel Lake">Hatirjheel Lake</option>
            <option value="Abdullahpur">Abdullahpur</option>
            <option value="Bochila">Bochila</option>
            <option value="Adabor">Adabor</option>
            <option value="Uttara">Uttara</option>
            <option value="Mirpur">Mirpur</option>
            <option value="Pallabi">Pallabi</option>
            <option value="Kazipara">Kazipara</option>
            <option value="Kafrul">Kafrul</option>
            <option value="Agargaon">Agargaon</option>
            <option value="Sher-e-Bangla Nagar">Sher-e-Bangla Nagar</option>
            <option value="Cantonment area">Cantonment area</option>
            <option value="Banani">Banani</option>
            <option value="Gulshan">Gulshan</option>
            <option value="Niketan, Gulshan">Niketan, Gulshan</option>
            <option value="Mohakhali">Mohakhali</option>
            <option value="Bashundhara">Bashundhara</option>
            <option value="Banasree">Banasree</option>
            <option value="Baridhara">Baridhara</option>
            <option value="Uttarkhan">Uttarkhan</option>
            <option value="Dakshinkhan">Dakshinkhan</option>
            <option value="Bawnia">Bawnia</option>
            <option value="Khilkhet">Khilkhet</option>
            <option value="Tejgaon">Tejgaon</option>
            <option value="Farmgate">Farmgate</option>
            <option value="Mohammadpur">Mohammadpur</option>
            <option value="Rampura">Rampura</option>
            <option value="Badda">Badda</option>
            <option value="Satarkul">Satarkul</option>
            <option value="Beraid">Beraid</option>
            <option value="Khilgaon">Khilgaon</option>
            <option value="Vatara">Vatara</option>
            <option value="Gabtali">Gabtali</option>
            <option value="Sadarghat">Sadarghat</option>
            <option value="Hazaribagh">Hazaribagh</option>
            <option value="Dhanmondi">Dhanmondi</option>
            <option value="Segunbagicha">Segunbagicha</option>
            <option value="Ramna">Ramna</option>
            <option value="Motijheel">Motijheel</option>
            <option value="Sabujbagh">Sabujbagh</option>
            <option value="Lalbagh">Lalbagh</option>
            <option value="Kamalapur">Kamalapur</option>
            <option value="Kakrail">Kakrail</option>
            <option value="Kamrangirchar">Kamrangirchar</option>
            <option value="Islampur">Islampur</option>
            <option value="Sadarghat">Sadarghat</option>
            <option value="Wari">Wari</option>
            <option value="Kotwali">Kotwali</option>
            <option value="Sutrapur">Sutrapur</option>
            <option value="Jurain">Jurain</option>
            <option value="Dania">Dania</option>
            <option value="Demra">Demra</option>
            <option value="Shyampur">Shyampur</option>
            <option value="Nimtoli">Nimtoli</option>
            <option value="Matuail">Matuail</option>
            <option value="Paribagh">Paribagh</option>
            <option value="Shahbagh">Shahbagh</option>
            <option value="Paltan">Paltan</option>
            <option value="National Martyrs' Memorial, Savar">National Martyrs' Memorial, Savar</option>
            <option value="Ashulia">Ashulia</option>
            <option value="Birulia">Birulia</option>
            <option value="Savar">Savar</option>
            <option value="Skyline of Hasnabad">Skyline of Hasnabad</option>
            <option value="Hasnabad">Hasnabad</option>
            <option value="Jinjira">Jinjira</option>
            <option value="Tegharia">Tegharia</option>
            <option value="Jhilmil">Jhilmil</option>
            <option value="Tongi">Tongi</option>
            <option value="Gazipur">Gazipur</option>
            <option value="Skyline of Narayanganj">Skyline of Narayanganj</option>
            <option value="Fatullah">Fatullah</option>
            <option value="Siddhirganj">Siddhirganj</option>
            <option value="Narayanganj">Narayanganj</option>
         </select>
      </div>
      <div class="checkbox">
         <div class="box">
            <p><input type="checkbox" name="parking_area" value="yes" <?php if($fetch_property['Parking'] == 'yes'){echo 'checked'; } ?> />parking area</p>
         </div>
      </div>
      <div class="box">
         <img src="uploaded_files/<?= $fetch_photos[0]['Photo']; ?>" class="image" alt="">
         <p>update image 01</p>
         <input type="file" name="image_01" class="input" accept="image/*">
      </div>
      <div class="flex"> 
         <div class="box">
            <?php if(!empty($fetch_photos[1]['Photo'])){ ?>
            <img src="uploaded_files/<?= $fetch_photos[1]['Photo']; ?>" class="image" alt="">
            <input type="submit" value="delete image 02" name="delete_image_02" class="inline-btn" onclick="return confirm('delete image 02');">
            <?php } ?>
            <p>update image 02</p>
            <input type="file" name="image_02" class="input" accept="image/*">
         </div>
         <div class="box">
         <?php if(!empty($fetch_photos[2]['Photo'])){ ?>
            <img src="uploaded_files/<?= $fetch_photos[2]['Photo']; ?>" class="image" alt="">
            <input type="submit" value="delete image 03" name="delete_image_03" class="inline-btn" onclick="return confirm('delete image 03');">
            <?php } ?>
            <p>update image 03</p>
            <input type="file" name="image_03" class="input" accept="image/*">
         </div>
         <div class="box">
         <?php if(!empty($fetch_photos[3]['Photo'])){ ?>
            <img src="uploaded_files/<?= $fetch_photos[3]['Photo']; ?>" class="image" alt="">
            <input type="submit" value="delete image 04" name="delete_image_04" class="inline-btn" onclick="return confirm('delete image 04');">
            <?php } ?>
            <p>update image 04</p>
            <input type="file" name="image_04" class="input" accept="image/*">
         </div>
         <div class="box">
         <?php if(!empty($fetch_photos[4]['Photo'])){ ?>
            <img src="uploaded_files/<?= $fetch_photos[4]['Photo']; ?>" class="image" alt="">
            <input type="submit" value="delete image 05" name="delete_image_05" class="inline-btn" onclick="return confirm('delete image 05');">
            <?php } ?>
            <p>update image 05</p>
            <input type="file" name="image_02" class="input" accept="image/*">
         </div>   
      </div>
      <input type="submit" value="update property" class="btn" name="update">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">property not found! <a href="post_property.php" style="margin-top:1.5rem;" class="btn">add new</a></p>';
   }
   ?>

</section>






<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>