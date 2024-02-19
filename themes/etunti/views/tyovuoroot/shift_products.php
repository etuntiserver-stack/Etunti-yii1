<?php
ini_set('memory_limit', '512M');
ini_set("max_execution_time", "180");

$site = Yii::app()->createController('Site');
//var_dump($shifts);
?>


<!-- begin: .tray-center -->
<div class="tray-center">


    <h2 class="myBgColors p10"><i class="fa fa-calendar-check-o"></i> <?=Yii::t('main', 'Työvuorot tuotteittain')?></h2>


    <div class="admin-form">
        <div class="panel heading-border">
            <div class="panel-body bg-light">
                <div class="pull-right">
                    <form action="<?=Yii::app()->request->baseUrl?>/index.php/mobile/tulostus" class="form-group"
                        target="_blank" method="POST">
                        <input type="hidden" name="excel_list" value="true">
                        <input type="hidden" name="ext" value="xls">
                        <textarea name="html_content" class="form-control" style="display:none"></textarea>
                        <button type="submit" class="btn btn-primary myBgColors submitForm"><i
                                class="fa fa-file-excel-o" aria-hidden="true"></i></button>
                    </form>
                </div>
                <form id="mobForm" action="#" class="form-inline" method="GET">
                    <input type="hidden" name="mob_hae">



                    <!-- Input Icons -->
                    <div class="row">

                        <div class="col-md-2">
                            <div class="section">
                                <label>Osoite</label>
                                <label class="field prepend-icon">

                                    <!-- Autocomplete -->
                                    <?php
										$site = Yii::app()->createController('Site');
										$mod = 'Asiakkaat';
										$sarake = 'osoite';
										$placeholder = 'Osoite';
										if(isset($_GET[$sarake])) $postvalue = $_GET[$sarake]; else $postvalue='';
											$site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
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
                                <label>Asiakas</label>
                                <label class="field prepend-icon">
                                    <!-- Autocomplete -->
                                    <?php
									$site = Yii::app()->createController('Site');
									$mod = 'Asiakkaat';
									$sarake = 'yrityksen_nimi';
									$placeholder = 'Asiakas';
									if(isset($_GET[$sarake])) 			
										$postvalue = $_GET[$sarake]; 
									else 
										$postvalue = '';				
										$site[0]->autocompleteFor($mod, array('yrityksen_nimi', 'etunimi', 'sukunimi'), $placeholder, $postvalue);
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
                                <label>Tyontekija</label>
                                <label class="field prepend-icon">

                                    <!-- Autocomplete -->
                                    <?php
									$site = Yii::app()->createController('Site');
									$mod = 'Tyontekijat';
									$sarake = 'tekijan_nimi';
									$placeholder = 'Nimi';
									if(isset($_GET[$sarake])) 			
										$postvalue = $_GET[$sarake]; 
									else 
										$postvalue = '';
										$site[0]->autocompleteFor($mod, array('tekijan_nimi', 'sukunimi'), $placeholder, $postvalue);
									?>
                                    <!-- Autocomplete -->

                                    <label for="firstname" class="field-icon">
                                        <i class="fa fa-user"></i>
                                    </label>
                                </label>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label>Alku</label>
                            <div class="section">
                                <label class="field prepend-icon">

                                    <input type="text" name="alkaen" class="gui-input datepickerFI"
                                        value="<?php if(isset($_GET['alkaen'])) echo date('d.m.Y', strtotime($_GET['alkaen'])); ?>">
                                    <label for="firstname" class="field-icon">
                                        <i class="fa fa-calendar"></i>
                                    </label>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label>Loppu</label>
                            <div class="section">
                                <label class="field prepend-icon">

                                    <input type="text" name="loppuen" class="gui-input datepickerFI"
                                        value="<?php if(isset($_GET['loppuen'])) echo date('d.m.Y', strtotime($_GET['loppuen'])); ?>">
                                    <label for="firstname" class="field-icon">
                                        <i class="fa fa-calendar"></i>
                                    </label>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="section">
                                <label>Tuote</label>
                                <label class="field prepend-icon">

                                    <!-- Autocomplete -->
                                    <?php
									$site = Yii::app()->createController('Site');
									$mod = 'TuotteetPalvelut';
									$sarake = 'nimike';
									$placeholder = 'Tuotteen nimi';
									if(isset($_GET[$sarake])) 			
										$postvalue = $_GET[$sarake]; 
									else 
										$postvalue = '';
										$site[0]->autocompleteFor($mod, array('nimike'), $placeholder, $postvalue);
									?>
                                    <!-- Autocomplete -->

                                    <label for="firstname" class="field-icon">
                                        <i class="fa fa-user"></i>
                                    </label>
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="section">
                                <br>

                                <button type="submit"
                                    class="btn btn-primary btn-lg haemob btn-block myBgColors"><?php echo Yii::t('main', 'Hae'); ?></button>

                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <script type="text/javascript">
    $(document).ready(function() {

        $(".submitForm").on('click', function(e) {
            $(this).prev('textarea').val($('#tableContent').html());
            $(this).closest('form').submit();
            e.preventDefault();
        });


    });
    </script>


    <!-- loppu: .tray-center -->
</div>

<div class="admin-form">
    <div class="panel heading-border">
        <div class="panel-body">
            <div class="row">
                <div class="table-responsive" id="tableContent">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>
                                    Tuote
                                </th>
                                <th>
                                    Työntekijä
                                </th>
                                <th>
                                    Osoite
                                </th>
                                <th>
                                    Kesto (suunniteltu)
                                </th>
                                <th>
                                    Aloitus aika
                                </th>
                                <th>
                                    Lopetus aika
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($shifts as $shift): ?>
                            <?php 
								$seconds = strtotime($shift->loppu) - strtotime($shift->alku);
								$duration = round($seconds/60/60, 2);
							?>
                            <tr data-id="<?=$shift->id?>">
                                <td><?=isset($shift->tp) ? $shift->tp->nimike : "Ei tuotetta" ?></td>
                                <td><?=isset($shift->tt) ? $shift->tt->tekijan_nimi : "Ei työntekijää" ?></td>
                                <td><?=isset($shift->kohteet) ? $shift->kohteet->osoite : "Ei kohdetta" ?></td>
                                <td><?=$duration?>h</td>
                                <td><?= $shift->alku . " " . $shift->pvm ?></td>
                                <td><?= $shift->loppu . " " . $shift->pvm ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>