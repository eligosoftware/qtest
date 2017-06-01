<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Recover Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-recover-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to recover email:</p>
    
    <?php $form = ActiveForm::begin([
        'id' => 'password-recover-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
    
    <?= $form->field($model, 'password')->passwordInput() ?>
    
    <?= $form->field($model, 'password_confirm')->passwordInput() ?>
    
    <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className()) ?>
    <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Reset', ['class' => 'btn btn-primary btn-round', 'name' => 'login-button']) ?>
             
            </div>
            
        </div>
    <?= $form->field($model,'token')->hiddenInput()->label(false)?>
    <?php ActiveForm::end(); ?>
</div>

<<<<<<< HEAD
/* @var $this yii\web\View */
/* @var $form yii\widget\ActiveForm */
/* @var $model app\models\LoginForm */
=======
>>>>>>> 98d87428c61033582fc0da2fce71a681fc86d268

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


?>
