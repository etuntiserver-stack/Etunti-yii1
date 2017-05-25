<?php

$output = shell_exec("soffice --writer --convert-to pdf /home/estromfi/www/dev/etunti/tiedostot/temp/sivex/temp_raporti_Roman_Sizov.html --outdir  /home/estromfi/www/dev/etunti/tiedostot/temp/sivex/temp_raporti_Roman_Sizov.pdf");

echo $output;
?>

<?php
/*
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
