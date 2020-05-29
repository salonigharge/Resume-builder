<?php
require_once "pdo.php";
require_once "utilities.php";
session_start();
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>GHARGE SALONI ANNASAHEB</title>
<?php require_once "head.php"; ?>
</head>
<body>
<div class="container">
<h1>Profile information</h1>
<?php
if(isset($_GET['profile_id'])){
$sql="SELECT * FROM Profile WHERE profile_id= :xyz";
$stmt=$pdo->prepare($sql);
$stmt->execute(array(":xyz" => $_GET['profile_id']));
 $row= $stmt->fetch(PDO::FETCH_ASSOC);
    echo"First name: ".htmlentities($row['first_name']);
    echo"<br>";
    echo"Last name: ".htmlentities($row['last_name']);
    echo"<br>";
    echo"Email: ".htmlentities($row['email']);
    echo"<br>";
    echo"Headline: ".htmlentities($row['headline']);
    echo"<br>";
    echo"Summary: ".htmlentities($row['summary']);
    echo"<br>";
$sql="SELECT * FROM Position WHERE profile_id= :xyz";
$stmt=$pdo->prepare($sql);
$stmt->execute(array(":xyz" => $_GET['profile_id']));
echo "Position:";
echo"<br>";
echo "<ul>";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  echo "<li>";
  echo htmlentities($row['year']).":".htmlentities($row['description']);
  echo "</li>";
}
}
?>
<p>
<a href="index.php">Done</a>
</p>
</div>
</body>
</html>