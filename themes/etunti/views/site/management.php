<?php

				$mobile = 0;
				$start_date = date ("Y-m-d"));
				$end_date = date ("Y-m-d", strtotime($start_date. " -12 month"));
	
	
				// <-- Ensin katsotaan mobile taulusta toteutuneet
		       		$criteria = new CDbCriteria();
		        	$criteria->select = "
					SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
					DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
				";
			        $criteria->condition = " 
					aloitan!='' AND loppui!=''
					AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN  '$start_date' AND '$end_date'
					AND status=3
					AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
				";
				$lu = Mobile::model()->find($criteria);
	
	
		       		$criteria = new CDbCriteria();
		        	$criteria->select = "
					SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
					DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
				";
			        $criteria->condition = " 
					aloitan!='' AND loppui!=''
					AND status=3
					AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN  '$start_date' AND '$end_date'
				";
				$tot = Toteutuneet::model()->find($criteria);
		
				if(isset($lu->l_tunnit))
				$mobile += $lu->l_tunnit;
		
				if(isset($tot->l_tunnit))
				$mobile += $tot->l_tunnit;

?>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
<div id="container"></div>
<script>
Highcharts.chart('container', {

    title: {
        text: 'Solar Employment Growth by Sector, 2010-2016'
    },

    subtitle: {
        text: 'Source: thesolarfoundation.com'
    },

    yAxis: {
        title: {
            text: 'Number of Employees'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
            pointStart: 2010
        }
    },

    series: [{
        name: 'Installation',
        data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});
</script>
