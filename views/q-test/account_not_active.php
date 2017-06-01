<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="account-not-active">

<?php $form=  ActiveForm::begin([
    'id'=>'account_not_active',
    'action'=>'resend-confirmation'
]); ?>
    <h2>Oops, it looks like your account is not activated(</h2>
<?= Html::hiddenInput('email', $email);?>
<?= Html::submitButton('Resend Confirmation Email', ['class' => 'btn btn-primary btn-round', 'name' => 'resend-button']) ?>
<?php ActiveForm::end();?>
</div>


