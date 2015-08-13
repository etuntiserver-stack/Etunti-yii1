<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

?>

<?php
	if(isset(Yii::app()->user->domain))
	echo Yii::app()->user->domain;

	//echo Yii::app()->session['domain'];
?>








<?php 
if(isset(Yii::app()->user->adminID))
{

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
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4 class="text-center">Basic paketti</h4>
                </div>
                <div class="panel-body text-center">
                    <p class="lead">
                        <strong>10&euro; / kk</strong>
                    </p>
                </div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">
                        Personal Use
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        Single Commercial License
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        Multiple site Commercial license
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        Technical Support
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                </ul>
                <div class="panel-footer">
                    <a class="btn btn-lg btn-block btn-success">Osta heti</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="text-center">Standard paketti</h4>
                </div>
                <div class="panel-body text-center">
                    <p class="lead">
                        <strong>30&euro; / kk</strong>
                    </p>
                </div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">
                        Personal Use
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        Single Commercial License
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        Multiple site Commercial license
                        <span class="glyphicon glyphicon-remove pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        Technical Support
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                </ul>
                <div class="panel-footer">
                    <a class="btn btn-lg btn-block btn-info">Osta heti</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="text-center">Premium paketti</h4>
                </div>
                <div class="panel-body text-center">
                    <p class="lead">
                        <strong>50&euro; / kk</strong>
                    </p>
                </div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">
                        Personal Use
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        Single Commercial License
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        Multiple site Commercial license
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                    <li class="list-group-item">
                        Technical Support
                        <span class="glyphicon glyphicon-ok pull-right"></span>
                    </li>
                </ul>
                <div class="panel-footer">
                    <a class="btn btn-lg btn-block btn-primary">Osta heti</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>








