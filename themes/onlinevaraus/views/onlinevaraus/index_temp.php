<!DOCTYPE html>
<html lang="en">
<head>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
  <link type="text/css" media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_onlinevaraus/css/onlinevaraus_ver2.css"/>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>

<style>
.container{ margin-top: 50px; }
.trash { color:rgb(209, 91, 71); }
.flag { color:rgb(248, 148, 6); }
.panel-body { padding:0px; }
.panel-footer .pagination { margin: 0; }
.panel .glyphicon,.list-group-item .glyphicon { margin-right:5px; }
.panel-body .radio, .checkbox { display:inline-block;margin:0px; }
.panel-body input[type=checkbox]:checked + label { text-decoration: line-through;color: rgb(128, 144, 160); }
.list-group-item:hover, a.list-group-item:focus {text-decoration: none;background-color: rgb(245, 245, 245);}
.list-group { margin-bottom:0px; }
</style>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="text-left"><span class="glyphicon glyphicon-list"></span>Onlinevaraus. Valitse yritys</h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
			<?php foreach($domainit as $item) : ?>
                        <li class="list-group-item">
                            <div class="checkbox">
                                <label for="checkbox5">
				 <?php echo CHtml::link($item->yritys,array('onlinevaraus/index', 'domain' => $item->domain)); ?>
                                </label>
                            </div>
                        </li>
			<?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
