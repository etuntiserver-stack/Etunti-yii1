<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */
ini_set('memory_limit','256M');
ini_set("max_execution_time", "60");
$tyovuoroot = Yii::app()->createController('Tyovuoroot');
?>




        <!-- begin: .tray-center -->
        <div class="tray-center">

            <h2 class="myBgColors p10"> <i class="fa fa-line-chart"></i> Kaaviot </h2>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'tyovuorojen_maara')): ?>
   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="tyovuorojen_maara">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Työvuorojen määrä ajanjaksolla')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="line" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'line')?'selected':''?>><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-4">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'lomat_poissaolot')): ?>
   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="lomat_poissaolot">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Lomat ja poissaolot')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
				<?php
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatListaNoMulti( 
						'tyontekija', // name
						'gui-input', //class
						'tyontekija', // id
						(isset($_GET['tyontekija']))?$_GET['tyontekija']:'', //selected
						1 // aktiivinen
				);
				echo $tyontekiatLista;
				?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
		      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="line" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'line')?'selected':''?>><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_uudet_lopettaneet')): ?>
   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="asiakkaat_uudet_lopettaneet">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Uudet ja lopettaneet asiakkaat - KPL määrä')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="line" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'line')?'selected':''?>><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-4">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_uudet_lopettaneet_tv')): ?>
   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="asiakkaat_uudet_lopettaneet_tv">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Uudet ja lopettaneet asiakkaat - Suunnitellut työvuorot')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="line" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'line')?'selected':''?>><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-4">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'tyontekijat_top')): ?>
   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="tyontekijat_top">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Työntekijät, joilla eniten hyväksyttyjä tunteja')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="line" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'line')?'selected':''?>><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="hyvaksynta" class="gui-input">
			     <option value="1" <?=(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == '1')?'selected':''?>><?php echo Yii::t('main', 'Hyväksyntä'); ?></option>
			     <option value="2" <?=(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == '2')?'selected':''?>><?php echo Yii::t('main', 'Hyväksytyt'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">
				<?php if(!isset($_GET['from'])){ $from_to_top = "first day of last month"; } else { $from_to_top = $from; } ?>
	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from_to_top))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">
				<?php if(!isset($_GET['to'])){ $to_to_top = "last day of last month"; } else { $to_to_top = $to; } ?>
   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to_to_top))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_top')): ?>
   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="asiakkaat_top">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Asiakkaat, joille on tehty eniten hyväksyttyjä tunteja')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="line" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'line')?'selected':''?>><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="hyvaksynta" class="gui-input">
			     <option value="1" <?=(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == '1')?'selected':''?>><?php echo Yii::t('main', 'Hyväksyntä'); ?></option>
			     <option value="2" <?=(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == '2')?'selected':''?>><?php echo Yii::t('main', 'Hyväksytyt'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">
				<?php if(!isset($_GET['from'])){ $from_to_top = "first day of last month"; } else { $from_to_top = $from; } ?>
	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from_to_top))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">
				<?php if(!isset($_GET['to'])){ $to_to_top = "last day of last month"; } else { $to_to_top = $to; } ?>
   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to_to_top))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_slh')): ?>
   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="asiakkaat_slh">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Suunniteltut, luetut ja hyväksytyt tunnit')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Asiakas';
				$postvalue = '';
				if(isset($_GET[$sarake])){ $postvalue = $_GET[$sarake]; }
		 	        $site[0]->autocompleteFor($mod,array('yrityksen_nimi','yhteyshenkilo'), $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="line"><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'eniten_tyovuoro_kestot')): ?>
   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="eniten_tyovuoro_kestot">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Eniten Suunniteltu työvuoro - Kestot')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="kpl_maara" class="gui-input">
			     <option value="5" <?=(isset($_GET['kpl_maara']) and $_GET['kpl_maara'] == 5)?'selected':''?>>5</option>
			     <option value="10" <?=(isset($_GET['kpl_maara']) and $_GET['kpl_maara'] == 10)?'selected':''?>>10</option>
			     <option value="20" <?=(isset($_GET['kpl_maara']) and $_GET['kpl_maara'] == 20)?'selected':''?>>20</option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="line" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'line')?'selected':''?>><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'eniten_tyovuoro_kpl')): ?>
   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="eniten_tyovuoro_kpl">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Eniten Suunniteltu työvuoro - KPL')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="kpl_maara" class="gui-input">
			     <option value="5" <?=(isset($_GET['kpl_maara']) and $_GET['kpl_maara'] == 5)?'selected':''?>>5</option>
			     <option value="10" <?=(isset($_GET['kpl_maara']) and $_GET['kpl_maara'] == 10)?'selected':''?>>10</option>
			     <option value="20" <?=(isset($_GET['kpl_maara']) and $_GET['kpl_maara'] == 20)?'selected':''?>>20</option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="line" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'line')?'selected':''?>><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'lahetettyjen_laskut')): ?>
   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="lahetettyjen_laskut">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Lähetettyjen laskujen määrä ja summa')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Asiakas';
				$postvalue = '';
				if(isset($_GET[$sarake])){ $postvalue = $_GET[$sarake]; }
		 	        $site[0]->autocompleteFor($mod,array('yrityksen_nimi','yhteyshenkilo'), $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="line"><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_arvoikkaimat')): ?>
   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="asiakkaat_arvoikkaimat">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Liikevaihto arvokkaimmat asiakkaat')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="kpl_maara" class="gui-input">
			     <option value="5" <?=(isset($_GET['kpl_maara']) and $_GET['kpl_maara'] == 5)?'selected':''?>>5</option>
			     <option value="10" <?=(isset($_GET['kpl_maara']) and $_GET['kpl_maara'] == 10)?'selected':''?>>10</option>
			     <option value="20" <?=(isset($_GET['kpl_maara']) and $_GET['kpl_maara'] == 20)?'selected':''?>>20</option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="line" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'line')?'selected':''?>><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'tyontekijat')): ?>
   	    <form id="yhtveto_tyontekijat" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="tyontekijat">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Työntekijät')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
				<?php
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatListaNoMulti( 
						'tyontekija', // name
						'gui-input', //class
						'yhtveto-tyontekijat-tyontekija', // id
						(isset($_GET['tyontekija']))?$_GET['tyontekija']:'', //selected
						1 // aktiivinen
				);
				echo $tyontekiatLista;
				?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
		      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="line"><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'onlinevaraukset')): ?>
   	    <form id="yhtveto_tyontekijat" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="onlinevaraukset">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Onlinevaraukset')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Asiakas';
				$postvalue = '';
				if(isset($_GET[$sarake])){ $postvalue = $_GET[$sarake]; }
		 	        $site[0]->autocompleteFor($mod,array('yrityksen_nimi','yhteyshenkilo'), $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="line"><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'eniten_tuotteet_per_laskurivi_euro')): ?>
   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="eniten_tuotteet_per_laskurivi_euro">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Eniten Tuotteet ja palvelut Euro')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="line" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'line')?'selected':''?>><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-4">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

        <!-- loppu: .tray-center -->
        </div>


