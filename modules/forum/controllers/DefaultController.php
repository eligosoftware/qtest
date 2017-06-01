<?php

namespace app\modules\forum\controllers;

use yii\web\Controller;


class DefaultController extends Controller{
    //put your code here
    public function actionIndex(){
        return $this->render('index');
    }
}
