<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Forgot password';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="account-not-active">

<?php $form=  ActiveForm::begin([
    'id'=>'send-email-forgot-form',
    'action'=>'send-email-forgot'
]); ?>
<?= Html::hiddenInput('email', $email);?>
<?= Html::submitButton('Sent token to email', ['class' => 'btn btn-primary btn-round', 'name' => 'send-button']) ?>
<?php ActiveForm::end();?>
</div>