<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<div id="container" style="height: 500px"></div>
<div id="container2" style="height: 500px;display:none"></div>

<!-- Työvuorojen määrä ajanjaksolla -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'tyovuorojen_maara'): ?>
<?php
	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data_arr = [];
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
	}

	$tids 		= [];
	$with		= ['status'];
	$haku_criteria	= [];
	$dataAll = $tyovuoroot[0]->FromToSuunnitellutAll(date("Y-m-d", strtotime($from)), date("Y-m-d", strtotime($to." +1 month")), $tids, $haku_criteria, $with);
	$ym_arr = [];
	foreach($dataAll as $arr){
		if( !isset($ym_arr[ date("Ym", strtotime($arr['this_pvm'])) ][$arr['status']]) )
			$ym_arr[ date("Ym", strtotime($arr['this_pvm'])) ][$arr['status']] = 1;
		else
			$ym_arr[ date("Ym", strtotime($arr['this_pvm'])) ][$arr['status']] += 1;
	}
	/*
	echo '<pre>';
	print_r( $ym_arr );
	echo '</pre>';
	exit;
	*/
	$arr_new 	= [];
	$i 		= 0;
	foreach($tyovuoroot[0]->tilanteet() as $k => $v){
		$i++;
		$arr_new[$i] = array('name' => $v );
		$arr_new[$i]['data'] = array();
		foreach($categories as $k_cat => $item_cat)
			$arr_new[$i]['data'][] = (isset($ym_arr[$k_cat][$k]))? (int)$ym_arr[$k_cat][$k]:0;
	}
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Työvuorojen määrä <?=date("d.m.Y", strtotime($from))."-".date("d.m.Y", strtotime($to))?>'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
        title: {
            text: 'KPL'
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
    series: JSON.parse('<?=json_encode(array_values($arr_new))?>'),
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Työvuorojen määrän kehitys kk -->

<!-- Lomat ja poissaolot -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'lomat_poissaolot'): ?>
<?php
	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data_arr = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
	}
