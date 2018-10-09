<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"><i class="fa fa-calendar-check-o"></i> <?=Yii::t('main', 'Työvuorojen siirto')?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
			<label>Keneltä</label>
                        <div class="section">
                          <label class="field select">
				<?php
				$name_tyontekijat = 'tekijaPaaSivulla';
				(isset($_GET['aktiivinen']))? $aktiivinen = $_GET['aktiivinen'] : $aktiivinen = 1;
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatListaNoMulti( 
						'kenelta', // name
						'gui-input', //class
						'kenelta', // id
						(isset($_GET['kenelta']))? $_GET['kenelta']: '', //selected
						$aktiivinen// aktiivinen
				);
				echo $tyontekiatLista;
				?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
			<label>Kenelle</label>
                        <div class="section">
                          <label class="field select">
				<?php
				(isset($_GET['aktiivinen']))? $aktiivinen = $_GET['aktiivinen'] : $aktiivinen = 1;
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatListaNoMulti( 
						'kenelle', // name
						'gui-input', //class
						'kenelle', // id
						(isset($_GET['kenelle']))? $_GET['kenelle']: '', //selected
						$aktiivinen// aktiivinen
				);
				echo $tyontekiatLista;
				?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
			<label>Alkaen</label>
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="alkaen" class="gui-input datepickerFI" value="<?php if(isset($_GET['alkaen'])) echo date('d.m.Y', strtotime($_GET['alkaen'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
			<br>
			<?php if( isset($_GET['alkaen'])) : ?>
        	        <?php echo CHtml::link(Yii::t('main', 'Keskeytä'), array('siirto'),array('class'=>'btn btn-primary btn-lg siirra btn-block myBgColors')); ?>
			<?php else : ?>
        	        <button class="btn btn-primary btn-lg haemob btn-block myBgColors"><?php echo Yii::t('main', 'Esikatselu'); ?></button>
			<?php endif; ?>
                        </div>
		      </div>


                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>


	<?php if( isset($_GET['kenelta']) and isset($_GET['kenelle'])) : ?>
        <!-- begin: .tray-center -->
        <div class="tray-center row">
            <div class="admin-form col-sm-5">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
		<h4 class="text-center"><?=$this->etuSukunimi($_GET['kenelta'])?></h4>
		<table class="table table-striped">
		<tr>
		<th><?=Yii::t('main', 'Pvm')?></th>
		<th><?=Yii::t('main', 'Aika')?></th>
		<th><?=Yii::t('main', 'Osoite')?></th>
		</tr>
		<?php $suorittu = 0; ?>
		<?php foreach($data_kenelta as $item) : ?>
		<?php if( isset($_GET['siirra_now']) ): ?>
		<?php
			$suorittu++;
			$t=Tyovuoroot::model()->findByPk($item->id);
			if(!isset($t->id)){ continue; }
			$model=$t;
			$model->attributes=$t->attributes;
			$model->tid=$_GET['kenelle'];
			//$model->toistuva_id=0;
			//$model->tyopaari='';
			if($model->save()){
			    // <-- Tyopaari tavallisessa tyovuorossa
			    if( $t->toistuva_id == 0 and is_array(json_decode($t->tyopaari, true)) ){
				$uusi_tp_arr = array();
				foreach(json_decode($t->tyopaari, true) as  $id => $tp_id){
					$tv = Tyovuoroot::model()->findByPk($id);
					if( isset($tv->id) and $tv->tid == $t->tid ){
						$uusi_tp_arr[$model->id] = $model->tid;
					} else {
						$uusi_tp_arr[$id] = $tp_id;
					}
				}
				foreach( $uusi_tp_arr as $k => $v ){
					if( count($uusi_tp_arr) == 1 ){
					Tyovuoroot::model()->updateByPk($k, array('tyopaari' => ''));
					break;
					}
					Tyovuoroot::model()->updateByPk($k, array('tyopaari' => json_encode($uusi_tp_arr)));
				}
			    }
			    //     Tyopaari tavallisessa tyovuorossa -->

			    // <-- ToistuvatTyovuorot ja tyopaarit
			    if( $t->toistuva_id != 0 and is_array(json_decode($t->tyopaari, true)) ){
				$uusi_tp_arr = array();
				$uusi_tp_arr[] = $model->tid;
				foreach(json_decode($t->tyopaari, true) as $tp_id){
					$uusi_tp_arr[] = $tp_id;
				}
				if( isset($uusi_tp_arr[$item->tid]) ){ unset($uusi_tp_arr[$item->tid]); }
				$criteria = new CDBCriteria;
        			$criteria->condition = " 
					pvm='".$t->pvm."'			
					AND toistuva_id='".$t->toistuva_id."' 
				";
				Tyovuoroot::model()->updateAll(array('tyopaari' => json_encode($uusi_tp_arr)), $criteria);
			    }
			    //     ToistuvatTyovuorot ja tyopaarit -->
			}
		?>
		<?php endif; ?>
		<tr>
		<td><?=$item->pvm?></td>
		<td><?=$item->alku?>-<?=$item->loppu?></td>
		<td><?=isset($item->kohteet->osoite)?$item->kohteet->osoite:$item->osoite?></td>
		</tr>
		<?php endforeach; ?>
		</table>
                </div>
              </div>
            </div>

	    <?php if( $suorittu > 0 ){ 
		Yii::app()->user->setFlash('success', "Onnistui!");
		$this->redirect(array('siirto'));
	    } ?>

            <div class="col-sm-2 text-center">
		<form action="#" method="GET">
		<input type="hidden" name="kenelta" value="<?=$_GET['kenelta']?>">
		<input type="hidden" name="kenelle" value="<?=$_GET['kenelle']?>">
		<input type="hidden" name="alkaen" value="<?=$_GET['alkaen']?>">
		<input type="hidden" name="siirra_now" value="true">
		<button type="submit" class="btn btn-primary btn-lg siirra myBgColors"><i class="fa fa-2x fa-arrow-right"></i></button>
		</form>
            </div>
            <div class="admin-form col-sm-5">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
		<h4 class="text-center"><?=$this->etuSukunimi($_GET['kenelle'])?></h4>
		<table class="table table-striped">
		<tr>
		<th><?=Yii::t('main', 'Pvm')?></th>
		<th><?=Yii::t('main', 'Aika')?></th>
		<th><?=Yii::t('main', 'Osoite')?></th>
		</tr>
		<?php foreach($data_kenelle as $item) : ?>
		<tr>
		<td><?=$item->pvm?></td>
		<td><?=$item->alku?>-<?=$item->loppu?></td>
		<td><?=isset($item->kohteet->osoite)?$item->kohteet->osoite:$item->osoite?></td>
		</tr>
		<?php endforeach; ?>
		</table>
                </div>
              </div>
            </div>
        </div>
        <!-- loppu: .tray-center -->
	<?php endif; ?>



<script type="text/javascript">
$(document).ready(function(){

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
    function(m,key,value) {
      vars[key] = value;
    });
    return vars;
  }

  if(getUrlVars()["selecter"]){ $(".selecter").attr("disabled", "yes") }

  $(".haemob").click(function(e){
	e.preventDefault(); 
	if( $("#kenelta option:selected").val() === 'kaikki' ){
		$("#kenelta").css({"border" : "1px red solid"});
		return false;
	}
	if( $("#kenelle option:selected").val() === 'kaikki' ){
		$("#kenelle").css({"border" : "1px red solid"});
		return false;
	}
	$("#mobForm").submit();
  });

});
</script>
