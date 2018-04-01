<?php
//check if there has been an image posted to the server to be processed
if(isset($_POST['submit']))
{
	$title =$_POST['title'];

	//move the image into the images folder
	if($_FILES)
	{
  		$fname = $_FILES['filename']['name'];
		move_uploaded_file($_FILES['filename']['tmp_name'], "images/".$fname);
	}
}

//check if there has been a video posted to the server to be processed
if(isset($_POST['submitVideo']))
{
	$film =$_POST['titleFilm'];

	//move the video into the videos folder
	if($_FILES)
	{
  		$fname = $_FILES['video']['name'];
		move_uploaded_file($_FILES['video']['tmp_name'], "uploadedVideos/".$fname);
	}
}


//open connection to the database
class MyDB extends SQLite3
   {
      function __construct()
      {
         $this->open('../../db/CoM.db');
      }
   }


//if an image was submitted
if(isset($_POST['submit']))
{
    try
   {
   	$db = new MyDB();
   	//The data from the text box is potentially unsafe; 'tainted'. 
	//We use the sqlite_escape_string. 
	//It escapes a string for use as a query parameter. 
	//This is common practice to avoid malicious sql injection attacks. 
	$title_es = $db->escapeString($title);
	// the file name with correct path
	$imageWithPath= "images/".$fname;
	$queryA ="INSERT INTO videoFrames (name, image) VALUES ('$title_es','$imageWithPath')";
	//error checking
	$ok1 = $db->exec($queryA);
	if (!$ok1) die("Cannot execute statement.");
    }
    catch(Exception $e)
    {
      die($e);
    }
}

if(isset($_POST['submitVideo']))
{
    try
   {
   	$db = new MyDB();
   	//The data from the text box is potentially unsafe; 'tainted'. 
	//We use the sqlite_escape_string. 
	//It escapes a string for use as a query parameter. 
	//This is common practice to avoid malicious sql injection attacks. 
	$film_es = $db->escapeString($film);
	// the file name with correct path
	$videoWithPath= "uploadedVideos/".$fname;
	$queryA ="INSERT INTO videos (name, url) VALUES ('$film_es','$videoWithPath')";
	//error checking
	$ok1 = $db->exec($queryA);
	if (!$ok1) die("Cannot execute statement.");
    }
    catch(Exception $e)
    {
      die($e);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Colour Timelines - Home</title>
<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
<link type='text/css' rel='stylesheet' href='Colours.css'/>

</head>
<body>        
 
<div id="bodie">


<br><br>

<h2>VIDEO LIBRARY</h2>
<p>select a video to paint its colour timeline</p>


<?php

//display all the video links
try
{
   $db = new MyDB();
   $db->busyTimeout(50000);
   $sql_select='SELECT * FROM videos';
	// the result set
	$result = $db->query($sql_select);
	if (!$result) die("Cannot execute query.");

	while($row = $result->fetchArray(SQLITE3_ASSOC))
	{

		echo "<a href = 'watch.php?video=" . $row['url'] .  "'>";
		echo $row['name'] . "<br />";
		echo "</a>";

	}//end while
	$db->close();
	unset($db);
}
 
catch(Exception $e)
{
   die($e);
}

?>


<br />

<h3>add your own</h3>

<form action="Colours.php" method="post" enctype ="multipart/form-data">
	<fieldset>
		<label>Name: </label><input type="text" size="24" maxlength = "40" name = "titleFilm" required><br \><br \>
		<label>Video: </label> <input type ="file" name = 'video' size=10 required /> <br \><br \>
		<div> <input type = "submit" name = "submitVideo" value = "Upload" /></div>
	</fieldset>
</form></center>

</div>


<div id="library">
<br>
<center><h2>UPLOADED RESULTS</h2>

<form action="Colours.php" method="post" enctype ="multipart/form-data">
	<fieldset>
		<label>Name: </label><input type="text" size="24" maxlength = "40" name = "title" required> &nbsp; &nbsp;
		<label>Image: </label> <input type ="file" name = 'filename' size=10 required /> <br /><br />
		<div> <input type = "submit" name = "submit" value = "Upload" /></div>
	</fieldset>
</form></center>

<br><br>

<?php

//display all the uploaded images
try
{
   $db = new MyDB();
   $db->busyTimeout(50000);
   $sql_select='SELECT * FROM videoFrames';
	// the result set
	$result = $db->query($sql_select);
	if (!$result) die("Cannot execute query.");

	while($row = $result->fetchArray(SQLITE3_ASSOC))
	{
        echo "<center>". $row['name'] ."<br>";;
    	$imagePath = $row["image"];
    	echo '<img width="450" src = ' . $imagePath . ' \> </center> <br \>';
	}//end while
	$db->close();
	unset($db);
}
 
catch(Exception $e)
{
   die($e);
}

?>

</div>

</body>
</html>