/*
	$criteria = new CDbCriteria();
       	$criteria->order = " tyoajanlaatu ";
       	$criteria->group = " tyoajanlaatu ";
       	$criteria->select = "
		COUNT(*) as count, t.*
	";
        $criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND tyoajanlaatu!=''
	";
	if( isset($_GET['tyontekija']) and $_GET['tyontekija'] !== 'kaikki' and $_GET['tyontekija'] > 0 ){
	        $criteria->addCondition (" tid='".$_GET['tyontekija']."' ");
	}
	$tv = Tyovuoroot::model()->findAll($criteria);
*/
	$tids 		= [];
	if( isset($_GET['tyontekija']) and $_GET['tyontekija'] !== 'kaikki' and $_GET['tyontekija'] > 0 )
	        $tids[$_GET['tyontekija']] = $_GET['tyontekija'];

	$with		= ['tyoajanlaatu'];
	$haku_criteria	= "tyoajanlaatu!=''";
	$dataAll = $tyovuoroot[0]->FromToSuunnitellutAll(date("Y-m-d", strtotime($from)), date("Y-m-d", strtotime($to." +1 month")), $tids, $haku_criteria, $with);
	$ym_arr = [];
	foreach($dataAll as $arr){
		if( !isset($ym_arr[ date("Ym", strtotime($arr['this_pvm'])) ][$arr['tyoajanlaatu']]) )
			$ym_arr[ date("Ym", strtotime($arr['this_pvm'])) ][$arr['tyoajanlaatu']] = 1;
		else
			$ym_arr[ date("Ym", strtotime($arr['this_pvm'])) ][$arr['tyoajanlaatu']] += 1;
	}
	$tyoajanlaadut = [];
	foreach($ym_arr as $k=>$v)
		foreach($v as $k1=>$v1)
			$tyoajanlaadut[$k1] = $k1;

	/* echo '<pre>'; print_r( $tyoajanlaadut ); echo '</pre>'; exit; */

	$arr_new 	= [];
	$i 		= 0;
	foreach($tyoajanlaadut as $v){
		$i++;
		$name_expl = explode("/", $v);
		$arr_new[$i] = array('name' => ((isset($name_expl[0]))?$name_expl[0]:'') );
		$arr_new[$i]['data'] = array();
		foreach($categories as $k_cat => $item_cat)
			$arr_new[$i]['data'][] = (isset($ym_arr[$k_cat][$v]))? (int)$ym_arr[$k_cat][$v]:0;
	}
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Lomat ja poissaolot <?=date("d.m.Y", strtotime($from))."-".date("d.m.Y", strtotime($to))?>'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
        title: {
            text: 'Tunnit'
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
    series: JSON.parse('<?=json_encode(array_values($arr_new))?>'),
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Lomat ja poissaolot -->

<!-- Uudet ja lopettaneet asiakkaat kuukausittain KPL -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_uudet_lopettaneet'): ?>
<?php
	// <-- Uudet
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE(time)) ";
       	$criteria->select = "
		COUNT(*) as count, t.*
	";
        $criteria->condition = " 
		DATE(time) BETWEEN '".$from."' AND '".$to."'
	";
	$asiakkaat = Asiakkaat::model()->findAll($criteria);
	$arr_uudet = array();
	foreach($asiakkaat as $item){
		$arr_uudet[date("Ym", strtotime($item->time))] = (int)$item->count;
	}
	//     Uudet -->

	// <-- Lopettaneet
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(lopetuksen_pvm, '%d.%m.%Y'), '%Y-%m-%d')) ";
       	$criteria->select = "
		COUNT(*) as count, t.*
	";
        $criteria->condition = " 
		lopetuksen_pvm!=''
		AND DATE_FORMAT(STR_TO_DATE(lopetuksen_pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
	";
	$asiakkaat = Asiakkaat::model()->findAll($criteria);
	$arr_lop = array();
	foreach($asiakkaat as $item){
		$arr_lop[date("Ym", strtotime($item->lopetuksen_pvm))] = (int)$item->count;
	}
	//     Lopettaneet -->

	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data_uudet = array();
	$data_lop = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
		if(isset($arr_uudet[$dt->format( "Ym" )])){
			$data_uudet[$dt->format( "Ym" )] = $arr_uudet[$dt->format( "Ym" )];
		} else {
			$data_uudet[$dt->format( "Ym" )] = 0;
		}

		if(isset($arr_lop[$dt->format( "Ym" )])){
			$data_lop[$dt->format( "Ym" )] = $arr_lop[$dt->format( "Ym" )];
		} else {
			$data_lop[$dt->format( "Ym" )] = 0;
		}
	}
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Uudet ja lopettaneet asiakkaat'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
        title: {
            text: 'Tunnit'
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
        name: 'Uudet',
        data: JSON.parse('<?=json_encode(array_values($data_uudet))?>')
    },{
        name: 'Lopettaneet',
        data: JSON.parse('<?=json_encode(array_values($data_lop))?>')
    }],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Uudet ja lopettaneet asiakkaat kuukausittain KPL -->

<!-- Uudet ja lopettaneet asiakkaat - Suunnitellut työvuorot -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_uudet_lopettaneet_tv'): ?>
<?php


	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data_uudet = array();
	$data_lopettaneet = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];

		$criteria = new CDbCriteria();
		$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";
		$criteria->condition = " 
			kohde IN ( SELECT id FROM sivex_kohdet WHERE asiakas_id IN 
					( SELECT id FROM asiakkaat WHERE YEAR(time)='".$dt->format( "Y" )."' AND MONTH(time)='".$dt->format( "m" )."' )
			)
			AND status=3
			AND peruutettu=0
		";
		$tv = Tyovuoroot::model()->find($criteria);
		if( isset($tv->l_tunnit) ){
			$arr_uudet[$dt->format( "Ym" )] = round($this->num($tv->l_tunnit), 2);
		} else {
			$arr_uudet[$dt->format( "Ym" )] = 0;
		}
/*
		$criteria = new CDbCriteria();
		$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";
		$criteria->condition = " 
			kohde IN ( SELECT id FROM sivex_kohdet WHERE asiakas_id IN 
					( SELECT id FROM asiakkaat WHERE lopetuksen_pvm!=''
						AND DATE_FORMAT(STR_TO_DATE(lopetuksen_pvm, '%d.%m.%Y'), '%Y%m')='".$dt->format( "Ym" )."' )
			)
			AND status=3
			AND peruutettu=0
		";
		$tv = Tyovuoroot::model()->find($criteria);
		if( isset($tv->l_tunnit) ){
			$data_lopettaneet[$dt->format( "Ym" )] = round($this->num($tv->l_tunnit), 2);
		} else {
			$data_lopettaneet[$dt->format( "Ym" )] = 0;
		}
*/
	}
/*
echo '<pre>';
print_r($arr_uudet);
echo '</pre>';
*/
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Uudet ja lopettaneet asiakkaat - Suunnitellut työvuorot'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
        title: {
            text: 'Tunnit'
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
        name: 'Uudet',
        data: JSON.parse('<?=json_encode(array_values($arr_uudet))?>')
    }/*,{
        name: 'Lopettaneet',
        data: JSON.parse('<?=json_encode(array_values($data_lopettaneet))?>')
    }*/],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Uudet ja lopettaneet asiakkaat - Suunnitellut työvuorot -->

