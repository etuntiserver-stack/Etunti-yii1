<div class="row small">
<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Luetut kohteet'),
);
/*
$this->menu=array(
	//array('label'=>'Create Mobile', 'url'=>array('create')),
	array('label'=>'Luetut Hallinta', 'url'=>array('admin')),
);
*/

	$this->widget('ext.tooltipster.tooltipster');
?>


<legend>
  <div class="pull-right form-inline small">
  <span><?php echo Yii::t('main', 'Ohje: '); ?></span>
	<b class="btn btn-warning btn-sm fa fa-map tooltipster" title="Aloitus GPS sijainti kartalla"></b>
	<b class="btn btn-warning btn-sm fa fa-map-o tooltipster" title="Lopetus GPS sijainti kartalla"></b>
	<b class="btn btn-warning btn-sm fa fa-envelope tooltipster" title="Viestin lähettäminen"></b>
	<b class="btn btn-warning btn-sm fa fa-table tooltipster" title="Siirry tämän päivän työvuoroihin"></b>
	<b class="btn btn-warning btn-sm fa fa-list-alt tooltipster" title="Näytä työvuorot"></b>
	<b class="btn btn-warning btn-sm fa fa-tags tooltipster" title="TAG numero"></b>
	<b class="btn btn-warning btn-sm fa fa-pencil-square-o tooltipster" title="Muokkaa"></b>
	<b class="btn btn-warning btn-sm fa fa-sign-in tooltipster" title="Alkuperäinen tieto"></b>
	<b class="btn btn-warning btn-sm fa fa-check-square-o tooltipster" title="Rivi on muokattu"></b>
  	<span class="btn btn-default klo"></span>
  </div>
  <h1> 
	<?php echo Yii::t('main', 'TUNNIT'); ?> <i class="glyphicon glyphicon-phone"></i> 
  </h1>
</legend>

<br>

<div class="row">

<?php
$months=array(
	1=>'Tammikuu',
	2=>'Helmikuu',
	3=>'Maaliskuu',
	4=>'Huhtikuu',
	5=>'Toukokuu',
	6=>'Kesäkuu',
	7=>'Heinäkuu',
	8=>'Elokuu',
	9=>'Syyskuu',
	10=>'Lokakuu',
	11=>'Marraskuu',
	12=>'Joulukuu',
	);

$date = date("l");
$en = array(
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
    'Sunday',
);
$fi = array(
    'Maanantai',
    'Tiistai',
    'Keskkiviikko',
    'Torstai',
    'Perjantai',
    'Lauantai',
    'Sunnuntai',
);


  $viikonpaiva = str_replace($en, $fi, $date);
?>
		<div class="col-md-2">
			<div class="panel panel-default date-panel">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo $viikonpaiva; ?></h3>
				</div>
				<div class="panel-body">

					<div class="date"><?php echo date("d"); ?></div>
				</div>	
				<div class="month panel-footer">
				  <?php echo $months[date("m")]; ?>, <span class="year"><?php echo date("Y"); ?></span>
				</div>
			</div>
		</div>

  <div class="col-sm-3">
<?php 
	$crsun = new CDbCriteria();
	$crsun->select = "  COUNT(*) as count ";
	$crsun->condition = " 
		DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE()
		AND kohde!=''
		AND tyoajanmerkinta NOT LIKE '%Ei lasketa%'
	";
	$s = Tyovuoroot::model()->find($crsun);


	$a = Mobile::model()->findAll("status=1",array('select'=>'id'));	
	$t = Mobile::model()->findAll(" DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() and status=3",array('select'=>'id'));	


        $this->widget(
            'chartjs.widgets.ChDoughnut', 
            array(
                'width' => 170,
                'height' => 175,
                'htmlOptions' => array(),
                'drawLabels' => true,
                'datasets' => array(
                    array(
                        "value" => (int)$s->count,
                        "color" => "#cecece",
                        "label" => Yii::t('main','Suunnitellut')
                    ),
                    array(
                        "value" => (int)count($a),
                        "color" => "#ebebcb",
                        "label" => Yii::t('main','Aloitetut')
                    ),
                    array(
                        "value" => (int)count($t),
                        "color" => "#8cc152",
                        "label" => Yii::t('main','Tehdyt')
                    )
                ),
                'options' => array()
            )
        ); 
    ?>

  </div>

