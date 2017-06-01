<?php


namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;

class HelloWidget extends Widget{    
    public function init() {
        parent::init();
        
        ob_start();
    }
    
    public function getViewPath() {
        return "@app/components/hello/views";
    }
    public function run() {
        $content=  ob_get_clean();
        return $this->render('hell');
        return Html::encode($content.'!');
    }
}