<!-- Työntekijät TOP -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'tyontekijat_top'): ?>
<?php
	$result = array();
	// <-- Hyvaksytyt yritykset
	$criteria = new CDbCriteria();
       	$criteria->limit = "20";
       	$criteria->group = "tid";
       	$criteria->order = "l_tunnit DESC";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND deleted=0
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
	";
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 1){
		$criteria->addCondition(" hyvaksytty='' OR hyvaksytty!='' ");
	}
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 2){
		$criteria->addCondition(" hyvaksytty!='' ");
	}
	$lu = Mobile::model()->findAll($criteria);

	$criteria = new CDbCriteria();
       	$criteria->limit = "20";
       	$criteria->group = "tid";
       	$criteria->order = "l_tunnit DESC";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND status=3
		AND deleted=0
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
	";
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 1){
		$criteria->addCondition(" hyvaksytty='' OR hyvaksytty!='' ");
	}
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 2){
		$criteria->addCondition(" hyvaksytty!='' ");
	}
	$tot = Toteutuneet::model()->findAll($criteria);
	$result = array_merge($lu, $tot);
	$data_hyvaksytyt = array();
	$data_suunnitellut = array();
	$categories_yritykset = array();
	$new_arr = array();
	$arr = array();
	foreach($result as $k=>$v){
		if(isset($v->kohteet->asiakkaat->id) and !isset($arr[$v->kohteet->asiakkaat->id])){
			$new_arr[$v->l_tunnit] = $v;
		}
		if(isset($v->kohteet->asiakkaat->id)){
			$arr[$v->kohteet->asiakkaat->id] = true;
		}
	}
	ksort($new_arr);
	$i = 0;
	foreach(array_reverse($new_arr) as $item){
		$criteria = new CDbCriteria();
	       	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";
	        $criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			AND tid='".$item->tid."'
			AND status=3
			AND peruutettu=0
		";
		$suunnitellut = Tyovuoroot::model()->find($criteria);
		$st = 0;
		if(isset($suunnitellut->l_tunnit)){
			$st = $suunnitellut->l_tunnit;
		}
	  	$i++;
		$data_hyvaksytyt[$item->l_tunnit] = round($this->num($item->l_tunnit), 2);
		$data_suunnitellut[$item->l_tunnit] = round($this->num($st), 2);
		$categories_yritykset[$item->l_tunnit] = $this->etuSukunimi($item->tid);
	  if($i > 20){ break; }
	}
	//     Hyvaksytyt yritykset -->
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Suunnitellut ja Hyväksytyt tunnit työntekijän mukaan'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories_yritykset))?>')
    },
    yAxis: {
        title: {
            text: 'Tunnit'
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
        name: 'Suunnitellut',
        data: JSON.parse('<?=json_encode(array_values($data_suunnitellut))?>')
    },{
        name: '<?=(isset($_GET["hyvaksynta"]) and $_GET["hyvaksynta"] == 1)?"Hyväksyntä":""?><?=(isset($_GET["hyvaksynta"]) and $_GET["hyvaksynta"] == 2)?"Hyväksytyt":""?>',
        data: JSON.parse('<?=json_encode(array_values($data_hyvaksytyt))?>')
    }],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Työntekijät TOP -->

<!-- Asiakkaat TOP -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_top'): ?>
<?php
	$result = array();
	// <-- Hyvaksytyt yritykset
	$criteria = new CDbCriteria();
       	$criteria->limit = "20";
       	$criteria->group = "asiakas";
       	$criteria->order = "l_tunnit DESC";
       	$criteria->select = "
		(SELECT id FROM asiakkaat WHERE id IN(SELECT asiakas_id FROM sivex_kohdet WHERE id=t.kohdenID)) as asiakas,
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND deleted=0
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
	";
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 1){
		$criteria->addCondition(" hyvaksytty='' OR hyvaksytty!='' ");
	}
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 2){
		$criteria->addCondition(" hyvaksytty!='' ");
	}
	$lu = Mobile::model()->findAll($criteria);

	$criteria = new CDbCriteria();
       	$criteria->limit = "20";
       	$criteria->group = "asiakas";
       	$criteria->order = "l_tunnit DESC";
       	$criteria->select = "
		(SELECT id FROM asiakkaat WHERE id IN(SELECT asiakas_id FROM sivex_kohdet WHERE id=t.kohdenID)) as asiakas,
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND status=3
		AND deleted=0
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
	";
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 1){
		$criteria->addCondition(" hyvaksytty='' OR hyvaksytty!='' ");
	}
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 2){
		$criteria->addCondition(" hyvaksytty!='' ");
	}
	$tot = Toteutuneet::model()->findAll($criteria);
	$result = array_merge($lu, $tot);
	$data_hyvaksytyt = array();
	$data_suunnitellut = array();
	$categories_yritykset = array();
	$new_arr = array();
	$arr = array();
	foreach($result as $k=>$v){
		if(isset($v->kohteet->asiakkaat->id) and !isset($arr[$v->kohteet->asiakkaat->id])){
			$new_arr[$v->l_tunnit] = $v;
		}
		if(isset($v->kohteet->asiakkaat->id)){
			$arr[$v->kohteet->asiakkaat->id] = true;
		}
	}
	ksort($new_arr);
	$i = 0;
	foreach(array_reverse($new_arr) as $item){
	  if(isset($item->kohteet->asiakkaat->id)){

		$criteria = new CDbCriteria();
	       	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";
	        $criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			AND kohde IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='".$item->kohteet->asiakkaat->id."')
			AND status=3
			AND peruutettu=0
		";
		$suunnitellut = Tyovuoroot::model()->find($criteria);
		$st = 0;
		if(isset($suunnitellut->l_tunnit)){
			$st = $suunnitellut->l_tunnit;
		}
	  	$i++;
		$data_hyvaksytyt[$item->l_tunnit] = round($this->num($item->l_tunnit), 2);
		$data_suunnitellut[$item->l_tunnit] = round($this->num($st), 2);
		$categories_yritykset[$item->l_tunnit] = $item->kohteet->asiakkaat->Fullname;
	  }
	  if($i > 20){ break; }
	}
	//     Hyvaksytyt yritykset -->
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Suunnitellut ja Hyväksytyt tunnit asiakkaiden mukaan'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories_yritykset))?>')
    },
    yAxis: {
        title: {
            text: 'Tunnit'
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
        name: 'Suunnitellut',
        data: JSON.parse('<?=json_encode(array_values($data_suunnitellut))?>')
    },{
        name: '<?=(isset($_GET["hyvaksynta"]) and $_GET["hyvaksynta"] == 1)?"Hyväksyntä":""?><?=(isset($_GET["hyvaksynta"]) and $_GET["hyvaksynta"] == 2)?"Hyväksytyt":""?>',
        data: JSON.parse('<?=json_encode(array_values($data_hyvaksytyt))?>')
    }],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Asiakkaat TOP -->

