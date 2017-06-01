<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs(
    "$('#username').on('input', function() {  var origin=$(\"#forgot_link\").attr(\"href\"); "
        ."var n = origin.indexOf(\"?email=\"); "
        . "var link_part=origin.substring(0,n+7);  "
        . "var username= $(\"#username\").val(); "
        . "var result=link_part.concat(username); "
        . "$(\"#forgot_link\").attr(\"href\",result); "
        . "});",
     yii\web\View::POS_READY,
      'username_on_change'
);
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true,'id'=>'username']) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

    
        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label} &nbsp;&nbsp;&nbsp;".Html::a('Forgot password',['q-test/forgot','email'=>''],['id'=>'forgot_link'])."</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]); ?>
        
  
        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-round', 'name' => 'login-button']) ?>
             or <?= Html::a('Sign Up', ['q-test/sign-up'])?>
            </div>
            
      
             
        </div>
    
    <h3>Login via social networks:<br/>
    </h3> 
          <?= yii\authclient\widgets\AuthChoice::widget([
     'baseAuthUrl' =>['q-test/auth']        
    ]) ?>    

    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;">
        You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong>.<br>
        To modify the username/password, please check out the code <code>app\models\User::$users</code>.
    </div>
</div>