<?php 
/*
<div class="col-sm-2">
	$crsop = new CDbCriteria();
	$crsop->select = "  COUNT(*) as count ";
	$crsop->condition = " 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() 
		and status=3 and kohdenID!='' 
	";
	$sop = Mobile::model()->find($crsop);	

	$crtunt = new CDbCriteria();
	$crtunt->select = " COUNT(*) as count ";
	$crtunt->condition = " 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() 
		and status=3 and kohdenID='' 
	";
	$tunt = Mobile::model()->find($crtunt);



            $this->widget(
                'chartjs.widgets.ChDoughnut', 
                array(
                    'width' => 130,
                    'height' => 175,
                    'htmlOptions' => array(),
                    'drawLabels' => true,
                    'datasets' => array(
                        array(
                            "value" => (int)$sop->count,
                            "color" => "#8cc152",
                            "label" => Yii::t('main','Sopimusasiakas')
                        ),
                        array(
                            "value" => (int)$tunt->count,
                            "color" => "#cecece",
                            "label" => Yii::t('main','Tuntematon')
                        )
                    ),
                    'options' => array()
                )
            ); 

</div>
*/
    ?>


  <div class="col-sm-3">

<?php 

	$a = Mobile::model()->findAll(" DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() and status=3",array('select'=>'id'));	
	$m = Mobile::model()->findAll(" DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() and status=2",array('select'=>'id'));
	$l = Mobile::model()->findAll(" DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() and status=10",array('select'=>'id'));


        $this->widget(
            'chartjs.widgets.ChDoughnut', 
            array(
                'width' => 170,
                'height' => 175,
                'htmlOptions' => array(),
                    'drawLabels' => true,
                    'datasets' => array(
                        array(
                            "value" => (int)count($a),
                            "color" => "#8cc152",
                            "label" => Yii::t('main','Työt')
                        ),
                        array(
                            "value" => (int)count($m),
                            "color" => "#cecece",
                            "label" => Yii::t('main','Matkat')
                        ),
                    	array(
                            "value" => (int)count($l),
                            "color" => "#ebebcb",
                            "label" => Yii::t('main','Lounaat')
                    	),
                    ),
                'options' => array()
            )
        ); 
    ?>
    <?php
	$criteria = new CDbCriteria;
	$criteria->group="tid";	
	$criteria->condition=" 
		kohde!='' 
		AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() 
		AND tyoajanmerkinta NOT LIKE '%Ei lasketa%'
	";
	$t = Tyovuoroot::model()->findAll($criteria);

    ?>
  </div><div class="col-sm-4">
   <div style="height:210px; overflow: auto; overflow-x: hidden;padding:0 10px;">
   <legend><?php echo Yii::t('main', 'Yli 10 tunti rivit'); ?></legend>
   <?php
	$criteria = new CDbCriteria;
	$criteria->order = " id DESC "; 
	$criteria->condition=" 
		TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) > 36000
		OR 
		(
		TIME_TO_SEC(TIMEDIFF(NOW(), DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) > 36000
		AND loppui=''
		)
	";
	$yli10 = Mobile::model()->findAll($criteria);
	foreach($yli10 as $data)
	{

  $kesto =  '';

if(!empty($data->loppui) and !empty($data->aloitan)){
  $data->loppui = date("d.m.Y H:i",strtotime($data->loppui));
  $data->aloitan = date("d.m.Y H:i",strtotime($data->aloitan));
  $kesto =  strtotime($data->loppui) - strtotime($data->aloitan);
} 
	  echo 
	  '
	   <div class="row">
	    <div class="col-sm-12">
		<div class="col-sm-3">
	    	  <b>ID: '.$data->id.'</b> 
		</div><div class="col-sm-2">
			('.date("d.m",strtotime($data->aloitan)).')
		</div><div class="col-sm-5">
			'.$data->tekijan_nimi.'
		</div><div class="col-sm-2 text-danger">
			'.$this->sprint($kesto).'
		</div>
	    </div>
	   </div>
	  ';
	}
   ?>
   </div>
  </div>
</div>

<br>

 <div class="row" id="haku">
   <div class="col-sm-12">

   <div class="pull-right">
     <?php echo CHtml::link(' +','/index.php/tyontekijat/create',array('target'=>'_blank','class'=>'btn btn-sm btn-default glyphicon glyphicon-user')); ?>
     <?php echo CHtml::link(' +','/index.php/asiakkaat/create',array('target'=>'_blank','class'=>'btn btn-sm btn-default glyphicon glyphicon-home')); ?>
   </div>

   <form id="mobForm" action="#" class="form-inline" method="POST">
   <input type="hidden" name="mob_hae">
   <?php
    $criteria = new CDbCriteria();
    $criteria->order = " tekijan_nimi ASC ";
    $criteria->condition = " aktiivinen=1 ";
    $model = Tyontekijat::model()->findAll($criteria);
    $list = CHtml::listData($model, 'id', 'tekijan_nimi');

    echo '<select class="form-control input-sm form-group" name="tekijaPaaSivulla">';
    if(Yii::app()->session['tekijaPaaSivulla'])
    {
       $tekija = Tyontekijat::model()->findbypk(Yii::app()->session['tekijaPaaSivulla']);
       if(isset($tekija->tekijan_nimi))
       echo '<option value="'.$tekija->id.'">'.$tekija->tekijan_nimi.'</option>';
    } else {
       echo '<option value="kaikki">'.Yii::t('main', 'Työntekijät').'</option>';
    }

       echo '<option value="kaikki">Kaikki</option>';

    foreach($list as $key=>$val){
    echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>

   <input type="text" class="form-control input-sm form-group" name="etsi_kohteet" value="<?php echo Yii::app()->session['etsi_kohteet']; ?>" placeholder="osoite">

   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="fromP" id="from" class="form-control form-group input-sm datepicker" value="<?php echo Yii::app()->session['fromP']; ?>">
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="toP" id="to" class="form-control form-group input-sm datepicker" value="<?php echo Yii::app()->session['toP']; ?>">

     <div class="form-group input-group-btn">
        <button class="btn btn-sm btn-primary haemob" type="button"><i class="glyphicon glyphicon-search"> Hae</i></button>
     </div>
   </div>
  </form>
 </div>
<br>



<!--
  <div class="row">
   <div class="col-sm-4">
        <div class="input-group">
            <input type="text" class="form-control hakusana" placeholder="Hakusana" name="srch-term" id="srch-term" value="<?php echo Yii::app()->session['hakusana']; ?>">
            <div class="input-group-btn">
                <button class="btn btn-primary hae" type="button"><i class="glyphicon glyphicon-search"> Hae</i></button>
            </div>
        </div>
   </div>
  </div>
-->

  <div id="tb" class="table-responsive"></div>

</div>


  <input type="hidden" id="dataChange" >


<script type="text/javascript">
$(document).ready(function(){

/* koko taulukko päivittä joka 60 sek, ja uuden rivin tarkistaminen on 10 sek kuluttua */

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
var mobnum = getParameterByName('Mobile_page');


$(".haemob").click(function(){
	$("#mobForm").submit();
});


// Send form by ajax
$('#mobForm').on('submit',function(e) {

  $.ajax({
  url: location.protocol + "//" + location.host + '/index.php/mobile/index?Mobile_page='+mobnum,
  data:$(this).serialize(),
  type:'POST',
  success:function(data){
  	console.log(data);
	tableAjax();
	return false;
  },
  error:function(data){
  	console.log(data); 
  }
  });

e.preventDefault(); 
});



function tableAjax(){

   $.ajax({
  url: location.protocol + "//" + location.host + '/index.php/mobile/index?Mobile_page='+mobnum,
      type: "POST",
      data: { index_ajax : "true" },
      	success: function(data){
  	  	//console.log(data);
	  	$('#tb').html(data);
      	},
  	error:function(data){
  		console.log(data); 
  	}
   });

}

   setTimeout(function(){tableAjax();},1500);
   setInterval(tableAjax, "60000");


    updateRivi();
    function updateRivi() {
        $.ajax({
           url: 'index_ajax',
           success: function(data){

        var date = new Date();
        var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        time = hours + ":" + minutes + ":" + seconds;


	  	$('.klo').text( time );	
		if(data)
		{
		  if(($("#dataChange").val() != data) & ($("#dataChange").val() != ''))
		  {
                    //console.log("new "+data);
		    var e = data;

		   $.ajax({
		      url: 'index?Mobile_page='+mobnum,
		      type: "POST",
		      data: { index_ajax : "true" },
		      success: function(data){		
			  $('#tb').html(data);	
			  if(e){
			    console.log(e);
			    $('#rivi_'+e).fadeOut(1000).fadeIn(1000).fadeOut(1000).fadeIn(1000);
			  }		
		      }
		   });

		  }
		}
		$("#dataChange").val(data);
           }
        });
    }
    setInterval(updateRivi, "5000");


});
</script>
