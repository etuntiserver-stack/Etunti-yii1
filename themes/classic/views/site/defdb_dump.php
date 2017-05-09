<?php

exec("/usr/bin/mysqldump -u mulgikapsas -pKristinA1 ".Yii::app()->user->domain." | gzip -c > backup/".Yii::app()->user->domain."/".date("Y-m-d")."_".Yii::app()->user->domain.".sql.gz");

?>