<!-- Asiakkaat kuukausittain -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_slh'): ?>
<?php
	$result = array();
	// <-- Luetut
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND deleted=0
	";
	if( isset($asiakas->id) ){
	$criteria->addCondition(" kohdenID IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas->id."') "); 
	}
	$luetut = Mobile::model()->findAll($criteria);
	$data_luetut = array();
	foreach($luetut as $item){
		$data_luetut[date("Ym", strtotime($item->aloitan))] = round($this->num($item->l_tunnit), 2);

	}
	//     Luetut -->

	// <-- Suunnitellut
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y.%m.%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND peruutettu=0
	";
	if( isset($asiakas->id) ){
	$criteria->addCondition(" kohde IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas->id."') "); 
	}
	$suunnitellut = Tyovuoroot::model()->findAll($criteria);
	$data_suunnitellut = array();
	foreach($suunnitellut as $item){
		$data_suunnitellut[date("Ym", strtotime($item->pvm))] = round($this->num($item->l_tunnit), 2);

	}
	//     Suunnitellut -->

	// <-- Hyvaksytyt
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND deleted=0
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
	";
	if( isset($asiakas->id) ){
	$criteria->addCondition(" kohdenID IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas->id."') "); 
	}
	$lu = Mobile::model()->findAll($criteria);

	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND status=3
		AND deleted=0
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
	";
	if( isset($asiakas->id) ){
	$criteria->addCondition(" kohdenID IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas->id."') "); 
	}
	$tot = Toteutuneet::model()->findAll($criteria);
	$result = array_merge($lu, $tot);
	$data_hyvaksytyt = array();
	$categories = array();
	foreach($result as $item){
		if( !isset($data_hyvaksytyt[date("Ym", strtotime($item->aloitan))]) ){ 
			$data_hyvaksytyt[date("Ym", strtotime($item->aloitan))] = 0;
		}
		$data_hyvaksytyt[date("Ym", strtotime($item->aloitan))] += round($this->num($item->l_tunnit), 2);
		$categories[date("Ym", strtotime($item->aloitan))] = date("Y", strtotime($item->aloitan)).', '.$months[date("n", strtotime($item->aloitan))];
	}
	//     Hyvaksytyt -->
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Suunniteltut, luetut ja hyväksytyt tunnit'
    },
    subtitle: {
        text: '<?=(isset($_GET["yrityksen_nimi"]) and !empty($_GET["yrityksen_nimi"]))? $_GET["yrityksen_nimi"] : "Kaikki asiakkaat"?>'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
        title: {
            text: 'Tunnit'
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
        name: 'Luetut',
        data: JSON.parse('<?=json_encode(array_values($data_luetut))?>')
    }, 
    {
        name: 'Hyväksytyt',
        data: JSON.parse('<?=json_encode(array_values($data_hyvaksytyt))?>')
    },
    {
        name: 'Suunnitellut',
        data: JSON.parse('<?=json_encode(array_values($data_suunnitellut))?>')
    },],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Asiakkaat kuukausittain -->

<!-- Lähetettyjen laskujen määrä ja summa -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'lahetettyjen_laskut'): ?>
<?php
	// <-- Laskut
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d')) ";
       	$criteria->select = "
		paivays, SUM(yhteensa_total_veroton) as yhteensa_total_veroton
	";
        $criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND tilanne=1
	";
	if( isset($asiakas->id) ){
	$criteria->addCondition(" as_nro IN (SELECT asiakasnumero FROM asiakkaat WHERE id='".$asiakas->id."') "); 
	}
	$lasku = Lasku::model()->findAll($criteria);
	$data_kpl = array();
	$data_summa = array();
	$categories = array();
	foreach($lasku as $item){
		$lasku_count = 0;
		$criteria = new CDbCriteria();
		$criteria->select = "COUNT(*) as count";
	        $criteria->condition = " 
			YEAR(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='".date("Y", strtotime($item->paivays))."' 
			AND MONTH(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='".date("m", strtotime($item->paivays))."' 
		";
		if( isset($asiakas->id) ){
			$criteria->addCondition(" as_nro IN (SELECT asiakasnumero FROM asiakkaat WHERE id='".$asiakas->id."') "); 
		}
		$lasku_count = Lasku::model()->find($criteria);

		$data_kpl[date("Ym", strtotime($item->paivays))] = (isset($lasku_count->count))?(int)$lasku_count->count:0;
		$data_summa[date("Ym", strtotime($item->paivays))] = (int)round($item->yhteensa_total_veroton, 2);
		$categories[date("Ym", strtotime($item->paivays))] = date("Y", strtotime($item->paivays)).', '.$months[date("n", strtotime($item->paivays))];
	}
	//     Laskut -->
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Lähetettyjen laskujen määrä ja summa'
    },
    subtitle: {
        text: '<?=(isset($_GET["yrityksen_nimi"]) and !empty($_GET["yrityksen_nimi"]))? $_GET["yrityksen_nimi"] : "Kaikki asiakkaat"?>'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
        title: {
            text: 'Tunnit'
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
        name: 'Määrä',
        data: JSON.parse('<?=json_encode(array_values($data_kpl))?>')
    }, 
    {
        name: 'Summa',
        data: JSON.parse('<?=json_encode(array_values($data_summa))?>')
    }],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Lähetettyjen laskujen määrä ja summa -->

<!-- Liikevaihto arvokkaimmat asiakkaat -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_arvoikkaimat'): ?>
<?php
	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
	}

	$tuote = array();
	for ($i = 1; $i <= $_GET['kpl_maara']; $i++) {
		$per = array();
		foreach($period as $dt) {
			$criteria = new CDbCriteria();
		       	$criteria->group = " as_nro ";
		       	$criteria->limit = 1;
		       	$criteria->order = " SUM(yhteensa_total_veroton) DESC ";
			$criteria->select = " SUM(yhteensa_total_veroton) as yhteensa_total_veroton, as_nro";
		       	$criteria->condition = "
				YEAR(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='".$dt->format( "Y" )."' 
				AND MONTH(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='".$dt->format( "m" )."'
			";
			if( isset($tuote[$dt->format( "Ym" )]) ){
				foreach($tuote[$dt->format( "Ym" )] as $k=>$v){
					foreach($v as $k1=>$v1){
		       				$criteria->addcondition (" as_nro!='".$v1."' ");
					}
				}
			}
			$lasku = Lasku::model()->findAll($criteria);
			foreach($lasku as $item){
					if( isset($item->asiakkaat->tyyppi) ){ 
						$asiakas = $item->asiakkaat->Fullname; 
					}
					$per[] = array("name" => $this->clean($asiakas), "y" => (int)$item->yhteensa_total_veroton);
					$tuote[$dt->format( "Ym" )][$item->as_nro][] = $item->as_nro;
			}

		}
			$data[] = array("data" => array_values($per), "name" => "");

	}

/*
echo '<pre>';
print_r(json_encode($data));
echo '</pre>';
//exit;
*/
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Liikevaihto arvokkaimmat asiakkaat'
    },
    subtitle: {
        text: '<?=(isset($_GET["yrityksen_nimi"]) and !empty($_GET["yrityksen_nimi"]))? $_GET["yrityksen_nimi"] : "Kaikki asiakkaat"?>'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
            stackLabels: {
                enabled: true,
                align: 'center',
		text: 'Euro',
            }
    },
    plotOptions: {
            column: {
                stacking: 'normal',
                pointPadding: 0,
                groupPadding: 0,
                dataLabels: {
                    enabled: true,
                    color: 'white'
                }
            }
    },
    tooltip: {
       	split: false,
        shared: true,
        pointFormatter: function() {
		return "<span style='color:{point.color}'></span> " + this.name + ": <b>" + Highcharts.numberFormat(this.y, 2, ",", ".") + "</b><br/>";
        }
    }, 
    series: JSON.parse('<?=json_encode(array_values($data))?>'),
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Liikevaihto arvokkaimmat asiakkaat -->

<!-- Eniten Suunniteltu työvuoro - Kestot -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'eniten_tyovuoro_kestot'): ?>
<?php
	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
	}

	$tuote = array();
	for ($i = 1; $i <= $_GET['kpl_maara']; $i++) {
		$per = array();
		foreach($period as $dt) {
			$criteria = new CDbCriteria();
		       	$criteria->group = " kohde ";
		       	$criteria->limit = 1;
		       	$criteria->order = " SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) DESC ";
			$criteria->select = " SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit, osoite, kohde";
		       	$criteria->condition = "
				kohde!=0
				AND YEAR(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))='".$dt->format( "Y" )."' 
				AND MONTH(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))='".$dt->format( "m" )."'
				AND status=3
				AND peruutettu=0
			";
			if( isset($tuote[$dt->format( "Ym" )]) ){
				foreach($tuote[$dt->format( "Ym" )] as $k=>$v){
					foreach($v as $k1=>$v1){
		       				$criteria->addcondition (" kohde!='".$v1."' ");
					}
				}
			}
			$lasku = Tyovuoroot::model()->findAll($criteria);
			foreach($lasku as $item){
					$osoite = '';
					if( !empty($item->osoite)){ 
						$osoite = $item->osoite; 
					} elseif( isset($item->kohteet->osoite) ){
						$osoite = $item->kohteet->osoite; 
					}
					$per[] = array("name" => $this->clean($osoite), "y" => round((int)$item->l_tunnit/3600), 2);
					$tuote[$dt->format( "Ym" )][$item->kohde][] = $item->kohde;
			}

		}
			$data[] = array("data" => array_values($per), "name" => "");

	}

