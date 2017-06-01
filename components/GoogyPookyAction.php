<?php

namespace app\components;

use app\controllers\QTestController;
use yii\base\Action;


class GoogyPookyAction extends Action{
    //put your code here
    public function run($name='Sam'){
        $message="Hello Again, $name!";
        
        return $this->controller->render("sayhello",compact('message'));
    }
}
