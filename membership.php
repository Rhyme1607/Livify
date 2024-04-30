<?php

include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
   header('Location: login.php'); // Redirect to login page
   exit();
}else{
  $user_id = $_SESSION['user_id'];
}

$select_membership = $conn->prepare("SELECT MembershipType FROM `membership` WHERE Trader_UserID = ? LIMIT 1");
$select_membership->execute([$user_id]);
$fetch_membership = $select_membership->fetch(PDO::FETCH_ASSOC);
$is_premium = $fetch_membership['MembershipType'] === 'Premium';

if (isset($_POST['activate_membership'])) {
    $duration = $_POST['duration']; // Get the duration from the form
    $end_date = date('Y-m-d', strtotime("+$duration months")); // Calculate the end date

    $update_membership = $conn->prepare("UPDATE `membership`, `trader` SET membership.MembershipType = 'Premium', membership.StartDate = NOW(), membership.EndDate = ?, trader.MembershipType = 'Premium' WHERE membership.Trader_UserID = ? AND trader.UserID = ?");
    $update_membership->execute([$end_date, $user_id, $user_id]);
    $is_premium = true;
}

if (isset($_POST['cancel_membership'])) {
    $update_membership = $conn->prepare("UPDATE `membership`, `trader` SET membership.MembershipType = 'Regular', membership.StartDate = NOW(), membership.EndDate = NULL, trader.MembershipType = 'Regular' WHERE membership.Trader_UserID = ? AND trader.UserID = ?");
    $update_membership->execute([$user_id, $user_id]);
    $is_premium = false;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Membership</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<h1 class="membership-center-text"><?php echo $is_premium ? 'You are a premium member!' : 'Become a premium member'; ?></h1>

<div class="membership-form-container">
    <?php if ($is_premium): ?>
        <div class="membership-box">
            <form method="post">
                <button type="submit" name="cancel_membership">Cancel membership</button>
            </form>
        </div>
    <?php else: ?>
        <div class="membership-box">
            <h2>Premium Membership Plan - 3 months</h2>
            <form method="post">
                <input type="hidden" name="duration" value="3">
                <button type="submit" name="activate_membership">Activate membership</button>
            </form>
        </div>
        <div class="membership-box">
            <h2>Premium Membership Plan - 6 months</h2>
            <form method="post">
                <input type="hidden" name="duration" value="6">
                <button type="submit" name="activate_membership">Activate membership</button>
            </form>
        </div>
        <div class="membership-box">
            <h2>Premium Membership Plan - 12 months</h2>
            <form method="post">
                <input type="hidden" name="duration" value="12">
                <button type="submit" name="activate_membership">Activate membership</button>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>
</html>