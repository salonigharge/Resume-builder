<?php
require_once "pdo.php";
require_once "utilities.php";
session_start();
?>
<html>
<head>
	<title>GHARGE SALONI ANNASAHEB</title>
<?php require_once"head.php"; ?>
</head><body>
<div class="container">
<h2>Saloni Gharge's Resume Registry</h2>
<?php
 flashMessages();
if(isset($_SESSION['name'])){
$stmt = $pdo->query("SELECT first_name,headline,profile_id FROM Profile");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row===false)
{
  echo"no row";
}
else{
  echo('<table border="1">'."\n");
    echo "<tr><td>";
    echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']));
    echo("</td><td>");
    echo(htmlentities($row['headline']));
    echo("</td><td>");
    echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
    echo("</td></tr>\n");
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo "<tr><td>";
   echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']));
    echo("</td><td>");
    echo(htmlentities($row['headline']));
    echo("</td><td>");
    echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
    echo("</td></tr>\n");
  }
  echo '</table>';
}
}
if (!isset($_SESSION['name'])){
  echo '<p><a href="login.php">Please log in</a></p>';

  $stmt = $pdo->query("SELECT first_name,headline,profile_id FROM Profile");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
 if($row===false)
 {
  echo "no rows";
 }
 else{
  echo('<table border="1">'."\n");
    echo "<tr><td>";
    echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']));
    echo("</td><td>");
    echo(htmlentities($row['headline']));
    echo("</td><td>");
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo "<tr><td>";
   echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']));
    echo("</td><td>");
    echo(htmlentities($row['headline']));
    echo("</td>");
   }
   echo '</table>';
  echo '<p>Attempt to <a href="add.php">Add data</a> without logging in</p>';
}
}
else{
echo '<p><a href="add.php">Add New Entry</a></p>';
echo '<p><a href="logout.php">Logout</a></p>';
}
?>
</div>
</body>
</html>