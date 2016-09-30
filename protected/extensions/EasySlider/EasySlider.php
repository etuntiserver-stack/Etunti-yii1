<?php
class EasySlider extends CTabView {
    private $jsFile;
    
    public $data = array ();
    public $id = 'slider';
    
    public $width  = '950px';
    public $height = '300px';
    
    public $params = array (
        'auto' => true,
        'continuous' => true,
        'controlsShow' => false,
        'speed' => 1200,
        'pause' => 7500,
    );
    

    public function init() {
        $baseUrl = CHtml::asset (dirname (__FILE__).DIRECTORY_SEPARATOR.'assets');

        # load css
        if($this->cssFile === null) {
            Yii::app()->getClientScript()->registerCssFile    ($baseUrl.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'screen.css');
        }

        # load js
        if($this->jsFile === null) {
            Yii::app()->getClientScript()->registerScriptFile ($baseUrl.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'easySlider1.7.js');
        }

        parent::init();
    }
    
    public function run () {
        echo '<div id="slider-wrap" style="width: '.$this->width.'; height: '.$this->height.'"><div id="'.$this->id.'"><ul>';
        foreach ($this->data as $key => $value) {
            if (isset($value['url']) and $value['url'] != '') {
                echo '<li><a href="'.$value['url'].'"><img src="'.$value['image'].'" alt="'.$value['title'].'"></a></li>';
            } else {
                echo '<li><img src="'.$value['image'].'" alt="'.$value['title'].'"></li>';
            }
        }
        echo '</ul></div></div>';
        
        $config = array ();
        foreach ($this->params as $key => $value) {
            if (is_bool($value) === true) {
                $config[] .= $key.':'.($value ? 'true' : 'false');
            } else {
                $config[] .= $key.':'.$value;
            }
        }
        
        Yii::app()->clientScript->registerScript('eslider', '
$(document).ready(function(){
    $("#'.$this->id.'").easySlider({
        '.implode (',', $config).'
    });
});
        ');
    }
}
?>
