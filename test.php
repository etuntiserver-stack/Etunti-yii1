<?php
/*
$servername = "localhost";
$username = "root";
$password = "111111";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

mysqli_select_db($conn,"kotipuhtaaksi");


$sql = mysqli_query($conn,"SELECT * FROM asiakkaat "); //WHERE hinta_sis_alv=0 AND alv!=0
 

while($row=mysqli_fetch_array($sql))
{
	echo $row['id'].' ALV: '.$row['alv'].', Hinta:'.$row['hinta'].', Hinta alv sis:'.$row['hinta_sis_alv'].'<br>';

	$hinta = 0;
	$hinta_sis_alv = 0;

	$hinta = $row['hinta']/(1+($row['alv']/100));
	$hinta_sis_alv = $row['hinta'];

	echo '<h3>'.$hinta.'</h3><br>';

//mysqli_query($conn,"UPDATE asiakkaat SET hinta_sis_alv='".$hinta_sis_alv."', hinta='".$hinta."' WHERE id='".$row['id']."' ");

}
*/

?>
