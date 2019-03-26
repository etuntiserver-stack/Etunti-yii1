<?php
$months=array(
	1=>Yii::t('main', 'Tammikuu'),
	2=>Yii::t('main', 'Helmikuu'),
	3=>Yii::t('main', 'Maaliskuu'),
	4=>Yii::t('main', 'Huhtikuu'),
	5=>Yii::t('main', 'Toukokuu'),
	6=>Yii::t('main', 'Kesäkuu'),
	7=>Yii::t('main', 'Heinäkuu'),
	8=>Yii::t('main', 'Elokuu'),
	9=>Yii::t('main', 'Syyskuu'),
	10=>Yii::t('main', 'Lokakuu'),
	11=>Yii::t('main', 'Marraskuu'),
	12=>Yii::t('main', 'Joulukuu')
	);

	$result = array();
	$start_date = date ("Y-m-d", strtotime(" -1 year"));
	$end_date = date ("Y-m-d");

	$criteria = new CDbCriteria();
       	$criteria->group = " MONTH(DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$start_date."' AND '".$end_date."'
		AND status=3
		AND sairaus!=1
		AND deleted=0
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
	";
	$lu = Mobile::model()->findAll($criteria);

	$criteria = new CDbCriteria();
       	$criteria->group = " MONTH(DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND status=3
		AND sairaus!=1
		AND deleted=0
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$start_date."' AND '".$end_date."'
	";
	$tot = Toteutuneet::model()->findAll($criteria);
	$result = array_merge($lu, $tot);

	$data = array();
	$categories = array();
	foreach($result as $item){
		$data[] = round($item->l_tunnit/3600, 2);
		$categories[] = $months[date("n", strtotime($item->aloitan))];
	}

//print_r($result);
?>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
<div id="container"></div>
<script>
Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Monthly Average Temperature'
    },
    subtitle: {
        text: 'Source: WorldClimate.com'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
        title: {
            text: 'Temperature (°C)'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Tokyo',
        data: JSON.parse('<?=json_encode(array_values($data))?>')
    }]
});
</script>
