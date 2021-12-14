<style>
td{
	vertical-align: top !important;
}
</style>
<?php
if(isset($_GET['getAsiakas']))
{
	echo '<h1>Tämä on: getcustomer.nv?id='.$_GET['getAsiakas'].' &nbsp;&nbsp;<a href="netvisor_customerlist">Takaisiin listaan</a></h1>';

	echo '<pre>';
	foreach($data as $arr)
	{
		foreach($arr as $key => $value)
		{
			foreach($value as $nimike => $arvo)
			{
				if(!is_array($arvo))
				{
					echo $nimike.': <b>'.$arvo.'</b><br>';

				} else {
					echo '<h3>'.$nimike.'</h3>';
					foreach($arvo as $k => $v)
					{
						echo '<p>'.$v.'<p>';
					}
				}
			}
		}
	}
	echo '</pre>';
				
} else {

	$criteria=new CDbCriteria;
	$criteria->select = "id,netvisorkey,yrityksen_nimi,y_tunnus,etunimi,sukunimi";
	$criteria->condition = " 
		netvisorkey>0
	";
	$asiakkaat 	= Asiakkaat::model()->findAll($criteria);
	$a_all		= [];
	foreach($asiakkaat as $item)
		$a_all[$item->netvisorkey] = $item;

	echo '<h1>Tämä on: customerlist.nv</h1>';
	echo '<table class="table table-bordered">';
	echo '<tr><th style="width:50%"><h1>Netvisor tiedot</h1></th><th style="width:50%"><h1>Etunti tiedot</h1></th></tr>';
	foreach($data as $arr)
	{
		foreach($arr as $key => $value)
		{
			echo '<tr>';
			echo '<td style="width:50%">';
			foreach($value as $nimike => $arvo)
			{
				if(!is_array($arvo))
				{
					if($nimike == 'Uri')
					{
						$expl = explode("=", $arvo);
						if(isset($expl[1]))
							echo '<h3><a href="netvisor_customerlist?getAsiakas='.$expl[1].'">Lisää tietoja Netvisorista</a><h3>';

					} else {
						echo $nimike.': <b>'.$arvo.'</b><br>';
					}

				} else {
					echo '<h3>'.$nimike.'</h3>';
					foreach($arvo as $k => $v)
					{
						echo '<p>'.$v.'<p>';
					}
				}
			}
			echo '</td>';
			echo '<td style="width:50%">';
				if(isset($value['Netvisorkey']) and isset($a_all[$value['Netvisorkey']]))
				{
					foreach($a_all[$value['Netvisorkey']] as $ka => $va)
					{
						if(!empty($va))
							echo $ka.': <b>'.$va.'</b><br>';
					}
				} else {
					echo '<h2 class="text-danger">Ei löydy</h2>';
				}
			echo '</td>';
			echo '</tr>';
		}
	}
	echo '</table>';
}
?>
