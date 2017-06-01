<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Recover Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-forgot-email">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin([
        'id' => 'password-recover-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
    
    <?= $form->field($model, 'email')->textInput() ?>
    <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className()) ?>
    <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Send link to email', ['class' => 'btn btn-primary btn-round', 'name' => 'login-button']) ?>
             
            </div>
            
        </div>
    <?php ActiveForm::end(); ?>
</div>