/*
echo '<pre>';
print_r($data);
echo '</pre>';
//exit;
*/
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Eniten Suunniteltu työvuoro - Kestot'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
            stackLabels: {
                enabled: true,
                align: 'center',
		text: 'Kesto',
            }
    },
    plotOptions: {
            column: {
                stacking: 'normal',
                pointPadding: 0,
                groupPadding: 0,
                dataLabels: {
                    enabled: true,
                    color: 'white'
                }
            }
    },
    tooltip: {
       	split: false,
        shared: true,
        pointFormatter: function() {
		return "<span style='color:{point.color}'></span> " + this.name + ": <b>" + Highcharts.numberFormat(this.y, 2, ",", ".") + "</b><br/>";
        }
    }, 
    series: JSON.parse('<?=json_encode(array_values($data))?>'),
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Eniten Suunniteltu työvuoro - Kestot-->

<!-- Eniten Suunniteltu työvuoro - KPL -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'eniten_tyovuoro_kpl'): ?>
<?php
	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
	}

	$tuote = array();
	for ($i = 1; $i <= $_GET['kpl_maara']; $i++) {
		$per = array();
		foreach($period as $dt) {
			$criteria = new CDbCriteria();
		       	$criteria->group = " kohde ";
		       	$criteria->limit = 1;
		       	$criteria->order = " COUNT(kohde) DESC ";
			$criteria->select = " COUNT(kohde) as l_tunnit, osoite, kohde";
		       	$criteria->condition = "
				kohde!=0
				AND YEAR(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))='".$dt->format( "Y" )."' 
				AND MONTH(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))='".$dt->format( "m" )."'
				AND status=3
				AND peruutettu=0
			";
			if( isset($tuote[$dt->format( "Ym" )]) ){
				foreach($tuote[$dt->format( "Ym" )] as $k=>$v){
					foreach($v as $k1=>$v1){
		       				$criteria->addcondition (" kohde!='".$v1."' ");
					}
				}
			}
			$lasku = Tyovuoroot::model()->findAll($criteria);
			foreach($lasku as $item){
					$osoite = '';
					if( !empty($item->osoite)){ 
						$osoite = $item->osoite; 
					} elseif( isset($item->kohteet->osoite) ){
						$osoite = $item->kohteet->osoite; 
					}
					$per[] = array("name" => $this->clean($osoite), "y" => (int)$item->l_tunnit);
					$tuote[$dt->format( "Ym" )][$item->kohde][] = $item->kohde;
			}

		}
			$data[] = array("data" => array_values($per), "name" => "");

	}

