<?php
require_once "pdo.php";
require_once "utilities.php";
session_start();
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}
if (isset($_POST['cancel']))
{
    header('location:index.php');
    return;
}
if ( ! isset($_REQUEST['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}  

if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) ){  
  
$msg=validateProfile();
  if(is_string($msg)){
    $_SESSION['error']=$msg;
    header("location:edit.php?profile_id=" . $_REQUEST["profile_id"]);
    return;
  } 
 
  $msg=validatePos();
  if(is_string($msg)){
    $_SESSION['error']=$msg;
    header("location:edit.php?profile_id=" . $_REQUEST["profile_id"]);
    return;
  }
      $sql="UPDATE Profile SET first_name= :mk,last_name= :yr,email= :mi,headline= :mo,summary= :ms WHERE profile_id= :profile_id AND user_id=:uid";
       $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
  ':mk' => $_POST['first_name'],
  ':yr' => $_POST['last_name'],
  ':mi' => $_POST['email'],
   ':mo'=> $_POST['headline'],
   ':ms'=> $_POST['summary'],
   ':profile_id'=> $_REQUEST['profile_id'],
   ':uid'=>$_SESSION['user_id'])
   ); 

  $stmt=$pdo->prepare('DELETE FROM position WHERE profile_id=:pid');
  $stmt->execute(array(':pid'=>$_REQUEST['profile_id']));

  $rank=1;
  for($i=1;$i<=9;$i++){
  if(!isset($_POST['year'.$i])) continue;
  if(!isset($_POST['desc'.$i])) continue;
  $year=$_POST['year'.$i];
  $desc=$_POST['desc'.$i];

  $stmt=$pdo->prepare('INSERT INTO Position (profile_id,rank,year,description) VALUES (:pid, :rank, :year, :des)');
  $stmt->execute(array(
     ':pid'=>$_REQUEST['profile_id'],
     ':rank'=>$rank,
     ':year'=>$year,
     ':des'=>$desc));
  $rank++;
}
  $_SESSION['success']="Record updated";
  header("location:index.php");
  return;   
}
$positions=loadPos($pdo,$_REQUEST['profile_id']);

$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_REQUEST['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}
$m = htmlentities($row['first_name']);
$o = htmlentities($row['last_name']);
$i = htmlentities($row['email']);
$y = htmlentities($row['headline']);
$z = htmlentities($row['summary']);
$profile_id = $row['profile_id'];


?>
<!DOCTYPE html>
<html>
<head>
    <title>GHARGE SALONI ANNASAHEB</title>
    <?php require_once "head.php"; ?>
</head>
<body>
<p>Editing profile for <?=htmlentities($_SESSION['name']); ?></p>
<?php flashMessages(); ?>
<form method="post" action="edit.php">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?= $m ?>"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value="<?= $o ?>"/></p>
<p>Email:
<input type="text" name="email" size="30" value="<?= $i ?>"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80" value="<?= $y ?>"/></p>
<p>Summary:<br/>
<input type="textarea" name="summary" rows="8" cols="80" value="<?= $z ?>"></textarea>

<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
  <?php 

        $pos = 0;
        echo('<p>Position: <input type="submit" id="addPos" value="+">'."\n");
        echo('<div id="position_fields">'."\n");
        foreach( $positions as $position ) {
            $pos++;
            echo('<div id="position'.$pos.'">'."\n");
            echo('<p>Year: <input type="text" name="year'.$pos.'"');
            echo('value="'.$position['year'].'"/>'."\n");
            echo('<input type="button" value="-" ');
            echo('onclick="$(\'#position'.$pos.'\').remove();return confirm(\'Are you sure you want to delete this Position?\');return;">'."\n");
            echo("</p>\n");
            echo('<textarea name="desc'.$pos.'" rows="8" cols="80">'."\n");
            echo(htmlentities($position['description'])."\n");
            echo("\n</textarea>\n</div>\n");
        }
        echo("</div></p>\n");
        ?>
<p><input type="submit" name="Save" value="Save"/>
<input type="submit" name="cancel" value="Cancel"></p>
</form>
<script>
countPos= <?= $pos ?>;

// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
$(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });
});
</script>
</body>
</html>