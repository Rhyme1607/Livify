<?php  

session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page
    exit();
}else{
   $user_id = $_SESSION['user_id'];
}

include 'components/connect.php';


if(isset($_POST['post'])){
   function generateUniqueID($conn, $table, $column, $prefix) {
      $id = $prefix . random_int(10000001, 99999999);
  
      // Prepare the SQL statement
      $stmt = $conn->prepare("SELECT * FROM $table WHERE $column = ?");
  
      // Execute the SQL statement
      $stmt->execute([$id]);
  
      // If the ID already exists in the database, generate a new one
      while ($stmt->fetch()) {
          $id = $prefix . random_int(10000001, 99999999);
          $stmt->execute([$id]);
      }
  
      return $id;
  }
  

   $locationid = generateUniqueID($conn, 'location', 'LocationID', 'L');
   $adID = generateUniqueID($conn, 'ad', 'AdID', 'A');
   $property_id = generateUniqueID($conn, 'property', 'PropertyID', 'P');
   $property_name = $_POST['property_name'];
   $property_name = filter_var($property_name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $location_name = 'Dhaka';
   $contract = $_POST['contract'];
   $contract = filter_var($contract, FILTER_SANITIZE_STRING);
   $type = $_POST['type'];
   $type = filter_var($type, FILTER_SANITIZE_STRING);;
   $bedroom = $_POST['bedroom'];
   $bedroom = filter_var($bedroom, FILTER_SANITIZE_STRING);
   $bathroom = $_POST['bathroom'];
   $bathroom = filter_var($bathroom, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
   $parking_area = isset($_POST['parking_area']) ? filter_var($_POST['parking_area'], FILTER_SANITIZE_STRING) : 'No';
   $latitude = $_POST['latitude'];
   $longitude = $_POST['longitude'];
   $area = $_POST['area'];
   $area = filter_var($area, FILTER_SANITIZE_STRING);

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_02_ext = pathinfo($image_02, PATHINFO_EXTENSION);
   $rename_image_02 = $property_id . 'pic-02.' . $image_02_ext;
   $image_02_tmp_name = $_FILES['image_02']['tmp_name'];
   $image_02_size = $_FILES['image_02']['size'];
   $image_02_folder = 'uploaded_files/'.$rename_image_02;   

   if(!empty($image_02)){
      if($image_02_size > 2000000){
         $warning_msg[] = 'Image-2 size is too large!';
      }else{
         move_uploaded_file($image_02_tmp_name, $image_02_folder);
      }
   }else{
      $rename_image_02 = '';
   }

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_03_ext = pathinfo($image_03, PATHINFO_EXTENSION);
   $rename_image_03 = $property_id . 'pic-03.' . $image_03_ext;
   $image_03_tmp_name = $_FILES['image_03']['tmp_name'];
   $image_03_size = $_FILES['image_03']['size'];
   $image_03_folder = 'uploaded_files/'.$rename_image_03;

   if(!empty($image_03)){
      if($image_03_size > 2000000){
         $warning_msg[] = 'Image-3 size is too large!';
      }else{
         move_uploaded_file($image_03_tmp_name, $image_03_folder);
      }
   }else{
      $rename_image_03 = '';
   }

   $image_04 = $_FILES['image_04']['name'];
   $image_04 = filter_var($image_04, FILTER_SANITIZE_STRING);
   $image_04_ext = pathinfo($image_04, PATHINFO_EXTENSION);
   $rename_image_04 = $property_id . 'pic-04.' . $image_04_ext;
   $image_04_tmp_name = $_FILES['image_04']['tmp_name'];
   $image_04_size = $_FILES['image_04']['size'];
   $image_04_folder = 'uploaded_files/'.$rename_image_04;

   if(!empty($image_04)){
      if($image_04_size > 2000000){
         $warning_msg[] = 'Image-4 size is too large!';
      }else{
         move_uploaded_file($image_04_tmp_name, $image_04_folder);
      }
   }else{
      $rename_image_04 = '';
   }

   $image_05 = $_FILES['image_05']['name'];
   $image_05 = filter_var($image_05, FILTER_SANITIZE_STRING);
   $image_05_ext = pathinfo($image_05, PATHINFO_EXTENSION);
   $rename_image_05 = $property_id . 'pic-05.' . $image_05_ext;
   $image_05_tmp_name = $_FILES['image_05']['tmp_name'];
   $image_05_size = $_FILES['image_05']['size'];
   $image_05_folder = 'uploaded_files/'.$rename_image_05;

   if(!empty($image_05)){
      if($image_05_size > 2000000){
         $warning_msg[] = 'Image-5 size is too large!';
      }else{
         move_uploaded_file($image_05_tmp_name, $image_05_folder);
      }
   }else{
      $rename_image_05 = '';
   }

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_01_ext = pathinfo($image_01, PATHINFO_EXTENSION);
   $rename_image_01 = $property_id . 'pic-01.' . $image_01_ext;
   $image_01_tmp_name = $_FILES['image_01']['tmp_name'];
   $image_01_size = $_FILES['image_01']['size'];
   $image_01_folder = 'uploaded_files/'.$rename_image_01;

   if($image_01_size > 2000000){
      $warning_msg[] = 'Image-1 size too large!';
   }else{
      $insert_location = $conn->prepare("INSERT INTO location (LocationID, Name, Longitude, Latitude) VALUES(?,?,?,?)");
      $insert_location->execute([$locationid, $location_name, $longitude, $latitude]);
      $insert_property = $conn->prepare("INSERT INTO `property`(PropertyID, `Price/Rent`, PropertyName, PropertyType, Description, Bedrooms, Bathrooms, Contract, Address, Area, Parking, LocationID) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)"); 
      $insert_property->execute([$property_id, $price, $property_name, $type, $description, $bedroom, $bathroom, $contract, $address, $area, $parking_area, $locationid]);
      
      $adType = 'Regular';
      $adTitle = $property_name;
      $datePosted = date('Y-m-d H:i:s'); // current date and time
      $insert_ad = $conn->prepare("INSERT INTO `ad`(AdID, AdType, AdTitle, DatePosted, UserID, PropertyID) VALUES(?,?,?,?,?,?)"); 
      $insert_ad->execute([$adID, $adType, $adTitle, $datePosted, $user_id, $property_id]);

      $update_property = $conn->prepare("UPDATE `property` SET AdID = ? WHERE PropertyID = ?");
      $update_property->execute([$adID, $property_id]);

      $photo = $_FILES['image_01']['name'];
      $photo = filter_var($photo, FILTER_SANITIZE_STRING);
      $insert_photo = $conn->prepare("INSERT INTO `property_photos`(PropertyID, Photo) VALUES(?,?)"); 
      $insert_photo->execute([$property_id, $rename_image_01]);
      move_uploaded_file($image_01_tmp_name, $image_01_folder);
      if(!empty($image_02)){
         $insert_photo->execute([$property_id, $rename_image_02]);
      }
      if(!empty($image_03)){
         $insert_photo->execute([$property_id, $rename_image_03]);
      }
      if(!empty($image_04)){
         $insert_photo->execute([$property_id, $rename_image_04]);
      }
      if(!empty($image_05)){
         $insert_photo->execute([$property_id, $rename_image_05]);
      }
      $success_msg[] = 'Property Posted Successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Post Ad</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
   <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
   <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@2.11.2/dist/leaflet-geoman.css" />
   <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@2.11.2"></script>
   <script src="https://unpkg.com/leaflet-geosearch@3.0.6/dist/geosearch.umd.js"></script>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="property-form">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>property details</h3>
      <div class="box">
         <p>Property Name <span>*</span></p>
         <input type="text" name="property_name" required maxlength="50" placeholder="Enter Property Name" class="input">
      </div>
      <div class="flex">
         <div class="box">
            <p>Property Price/Rent <span>*</span></p>
            <input type="number" name="price" required min="0" max="9999999999" maxlength="10" placeholder="Enter Property Price/Rent" class="input">
         </div>
         <div class="box">
            <p>Property Address<span>*</span></p>
            <input type="text" name="address" required maxlength="100" placeholder="Enter Property Address" class="input">
         </div>
         <input type="hidden" id="latitude" name="latitude">
         <input type="hidden" id="longitude" name="longitude">
         <div class="box">
            <p>Property in Map:<span>*</span></p>
            <div id="map" style="width: 1110px; height: 400px;"></div>
            <script>
               // Set the default location to Dhaka, Bangladesh
               const map = L.map('map').setView([23.8103, 90.4125], 13);

               const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                     maxZoom: 19,
                     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
               }).addTo(map);

               // Place the marker at Dhaka, Bangladesh
               let marker = L.marker([23.8103, 90.4125], {draggable: true}).addTo(map);
               marker.on('dragend', function(e) {
                     let position = marker.getLatLng();
                     document.getElementById('latitude').value = position.lat;
                     document.getElementById('longitude').value = position.lng;
               });
            </script>
         </div>
         <div class="box">
            <p>Contract<span>*</span></p>
            <select name="contract" required class="input">
               <option value="sale">Sale</option>
               <option value="rent">Rent</option>
            </select>
         </div>
         <div class="box">
            <p>Property Type<span>*</span></p>
            <select name="type" required class="input">
               <option value="flat">Flat</option>
               <option value="house">House</option>
               <option value="shop">Shop</option>
            </select>
         </div>
         <div class="box">
            <p>How Many Bedrooms<span>*</span></p>
            <select name="bedroom" required class="input">
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
            <p>How Many Bathrooms<span>*</span></p>
            <select name="bathroom" required class="input">
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
      </div>
      <div class="box">
         <p>Property Description<span>*</span></p>
         <textarea name="description" maxlength="1000" class="input" required cols="30" rows="10" placeholder="Describe The Property..."></textarea>
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
            <p><input type="checkbox" name="parking_area" value="yes" />Parking</p>
         </div>
      </div>
      <div class="box">
         <p>Image-1 <span>*</span></p>
         <input type="file" name="image_01" class="input" accept="image/*" required>
      </div>
      <div class="flex"> 
         <div class="box">
            <p>Image-2</p>
            <input type="file" name="image_02" class="input" accept="image/*">
         </div>
         <div class="box">
            <p>Image-3</p>
            <input type="file" name="image_03" class="input" accept="image/*">
         </div>
         <div class="box">
            <p>Image-4</p>
            <input type="file" name="image_04" class="input" accept="image/*">
         </div>
         <div class="box">
            <p>Image-5</p>
            <input type="file" name="image_05" class="input" accept="image/*">
         </div>   
      </div>
      <input type="submit" value="post property" class="btn" name="post">
   </form>

</section>





<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>