/*
echo '<pre>';
print_r($data);
echo '</pre>';
//exit;
*/
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Eniten Suunniteltu työvuoro - KPL'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
            title: {
            	text: 'KPL'
	    },
            stackLabels: {
                enabled: true,
                align: 'center',
		text: 'KPL',
            }
    },
    plotOptions: {
            column: {
                stacking: 'normal',
                pointPadding: 0,
                groupPadding: 0,
                dataLabels: {
                    enabled: true,
                    color: 'white'
                }
            }
    },
    tooltip: {
       	split: false,
        shared: true,
        pointFormatter: function() {
		return "<span style='color:{point.color}'></span> " + this.name + ": <b>" + this.y + " kpl</b><br/>";
        }
    }, 
    series: JSON.parse('<?=json_encode(array_values($data))?>'),
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Eniten Suunniteltu työvuoro - KPL-->

<?php if(isset($_GET['haku']) and $_GET['haku'] == 'tyontekijat'): ?>
<?php
	$result = array();
	// <-- Luetut
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND deleted=0
	";
	if( isset($_GET['tyontekija']) ){
	$criteria->addCondition(" tid='".$_GET['tyontekija']."' "); 
	}
	$luetut = Mobile::model()->findAll($criteria);
	$data_luetut = array();
	foreach($luetut as $item){
		$data_luetut[date("Ym", strtotime($item->aloitan))] = round($this->num($item->l_tunnit), 2);

	}
	//     Luetut -->

	// <-- Suunnitellut
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y.%m.%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND peruutettu=0
	";
	if( isset($_GET['tyontekija']) ){
	$criteria->addCondition(" tid='".$_GET['tyontekija']."' "); 
	}
	$suunnitellut = Tyovuoroot::model()->findAll($criteria);
	$data_suunnitellut = array();
	foreach($suunnitellut as $item){
		$data_suunnitellut[date("Ym", strtotime($item->pvm))] = round($this->num($item->l_tunnit), 2);

	}
	//     Suunnitellut -->

	// <-- Hyvaksytyt
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND deleted=0
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
	";
	if( isset($_GET['tyontekija']) ){
	$criteria->addCondition(" tid='".$_GET['tyontekija']."' "); 
	}
	$lu = Mobile::model()->findAll($criteria);

	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND status=3
		AND deleted=0
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
	";
	if( isset($_GET['tyontekija']) ){
	$criteria->addCondition(" tid='".$_GET['tyontekija']."' "); 
	}
	$tot = Toteutuneet::model()->findAll($criteria);
	$result = array_merge($lu, $tot);
	$data_hyvaksytyt = array();
	$categories = array();
	foreach($result as $item){
		if( !isset($data_hyvaksytyt[date("Ym", strtotime($item->aloitan))]) ){ 
			$data_hyvaksytyt[date("Ym", strtotime($item->aloitan))] = 0;
		}
		$data_hyvaksytyt[date("Ym", strtotime($item->aloitan))] += round($this->num($item->l_tunnit), 2);
		$categories[date("Ym", strtotime($item->aloitan))] = date("Y", strtotime($item->aloitan)).', '.$months[date("n", strtotime($item->aloitan))];
	}
	//     Hyvaksytyt -->
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: '<?=(isset($_GET["tyontekija"]) and !empty($_GET["tyontekija"]))? $this->etuSukunimi($_GET["tyontekija"]) : ""?>'
    },
    subtitle: {
        text: 'Luetut, hyväksytyt ja suunnitellut tunnit'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
        title: {
            text: 'Tunnit'
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
        name: 'Luetut',
        data: JSON.parse('<?=json_encode(array_values($data_luetut))?>')
    }, 
    {
        name: 'Hyväksytyt',
        data: JSON.parse('<?=json_encode(array_values($data_hyvaksytyt))?>')
    },
    {
        name: 'Suunnitellut',
        data: JSON.parse('<?=json_encode(array_values($data_suunnitellut))?>')
    },],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>

