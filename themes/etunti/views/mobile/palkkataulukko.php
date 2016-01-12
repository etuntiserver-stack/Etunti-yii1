<div class="row">
<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Tunnit'),
);

?>




        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="fa fa-eur"></i> <?php echo Yii::t('main', 'PALKKATAULUKKO'); ?> 
   <!-- tulostus -->
   <div class="pull-right">
    <div class="form-inline">
     <form action="#" target="_blank" class="form-group" method="POST">
      <input type="submit" name="tulosta" class="btn btn-success btn-sm" value="PDF">
     </form>
     <button class="btn btn-info btn-sm btn-group" data-toggle="collapse"  data-target="#haku"><?php echo Yii::t('main', 'Extrat'); ?> <b class="caret"></b></button>
    </div>
   </div>
   <!-- tulostus -->
		</h2>



   	    <form id="yhtveto" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="yhtvetoform">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field">


   <?php
    $list = CHtml::listData(Mobile::model()->findAll(array('order' => 'tekijan_nimi','group'=>'tekijan_nimi')), 'tid', 'tekijan_nimi');

    echo '<select name="Tekija[]" class="mult" id="tyontekijat" multiple title="Työntekijät">';
    foreach($list as $key=>$val){
     if(!empty($val))
     {
       if(isset(Yii::app()->session['Tekija']) and in_array($key,Yii::app()->session['Tekija']))
       	 echo '<option value="'.$key.'" selected>'.$val.'</option>';
       else
       	 echo '<option value="'.$key.'">'.$val.'</option>';
     }
    }
    echo '</select>';
   ?>


                          </label>

   <a href="#" id="deselAll" class="glyphicon glyphicon-minus"></a>
   <a href="#" id="selAll" class="glyphicon glyphicon-plus"></a>


                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   <input type="text" name="from" id="from" class="gui-input datepicker" value="<?php echo $from; ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   <input type="text" name="to" id="to" class="gui-input datepicker" value="<?php echo $to; ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2  col-md-offset-3">
        	        <button class="btn btn-primary btn-lg haemob btn-block myBgColors" type="button"><i class="glyphicon glyphicon-search"> </i> Hae</button>
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>


<br>





<div class="row collapse" id="haku">
  <div class="col-md-12">
   <br><br><br>
   <?php
	$ko = new Korvaukset;
	echo $this->renderPartial('//korvaukset/_form',array('model'=>$ko)); 
   ?>
   <?php
	$lt = new Lisatyotunnit;
	echo $this->renderPartial('//lisatyotunnit/_form',array('model'=>$lt)); 
   ?>
   <?php
	$en = new Ennakko;
	echo $this->renderPartial('//ennakko/_form',array('model'=>$en)); 
   ?>
  </div>
</div>

<br>

<?php if($from and $to) : ?>


  <table class="table table-striped table-bordered small">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Työpäiviä'); ?></th>
  <th><?php echo Yii::t('main', 'Matkat'); ?></th>
  <th><?php echo Yii::t('main', 'Työtunnit'); ?></th>
  <th><?php echo Yii::t('main', 'matka+<br>tunnit yht'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  <th><?php echo Yii::t('main', 'iltamatka+<br>iltatunnit yht'); ?></th>
  <th><?php echo Yii::t('main', 'Yö'); ?></th>
  <th><?php echo Yii::t('main', 'Su'); ?></th>
  <th><?php echo Yii::t('main', 'SL'); ?></th>
  <th><?php echo Yii::t('main', 'SPL<br>Pvm'); ?></th>
  <th><?php echo Yii::t('main', 'LS'); ?></th>
  <th><?php echo Yii::t('main', 'Korvaus'); ?></th>
  <th><?php echo Yii::t('main', 'Lisätyötunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Ennakko'); ?></th>
  </tr>
  </thead>

  <?php 
  $tids = array();
  $totalTp	= 0;
  $tot_sun	= 0;
  $tp		= 0;
  $sl 		= 0;
  $ls 		= 0;
  $spl 		= 0;
  $slYht	= 0;
  $splYht	= 0;
  $lsYht	= 0;
  foreach($model as $data)
  {
	$tids[] = $data->id;
	$tp = $this->Tp($data->id,$from,$to);
  	$sl = $this->TidfromtoSL($from,$to,$data->id);
	$slYht += $sl;
  	$ls = $this->TidfromtoLS($from,$to,$data->id);
	$lsYht += $ls;
  	$spl = $this->TidfromtoSPL($from,$to,$data->id);
	$splYht += $spl;
	$totalTp += $tp;
	$this->renderPartial('_palkkataulukko',array('data'=>$data,'tp'=>$tp,'sl'=>$sl,'spl'=>$spl,'ls'=>$ls,'from'=>$from,'to'=>$to));
  }

	$yht[0] = 0;
	$yht[1] = 0;
	$yht[2] = 0;
	$yht[3] = 0;
	$matka = 0;
	$matkaIlta = 0;

  foreach($tids as $t)
  {
	$matka += $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$t
		),true);


	$matkaIlta += $this->matkaIlta($t,$from,$to);

	$return = $this->toteutu($t,"palkkataulukko",$from,$to);
	$yht[0] += $return[0];
	$yht[1] += $return[1];
	$yht[2] += $return[2];
	$yht[3] += $return[3];
  }

  if($matka != 0)
  $matka = $this->sprint($matka).'<br>('.$this->num($matka).')';
  if($matkaIlta != 0)
  $matkaIlta = '<br><b>Matkat</b>:<br>'.$this->num($matkaIlta);
  ?>
  <tfoot>
  <tr>
  	<th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
	<td><?php echo $totalTp; ?></td>
	<td><?php echo $matka; ?></td>
	<td><?php echo $this->num($yht[0]); ?></td>
	<td><?php echo '<b>Työt</b>:<br>'.$this->num($yht[1]).$matkaIlta; ?></td>
	<td><?php echo $this->sprint($yht[2]); ?></td>
	<td><?php echo $this->sprint($yht[3]); ?></td>
	<td></td>
	<td></td>
	<td><?php echo $this->num($slYht); ?></td>
	<td><?php echo $splYht; ?></td>
	<td><?php echo $this->num($lsYht); ?></td>
	<td></td>
	<td></td>
	<td></td>
  </tr>
  </tfoot>
  </table>


<?php endif; ?>
</div>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>




<script type="text/javascript">
$(document).ready(function(){

$(".haemob").click(function(){
	$("#yhtveto").submit();
});

$('#deselAll').click(function(){
   $('#tyontekijat').selectpicker('deselectAll');
});


$('#selAll').click(function(){
   $('#tyontekijat').selectpicker('selectAll');
});




$("#yhtveto").on('submit',function(e){

  var from = $("#from").val();
  var to = $("#to").val();

    if (from  === '') {
        $('#from').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (to  === '') {
        $('#to').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }

});

$('.mult').selectpicker({
      style: 'gui-input',
      //size: 4
  });

});
</script>
