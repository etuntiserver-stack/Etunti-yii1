<?php
/* @var $this KohteetController */
/* @var $data Kohteet */

		$as = Asiakkaat::model()->findbypk($data->asiakas_id);
			
		$nimi = '';
		if(isset($as->yrityksen_nimi) and !empty($as->yrityksen_nimi))
		$nimi = $as->yrityksen_nimi;
		elseif(isset($as->yhteyshenkilo) and !empty($as->yhteyshenkilo))
		$nimi = $as->yhteyshenkilo;


		$criteria=new CDbCriteria;
		$criteria->order = " DATE(time) DESC ";
		$criteria->condition = "
			asiakas_id='".$data->asiakas_id."' 
			AND keskustelu_id!=id
		";
		$p_juttelu = Palautteet::model()->findAll($criteria);
?>

<tr>
	<td>
		<?php echo date("d.m.Y H:i", strtotime($data->time)); ?>
	</td>
	<td>
		<?php echo $nimi; ?>
	</td>
	<td>
		<?php echo $data->otsikko; ?>
	</td>

<?php
	  	$bod = '';
	  	$bod .= '<td>';
		$bod .= '<p>'.$data->teksti.'</p>';
		   foreach($p_juttelu as $data2)
		   {
			$bod .= '<p>'.$data2->teksti.'</p>';
		   }

		if($data->status == 0)
		$bod .= CHtml::link(Yii::t('main', 'Vasta'), Yii::app()->request->baseUrl.'/index.php/palautteet/vastaus?id='.$data->keskustelu_id,array('class'=>'btn btn-primary btn-sm', 'style'=>'color:white'));
	  	$bod .= '</td>';

	  	$bod .= '<td>';
		if($data->status == 0)
	  	$bod .= '<span class="btn btn-sm btn-warning btn-block">'.Yii::t('main', 'avoin').'</span>';
		elseif($data->status == 3)
	  	$bod .= '<span class="btn btn-sm btn-success btn-block">'.Yii::t('main', 'suljettu').'</span>';

		if(isset(Yii::app()->user->adminID) and $data->status != 3)
		{
		$bod .= CHtml::link('Sulje', '#', array(
		'submit'=>array('/asiakkaat/update', "suljeJuttelu"=>$data->keskustelu_id, "id"=>$data->asiakas_id), 
		'class'=>'btn btn-danger btn-sm btn-block', 'style'=>'color:white'
		));
		}

	  	$bod .= '</td>';
		echo $bod;
?>

	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>


