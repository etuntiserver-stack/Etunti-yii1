<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

//echo Yii::app()->user->isGuest ;
//print_r(Yii::app()->user);
?>

<?php
	if(isset(Yii::app()->user->domain))
	echo Yii::app()->user->domain;

	//echo Yii::app()->session['domain'];
?>








<?php 
if(isset(Yii::app()->user->adminID))
{
  if(isset($_SESSION['domain']))
  echo '<h1>Domaini: '.$_SESSION['domain'].'</h1>';

for ($i = 1; $i <= 12; $i++) {


	$criteria = new CDbCriteria;
	$criteria->addCondition('YEAR(time) = '.date("Y"));
	$criteria->addCondition('MONTH(time) = '.$i);
	$result = Sivexkuitti::model()->findAll($criteria);
  

    $res = $result;
    $kk[$i] = count ( $res );
}

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
    
}
?>


<?php if(!isset(Yii::app()->user->adminID)) : ?>
<!--
<div class="row">
 <div class="col-md-12">
   <div class="alert alert-success">
	<b><?php echo Yii::t('main', 'TilaamisenJalkeen'); ?></b>
   </div>
 </div>
</div>
-->
<br>
    <div class="row">
        <div class="col-md-3 col-md-offset-1">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4 class="text-center"><?php echo Yii::t('main', 'Taso 1 ETUNTI'); ?></h4>
                </div>
                <div class="panel-body text-center">
                    <p class="lead">
                        <strong>15&euro; / <?php echo Yii::t('main', 'kk per työntekijä'); ?> </strong>
                    </p>
                </div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työajanseuranta liikuvalle työlle ja Mobiili sovellus'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työvuorosuunnittelu ohjelma'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Asiakkuuksien hallinta ohjelma'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työnjohdon kuntopuntari'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Laskutus ohjelma'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Online varaus'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                </ul>
                <div class="panel-footer">
                    <a class="btn btn-lg btn-block btn-success"><?php echo Yii::t('main', 'Tilaa heti!'); ?></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="text-center"><?php echo Yii::t('main', 'Taso 2 ETUNTI'); ?></h4>
                </div>
                <div class="panel-body text-center">
                    <p class="lead">
                        <strong>30&euro; / <?php echo Yii::t('main', 'kk per työntekijä'); ?></strong>
                    </p>
                </div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työajanseuranta liikuvalle työlle ja Mobiili sovellus'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työvuorosuunnittelu ohjelma'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Asiakkuuksien hallinta ohjelma'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työnjohdon kuntopuntari'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Laskutus ohjelma'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Online varaus'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                </ul>
                <div class="panel-footer">
                    <a class="btn btn-lg btn-block btn-info"><?php echo Yii::t('main', 'Tilaa heti!'); ?></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="text-center"><?php echo Yii::t('main', 'Taso 3 ETUNTI'); ?></h4>
                </div>
                <div class="panel-body text-center">
                    <p class="lead">
                        <strong>50&euro; / <?php echo Yii::t('main', 'kk per työntekijä'); ?></strong>
                    </p>
                </div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työajanseuranta liikuvalle työlle ja Mobiili sovellus'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työvuorosuunnittelu ohjelma'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Asiakkuuksien hallinta ohjelma'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työnjohdon kuntopuntari'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Laskutus ohjelma'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Online varaus'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                </ul>
                <div class="panel-footer">
                    <a class="btn btn-lg btn-block btn-primary"><?php echo Yii::t('main', 'Tilaa heti!'); ?></a>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-3 col-md-offset-1">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4 class="text-center"><?php echo Yii::t('main', 'Taso 4 ETUNTI'); ?></h4>
                </div>
                <div class="panel-body text-center">
                    <p class="lead">
                        <strong>15&euro; / <?php echo Yii::t('main', 'kk per työntekijä'); ?> </strong>
                    </p>
                </div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työajanseuranta liikuvalle työlle ja Mobiili sovellus'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työvuorosuunnittelu ohjelma'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Asiakkuuksien hallinta ohjelma'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työnjohdon kuntopuntari'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Laskutus ohjelma'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Online varaus'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                </ul>
                <div class="panel-footer">
                    <a class="btn btn-lg btn-block btn-success"><?php echo Yii::t('main', 'Tilaa heti!'); ?></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-info">
                <div class="panel-heading">

                    <h4 class="text-center"><?php echo Yii::t('main', 'Taso 5 ETUNTI'); ?></h4>
                </div>
                <div class="panel-body text-center">
                    <p class="lead">
                        <strong>30&euro; / <?php echo Yii::t('main', 'kk per työntekijä'); ?></strong>

                    </p>
                </div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työajanseuranta liikuvalle työlle ja Mobiili sovellus'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työvuorosuunnittelu ohjelma'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Asiakkuuksien hallinta ohjelma'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työnjohdon kuntopuntari'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Laskutus ohjelma'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Online varaus'); ?>
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                </ul>

                <div class="panel-footer">
                    <a class="btn btn-lg btn-block btn-info"><?php echo Yii::t('main', 'Tilaa heti!'); ?></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="text-center"><?php echo Yii::t('main', 'Taso 6 ETUNTI'); ?></h4>
                </div>

                <div class="panel-body text-center">
                    <p class="lead">
                        <strong>50&euro; / <?php echo Yii::t('main', 'kk per työntekijä'); ?></strong>
                    </p>
                </div>

                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työajanseuranta liikuvalle työlle ja Mobiili sovellus'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työvuorosuunnittelu ohjelma'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Asiakkuuksien hallinta ohjelma'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työnjohdon kuntopuntari'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Laskutus ohjelma'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Online varaus'); ?>
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>

                </ul>
                <div class="panel-footer">
                    <a class="btn btn-lg btn-block btn-primary"><?php echo Yii::t('main', 'Tilaa heti!'); ?></a>
                </div>
            </div>

        </div>
    </div>

<?php endif; ?>








