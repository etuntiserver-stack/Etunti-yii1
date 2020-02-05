<?php

	$k=Kohteet::model()->findbypk($data->kohde);
	if(isset($k->id)) $osoite = $k->osoite; else $osoite = '';

	$tt=Tyontekijat::model()->findbypk($data->tid);
	if(isset($tt->id)) $tekijan_nimi = $this->etuSukunimi($tt->id); else $tekijan_nimi = '';


  	$path = Yii::app()->basePath.'/../img/tekijat/'.Yii::app()->user->domain.'/'. $data->tid.'.jpg';
  	if (file_exists($path) and isset($_POST['tulosta'])){
		$img =  '<img src="img/tekijat/'.Yii::app()->user->domain.'/'. $data->tid.'.jpg" height="30">';
  	} elseif (file_exists($path) and !isset($_POST['tulosta'])){
		$img =  '<img src="'.Yii::app()->request->baseUrl.'/img/tekijat/'.Yii::app()->user->domain.'/'. $data->tid.'.jpg" height="60">
			<p>'.$tekijan_nimi.'</p>';
	} else {
		$img =  $tekijan_nimi;
	}

	$kesto = strtotime($data->loppu)-strtotime($data->alku);

	echo '<tr class="'.(($data->peruutettu != 0)? 'text-danger':'').'">';
	echo '<td>
	'.CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('/tyovuoroot/beta?year='.date("Y", strtotime($data->pvm)).'&week='.date("W", strtotime($data->pvm)).'&tv_id='.$this_id), 
				array(
					'class'=>'btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Muokkaa'),
					'target' => '_blank'
				)
			).
	'</td>';
	echo '<td class="col1">'.$data->pvm.'</td>';
	echo '<td class="col2">'.$data->alku.'-'.$data->loppu.'</td>';
	echo '<td class="col1">'.$this->sprint($kesto).'</td>';
	echo '<td class="col3">'.$osoite.'</td>';
	echo '<td class="col4">'.$img.'</td>';
	echo '<td class="col1">'.$data->tietoja.'</td>';
	echo '</tr>';

?>
