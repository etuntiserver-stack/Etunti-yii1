<?php
/* @var $this MobileController */
/* @var $data Mobile */

		$total_l 	= 0;
		$total_t 	= 0;
		$totalTp	= 0;
		$totalIlta 	= 0;
		$totalYo 	= 0;
		$totalSu	= 0;
		$tp		= 0;
		$al		= '';
		$lop		= '';
		$total_sunniteltu = 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'))) as l_tunnit,aloitan,loppui
		";

        	$criteria->condition = "  
			tid = '".$data->tid."' and aloitan !='' and loppui !='' 
			AND id NOT IN(select kid from sivexkuitti_repaired)
		";

		if(Yii::app()->session['Lounastauko'])
	        $criteria->addCondition (" status != '10' ");

		if(Yii::app()->session['MATKA'])
	        $criteria->addCondition (" status != '2' ");

		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

		$lu = Mobile::model()->findAll($criteria);
		foreach($lu as $l)
		{
		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    $totalIlta += $this->ilta($al,$lop);
		    $totalYo += $this->yo($al,$lop);
		    $total_l += $l->l_tunnit;
		    if(date('N', strtotime($al[0])) == 7)
		    $totalSu += (strtotime($lop[0]." ".$lop[1])-strtotime($al[0]." ".$al[1]));
		}
		/* ////////////////////////// */

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'))) as l_tunnit,aloitan,loppui 
		";

        	$criteria->condition = "  
			tid = '".$data->tid."' and aloitan !='' and loppui !='' 
		";

		if(Yii::app()->session['Lounastauko'])
	        $criteria->addCondition (" status != '10' ");

		if(Yii::app()->session['MATKA'])
	        $criteria->addCondition (" status != '2' ");

		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

		$tot = Toteutuneet::model()->findAll($criteria);
		foreach($tot as $l)
		{
		    $l->l_tunnit = (strtotime($l->loppui)-strtotime($l->aloitan));
		    $al = explode(" ",$l->aloitan);
		    $lop = explode(" ",$l->loppui);
		    $totalIlta += $this->ilta($al,$lop);
		    $totalYo += $this->yo($al,$lop);
		    $total_l += $l->l_tunnit;
		    if(date('N', strtotime($al[0])) == 7)
		    $totalSu += (strtotime($lop[0]." ".$lop[1])-strtotime($al[0]." ".$al[1]));
		}

		$total = $total_l;

?>

<tr>

	<td><?php echo CHtml::encode($data->tekijan_nimi); ?></td>

	<?php
	$tas = explode(",",Yii::app()->user->adminPaketti);
	if(in_array('2',$tas)) {
	$total_sunniteltu = $this->renderPartial('//mobile/suunniteltu',array('id'=>$data->tid,'kohde_tid'=>'tid'),true);
	echo '<td>'.$this->sprint($total_sunniteltu).'</td>';
	}
	?>

	<td><?php echo $this->sprint($data->l_tunnit); ?></td>
	<td><?php echo $this->sprint($total); ?></td>
	<td><?php echo $this->Tp($data->tid); ?></td>
	<td><?php echo $this->sprint($totalIlta); ?></td>
	<td><?php echo $this->sprint($totalYo); ?></td>
	<td><?php echo $this->sprint($totalSu); ?></td>
</tr>

	


