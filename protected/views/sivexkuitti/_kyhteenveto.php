<?php
/* @var $this SivexkuittiController */
/* @var $data Sivexkuitti */

		$total_l 	= 0;
		$total_t 	= 0;
		$tp		= 0;
		$al		= '';
		$lop		= '';
		$total_sunniteltu = 0;

       		$criteria = new CDbCriteria();
        	$criteria->select = "
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'))) as l_tunnit,
		t.id,t.aloitan,t.loppui";

        	$criteria->condition = "  kohde_kannasta = '".$data->kohde_kannasta."' and status='3' ";


		if(Yii::app()->session['from'] and Yii::app()->session['to'])
	        $criteria->addCondition ("DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".Yii::app()->session['from']."' AND '".Yii::app()->session['to']."' ");

		$lu = Sivexkuitti::model()->findAll($criteria);
		foreach($lu as $val)
		{

			$tot = Toteutuneet::model()->find(" kid = '".$val->id."' ");

			if(isset($tot['id']))
			{
			  $val->l_tunnit = (strtotime($tot['loppui'])-strtotime($tot['aloitan']));
			  $al = explode(" ",$tot['aloitan']);
			  $lop = explode(" ",$tot['loppui']);
			} else {
			  $al = explode(" ",$val->aloitan);
			  $lop = explode(" ",$val->loppui);
			}

			// Toteutuneet
			$total_l += $val->l_tunnit;

		}
		$total = $total_l;


?>

<tr>

	<td><?php echo CHtml::encode($data->kohde_kannasta); ?></td>

	<?php
	$tas = explode(",",Yii::app()->user->adminPaketti);
	if(in_array('2',$tas)) {
	$total_sunniteltu = $this->renderPartial('//sivexkuitti/suunniteltu',array('id'=>$data->kohdenID,'kohde_tid'=>'kohde'),true);
	echo '<td>'.sprint($total_sunniteltu).'</td>';
	}
	?>

	<td><?php echo sprint($data->l_tunnit); ?></td>
	<td><?php echo sprint($total_l); ?></td>

</tr>

	