<?php if(isset($_GET['haku']) and $_GET['haku'] == 'onlinevaraukset'): ?>
<?php
	// <-- Onlinevaraus
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE(time)) ";
       	$criteria->select = "
		SUM(hinta) as hinta, t.*
	";
        $criteria->condition = " 
		DATE(time) BETWEEN '".$from."' AND '".$to."'
	";
	if( isset($asiakas->id) ){
	$criteria->addCondition(" asiakas_id ='".$asiakas->id."' "); 
	}
	$onlinevaraus = Onlinevaraus::model()->findAll($criteria);
	$data_onlinevaraus = array();
	$categories = array();
	foreach($onlinevaraus as $item){
		$data_onlinevaraus[] = round((float)$item->hinta, 2);
		$categories[date("Ym", strtotime($item->time))] = date("Y", strtotime($item->time)).', '.$months[date("n", strtotime($item->time))];
	}
	//     Onlinevaraus -->
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: '<?=$from?> - <?=$to?>'
    },
    subtitle: {
        text: 'Onlinevaraukset'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
        title: {
            text: 'Liikevaihto'
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
        name: 'Onlinevaraukset',
        data: JSON.parse('<?=json_encode(array_values($data_onlinevaraus))?>')
    }],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>

<!-- Enitent Tuotteet ja palvelut Euro -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'eniten_tuotteet_per_laskurivi_euro'): ?>
<?php
	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
	}

	$tuote = array();
	for ($i = 1; $i <= 10; $i++) {
		$per = array();
		foreach($period as $dt) {
			$criteria = new CDbCriteria();
		       	$criteria->with = array('tuotteet_palvelut');
		       	$criteria->group = " tuoteID ";
		       	$criteria->limit = " 1 ";
		       	$criteria->order = " SUM(veroton) DESC ";
			$criteria->select = " SUM(veroton) as veroton, tuoteID";
		       	$criteria->condition = "
				tuoteID!=0
				AND lid IN( SELECT id FROM laskut WHERE tilanne!=999 
						AND YEAR(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='".$dt->format( "Y" )."' 
						AND MONTH(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='".$dt->format( "m" )."' 
				)
			";
			if( isset($tuote[$dt->format( "Ym" )]) ){
				foreach($tuote[$dt->format( "Ym" )] as $k=>$v){
					foreach($v as $k1=>$v1){
		       				$criteria->addcondition (" tuoteID!='".$v1."' ");
					}
				}
			}
			$laskur = LaskunRivit::model()->findAll($criteria);
			foreach($laskur as $item){
					$per[] = array(
						"name" => ( isset($item->tuotteet_palvelut->nimike) )?$item->tuotteet_palvelut->nimike:'', 
						"y" => (int)$item->veroton
					);
					$tuote[$dt->format( "Ym" )][$item->tuoteID][] = $item->tuoteID;
			}

		}
			$data[] = array("data" => array_values($per), "name" => "");

	}




/*
echo '<pre>';
print_r($data);
echo '</pre>';
//exit;
*/
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Eniten Tuotteet ja palvelut Euro'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
	    title: {
		text: 'Euro'
	    },
            stackLabels: {
                enabled: true,
                align: 'center',
		text: 'Euro',
            }
    },
    plotOptions: {
            column: {
                stacking: 'normal',
                pointPadding: 0,
                groupPadding: 0,
                dataLabels: {
                    enabled: true,
                    color: 'white'
                }
            }
    },
    tooltip: {
       	split: false,
        shared: true,
        pointFormatter: function() {
		return "<span style='color:{point.color}'></span> " + this.name + ": <b>" + this.y + " euro</b><br/>";
        }
    }, 
    series: JSON.parse('<?=json_encode(array_values($data))?>'),
    exporting: {
        enabled: true
    }
});
//    series: JSON.parse('<?=json_encode($data)?>'),
</script>
<?php endif; ?>
<!-- Enitent Tuotteet ja palvelut euro -->

<script>
$(document).ready(function(){

$('.mult').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Työntekijät"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
});

  $("#yhtveto_tyontekijat").on("submit", function(){
	if( $("#yhtveto-tyontekijat-tyontekija option:selected").val() == 'kaikki' ){
		alert("Valitse työntekijä.");
		return false;
	}
  });
});
</script>
