<?php
require_once "pdo.php";
session_start();
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}
if (isset($_POST['cancel']))
{
    header('location:index.php');
    return;
}
if ( isset($_POST['Delete']) && isset($_POST['profile_id'])) {
    $sql = "DELETE FROM Profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT first_name,last_name,profile_id FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>GHARGE SALONI ANNASAHEB</title>
</head>
<body>
  <h2>Deleting Profile</h2>
<p>First name: <?= htmlentities($row['first_name']) ?></p>
<p>last name: <?= htmlentities($row['last_name']) ?></p>

<form method="post">
<input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
<input type="submit" value="Delete" name="Delete">
<input type="submit" name="cancel" value="Cancel">
</form>
</body>
</html>