<?php
/* @var $this MobileController */
/* @var $data Mobile */

		$total_l 	= 0;
		$total_t 	= 0;
		$total_sunniteltu = 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as l_tunnit
		";

		if(Yii::app()->session['mitkatKohteet'] == 'kohdenID')
		{
        	$criteria->condition = " 
			loppui!='' and aloitan!='' 
			AND status='3'
			AND kohdenID ='".$data->kohdenID."' 
		";
		}

		if(Yii::app()->session['mitkatKohteet'] == 'kohde_kannasta')
		{
        	$criteria->condition = " 
			loppui!='' and aloitan!='' 
			AND status='3'
			AND kohdenID =''
			AND kohde_kannasta='".$data->kohde_kannasta."' 
		";
		}


		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition (" 

			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' 

		");


		$criteria->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
		$lu = Mobile::model()->find($criteria);


       		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) as t_tunnit
		";

		if(Yii::app()->session['mitkatKohteet'] == 'kohdenID')
		{
        	$criteria->condition = " 
			loppui!='' and aloitan!='' 
			AND status='3'
			AND kohdenID ='".$data->kohdenID."' 
		";
		}

		if(Yii::app()->session['mitkatKohteet'] == 'kohde_kannasta')
		{
        	$criteria->condition = " 
			loppui!='' and aloitan!='' 
			AND status='3'
			AND kohdenID =''
			AND kohde_kannasta='".$data->kohde_kannasta."' 
		";
		}


		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition (" 

			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' 

		");

		$tot = Toteutuneet::model()->find($criteria);


?>

<tr>

	<td><?php echo CHtml::encode($data->kohde_kannasta); ?></td>

	<?php
	$tas = explode(",",Yii::app()->user->adminPaketti);
	if(in_array('2',$tas)) {
	$total_sunniteltu = $this->renderPartial('//mobile/suunniteltu',array('id'=>$data->kohdenID,'kohde_tid'=>'kohde'),true);
	echo '<td>'.sprint($total_sunniteltu).'</td>';
	}
	?>

	<td><?php echo sprint($data->l_tunnit); ?></td>
	<td><?php echo sprint($lu->l_tunnit+$tot->t_tunnit); ?></td>
	<td><?php echo $data->kpl; ?></td>

</tr>

	


