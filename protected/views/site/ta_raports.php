<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

/*
if(Yii::app()->user->name == 'admin')
{  
$this->widget('zii.widgets.CMenu', array(
    'items'=>array(
        array('label'=>'Muokka (admin)', 'url'=>array('page/update&id=1')),
    ),
));
}

*/

	$criteria = new CDbCriteria;
	$criteria->select = 'kuka';
	$criteria->group = 'kuka';
	$tar = Tarjoukset::model()->findAll($criteria);

if(count($tar) > 0){

foreach($tar as $val){

  if($val->kuka){
	$tarByUser = Tarjoukset::model()->findAll("kuka = :n", array(":n"=>$val->kuka));
	$count = count($tarByUser);

	$criteria = new CDbCriteria;
	$criteria->select = 'id';
	$criteria->addCondition('username = "'.$val->kuka.'"');
	$result = User::model()->find($criteria);

	if(isset($result->id)){
	$pr = Profile::model()->findbyPk($result->id);
	$color = $pr->color;
	$label = $pr->firstname;
	} else {
	$color = '#ccc';
	$label = 'poistettu';
	}

	$kuka[] = array("value"=>$count,"color" => $color,"label"=>$label);
  }
}



for ($i = 1; $i <= 12; $i++) {

    if(Yii::app()->request->getParam('luoja')){
	$us = User::model()->findbyPk(Yii::app()->request->getParam('luoja'));

	$criteria = new CDbCriteria;
	$criteria->addCondition('kuka = "'.$us->username.'"');
	$criteria->addCondition('MONTH(time) = '.$i);
	$result = Tarjoukset::model()->findAll($criteria);

    } else {
	$criteria = new CDbCriteria;
	$criteria->addCondition('MONTH(time) = '.$i);
	$result = Tarjoukset::model()->findAll($criteria);
    }

    $res = $result;
    $kk[$i] = count ( $res );
}


?>

<h1>TARJOUS RAPORTIT</h1>

<div class="row">
  <div class="col-sm-6">
  <h2>Tarjoukset kuukausittain</h2>

  <div class="col-sm-6">
  <?php
    if(Yii::app()->request->getParam('luoja')){
	$us = User::model()->findbyPk(Yii::app()->request->getParam('luoja'));
 	$empty = $us->username;
    } else {
	$empty = 'Kaikki';
    }
  ?>
  <?php echo CHtml::dropDownList('t','y',CHtml::listData(User::model()->findAll(), 'id', 'username'), array('empty'=>$empty,'class'=>'form-control form-group luojaValikko')); ?>
  <BR>
  </div>

  <div class="col-sm-6">
  <?php if(Yii::app()->request->getParam('luoja')) echo CHtml::Button('Keskeytä', array('class' => 'btn btn-success btn-group keskeyta')); ?>
  </div>


  <?php 
        $this->widget(
            'chartjs.widgets.ChBars', 
            array(
                'width' => 600,
                'height' => 300,
                'htmlOptions' => array(),
                'labels' => array("Tammikuu","Helmikuu","Maaliskuu","Huhtikuu","Toukokuu","Kesäkuu","Heinäkuu","Elokuu","Syyskuu","Lokakuu","Marraskuu", "Joulukuu"),
                'datasets' => array(
                    array(
                        "fillColor" => "rgba(100,100,220,1)",
                        "strokeColor" => "rgba(220,220,220,1)",
                        "data" => array($kk[1],$kk[2],$kk[3],$kk[4],$kk[5],$kk[6],$kk[7],$kk[8],$kk[9],$kk[10],$kk[11],$kk[12])
                    )       
                ),
                'options' => array()
            )
        ); 
    ?>
  </div>

  <div class="col-sm-6">
  <h2>Kaikki tarjouksen luojat</h2>
  <?php 
            $this->widget(
                'chartjs.widgets.ChPie', 
                array(
                    'width' => 600,
                    'height' => 300,
                    'htmlOptions' => array(),
                    'drawLabels' => true,
                    'datasets' => $kuka,
                    'options' => array()
                )
            ); 
        ?>
  </div>
</div>



<script type="text/javascript">
$(document).ready(function(){

$(".keskeyta").click(function(){
	var link = "index.php?r=site/ta_raports";
   	window.location.href=link;
});

$(".luojaValikko").change(function(){
	var link = "index.php?r=site/ta_raports&luoja="+$(this).val();
   	window.location.href=link;
});

});
</script>

<?php 
} else {
	echo '<h1>RAPORTIT</h1><BR><h1>Ei yhtään tarjousta tällä hetkellä</h1>';
}
?>
