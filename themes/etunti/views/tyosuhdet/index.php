<?php
/* @var $this TyosuhdetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tyosuhdets',
);
/*
$this->menu=array(
	array('label'=>'Create Tyosuhdet', 'url'=>array('create')),
	array('label'=>'Manage Tyosuhdet', 'url'=>array('admin')),
);
*/
?>

<?php if(!Yii::app()->request->getPost('tulosta')) : ?>
<div class="row">
        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="fa fa-list"></i> <?php echo Yii::t('main', 'TYÖSUHTEET'); ?>

   <!-- tulostus -->
   <div class="pull-right">
    <div class="form-inline">
     <form action="#" target="_blank" class="form-group" method="POST">
      <input type="submit" name="tulosta" class="btn btn-primary btn-sm myBgColors" value="PDF">
     </form>
    </div>
   </div>
   <!-- tulostus -->

	     </h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">



                      <div class="col-md-3">
                        <div class="section">
                          <label class="field prepend-icon">

   <?php
   $criteria = new CDbCriteria();
   $criteria->order = " tekijan_nimi ";
   //$criteria->condition = " aktiivinen='1' ";

    $list = CHtml::listData(Tyontekijat::model()->findAll($criteria), 'id', 'tekijan_nimi');
    echo '<select name="TekijaVuoro[]" id="tyontekijat" multiple title="Työntekijät">';
    foreach($list as $key=>$val){
       if(isset(Yii::app()->session['TekijaVuoro']) and in_array($key,Yii::app()->session['TekijaVuoro']))
       	 echo '<option value="'.$key.'" selected>'.$val.'</option>';
       else
       	 echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>


                          </label>
                        </div>
                      </div>


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field prepend-icon">

<select name="sarakkeet[]" id="sarakkeet" multiple>
   <option value="hetu"><?php echo Yii::t('main', 'Hetu'); ?></option>
   <option value="osoite"><?php echo Yii::t('main', 'Osoite'); ?></option>
   <option value="sosoite"><?php echo Yii::t('main', 'S-osoite'); ?></option>
   <option value="alkaen"><?php echo Yii::t('main', 'Työsuhde alkaen'); ?></option>
   <option value="tyoaika"><?php echo Yii::t('main', 'Sään. työaika vkossa'); ?></option>
   <option value="palkka"><?php echo Yii::t('main', 'Palkka'); ?></option>
   <option value="koeaika"><?php echo Yii::t('main', 'Koeaika kk'); ?></option>
   <option value="palkakoenjalkeen"><?php echo Yii::t('main', 'Palkka koeajan jälkeen'); ?></option>
   <option value="tilinumero"><?php echo Yii::t('main', 'Tilinumero'); ?></option>
   <option value="palkanmaksu"><?php echo Yii::t('main', 'Palkanmaksu'); ?></option>
   <option value="verokorti"><?php echo Yii::t('main', 'Verokortin tiedot'); ?></option>
</select>


                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="Hae">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>




        <!-- loppu: .tray-center -->
        </div>
</div>

<br>
<?php endif; ?>


<?php if(Yii::app()->request->getPost('tulosta')) : ?>
<link rel="stylesheet" type="text/css" href="css/pdf_table.css">
<?php endif; ?>


<div class="row">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">


<div class="row table-responsive tb">
  <table class="table table-bordered table-striped small" style="background: white">
  <thead>
  <tr>
   <th><?php echo Yii::t('main', 'Nimi'); ?></th>
   <?php if(isset($_POST['sarakkeet']) and in_array('hetu',$_POST['sarakkeet'])) : ?>
     <th><?php echo Yii::t('main', 'Hetu'); ?></th>
   <?php endif; ?>
   <?php if(isset($_POST['sarakkeet']) and in_array('osoite',$_POST['sarakkeet'])) : ?>
     <th><?php echo Yii::t('main', 'Osoite'); ?></th>
   <?php endif; ?>
   <?php if(isset($_POST['sarakkeet']) and in_array('sosoite',$_POST['sarakkeet'])) : ?>
     <th><?php echo Yii::t('main', 'S-osoite'); ?></th>
   <?php endif; ?>
   <?php if(isset($_POST['sarakkeet']) and in_array('alkaen',$_POST['sarakkeet'])) : ?>
     <th><?php echo Yii::t('main', 'Työsuhde alkaen'); ?></th>
   <?php endif; ?>
   <?php if(isset($_POST['sarakkeet']) and in_array('tyoaika',$_POST['sarakkeet'])) : ?>
     <th><?php echo Yii::t('main', 'Sään. työaika vkossa'); ?></th>
   <?php endif; ?>
   <?php if(isset($_POST['sarakkeet']) and in_array('palkka',$_POST['sarakkeet'])) : ?>
     <th><?php echo Yii::t('main', 'Palkka'); ?></th>
   <?php endif; ?>
   <?php if(isset($_POST['sarakkeet']) and in_array('koeaika',$_POST['sarakkeet'])) : ?>
     <th><?php echo Yii::t('main', 'Koeaika kk'); ?></th>
   <?php endif; ?>
   <?php if(isset($_POST['sarakkeet']) and in_array('palkakoenjalkeen',$_POST['sarakkeet'])) : ?>
     <th><?php echo Yii::t('main', 'Palkka koeajan jälkeen'); ?></th>
   <?php endif; ?>
   <?php if(isset($_POST['sarakkeet']) and in_array('tilinumero',$_POST['sarakkeet'])) : ?>
     <th><?php echo Yii::t('main', 'Tilinumero'); ?></th>
   <?php endif; ?>
   <?php if(isset($_POST['sarakkeet']) and in_array('palkanmaksu',$_POST['sarakkeet'])) : ?>
     <th><?php echo Yii::t('main', 'Palkanmaksu'); ?></th>
   <?php endif; ?>
   <?php if(isset($_POST['sarakkeet']) and in_array('verokorti',$_POST['sarakkeet'])) : ?>
     <th><?php echo Yii::t('main', 'Verokortin tiedot'); ?></th>
   <?php endif; ?>
  </tr>
  </thead>
  <?php

  foreach($model as $t)
  {

     $alku 	= '';
     $vktyoaika = '';
     $koe_hinta = '';
     $tuntihinta = '';
     $koeaika 	= '';
     $verotiedot = '';
     $palkanmaksu = '';

     $ts = Tyosuhdet::model()->find(" tid = '".$t->id."' ");
     if(isset($ts->id))
     {
        $alku = date("d.m.Y", strtotime($ts->alku));
        $vktyoaika = $ts->vktyoaika.' tuntia/vko';
     	if((float)$ts->koe_hinta > 0) $koe_hinta = $ts->koe_hinta.'&euro;/t';
     	if((float)$ts->tuntihinta > 0) $tuntihinta = $ts->tuntihinta.'&euro;/t';
        $koeaika = $ts->koe_loppu;

	$palkanmaksu = $ts->palkkausmuoto;

	if(!empty($ts->tuloraja_ajalle))
	$verotiedot .= $ts->getAttributeLabel('tuloraja_ajalle').': '.$ts->tuloraja_ajalle.'<br>';
	if(!empty($ts->perusprosentti))
	$verotiedot .= $ts->getAttributeLabel('perusprosentti').': '.$ts->perusprosentti.'<br>';
	if(!empty($ts->lisaprosentti))
	$verotiedot .= $ts->getAttributeLabel('lisaprosentti').': '.$ts->lisaprosentti.'<br>';
	if(!empty($ts->kuukaudessa))
	$verotiedot .= $ts->getAttributeLabel('kuukaudessa').': '.$ts->kuukaudessa.'<br>';
	if(!empty($ts->kahdessa_viikossa))
	$verotiedot .= $ts->getAttributeLabel('kahdessa_viikossa').': '.$ts->kahdessa_viikossa.'<br>';
	if(!empty($ts->viikossa))
	$verotiedot .= $ts->getAttributeLabel('viikossa').': '.$ts->viikossa.'<br>';
	if(!empty($ts->paivassa))
	$verotiedot .= $ts->getAttributeLabel('paivassa').': '.$ts->paivassa.'<br>';
	if(!empty($ts->atk_varten))
	$verotiedot .= $ts->getAttributeLabel('atk_varten').': '.$ts->atk_varten.'<br>';
	if(!empty($ts->yksi_tuloraja))
	$verotiedot .= $ts->getAttributeLabel('yksi_tuloraja').': '.$ts->yksi_tuloraja;
     }

	echo '<tr>';
	echo '<td>'.$t->tekijan_nimi.'</td>';
if(isset($_POST['sarakkeet']) and in_array('hetu',$_POST['sarakkeet']))
	echo '<td>'.$t->tekijan_henkilotunnus.'</td>';
if(isset($_POST['sarakkeet']) and in_array('osoite',$_POST['sarakkeet']))
	echo '<td>'.$t->tekijan_katuosoite.'</td>';
if(isset($_POST['sarakkeet']) and in_array('sosoite',$_POST['sarakkeet']))
	echo '<td>'.$t->tekijan_email.'</td>';
if(isset($_POST['sarakkeet']) and in_array('alkaen',$_POST['sarakkeet']))
	echo '<td>'.$alku.'</td>';
if(isset($_POST['sarakkeet']) and in_array('tyoaika',$_POST['sarakkeet']))
	echo '<td>'.$vktyoaika.'</td>';
if(isset($_POST['sarakkeet']) and in_array('palkka',$_POST['sarakkeet']))
	echo '<td>'.$koe_hinta.'</td>';
if(isset($_POST['sarakkeet']) and in_array('koeaika',$_POST['sarakkeet']))
	echo '<td>'.$koeaika.'</td>';
if(isset($_POST['sarakkeet']) and in_array('palkakoenjalkeen',$_POST['sarakkeet']))
	echo '<td>'.$tuntihinta.'</td>';
if(isset($_POST['sarakkeet']) and in_array('tilinumero',$_POST['sarakkeet']))
	echo '<td>'.str_replace(" ", "", $t->tekijan_pankkitili).'</td>';
if(isset($_POST['sarakkeet']) and in_array('palkanmaksu',$_POST['sarakkeet']))
	echo '<td>'.$palkanmaksu.'</td>';
if(isset($_POST['sarakkeet']) and in_array('verokorti',$_POST['sarakkeet']))
	echo '<td>'.$verotiedot.'</td>';
	echo '</tr>';
  }
  ?>
  </table>
</div>


                 </div>
                </div>
              </div>
</div>






<?php if(!Yii::app()->request->getPost('tulosta')) : ?>
<script type="text/javascript">
$(document).ready(function(){

$('#tyontekijat').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: 'Tyhjä',
	selectAllText: 'Valitse kaikki',
	allSelectedText: 'Kaikki',
	nSelectedText: 'valittu',
});

$('#sarakkeet').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: 'Tyhjä',
	selectAllText: 'Valitse kaikki',
	allSelectedText: 'Kaikki',
	nSelectedText: 'valittu',
});


});
</script>
<?php endif; ?>

