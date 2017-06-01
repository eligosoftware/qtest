<?php
namespace app\controllers;

use app\models\Country;
use app\models\EntryForm;

use app\models\LoginForm;
use app\models\SignUp;
use Yii;
use yii\web\Controller;

class QTestController extends Controller
{
    public $defaultAction='index';
   // public $layout = 'main_red'; //uncomment this line in order not to use layout
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionSayhello(){
        
        $country= Country::findOne('US');
        
        $country->name="U.S.A";
        $country->save();
        
        $message="Hello World from ".$country->name."!";
        return $this->render('sayhello',  compact('message'));
    }
    public function actionEntry(){
        
        $model=new EntryForm();
        
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            return $this->render('entry-confirm',  compact('model'));
        }
            else {
            return $this->render('entry',  compact('model'));
        }
    }
    
    public function actionConfirmation(){
        $token =  Yii::$app->getRequest()->get('token');
        
        $member=\app\models\Member::findByPasswordResetToken($token);
        
        if($member){
            $member->removePasswordResetToken();
            $member->active=1;
            $member->scenario=  \app\models\Member::SCENARIO_LOGIN;
            $member->save();
            
            $message="Your account is successfully confirmed!";
        }
        else{
            $message="Something went wrong, please request account confirmation again";
        }
        return $this->render('account_confirm_result',  compact('message'));
    }

        public function actionHelloWorld(){
        $message="Hello World!";
        return $this->render('hello-world',  compact('message'));
    }
    
    public function actionLogin(){
         if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm;
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        if(array_key_exists('not_active',$model->getErrors())){
           
            return $this->render('account_not_active', [
            'email' =>$model->username,
        ]);
            
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    
    
        public function actionForgot($email=''){
            
            $model = new \app\models\Forgot();
            
             if ($model->load(Yii::$app->request->post()) && $model->send_email()) {
                 
            \Yii::$app->getSession()->setFlash('forgot_email_mail_sent', 'Message with recovery info is sent to your email');
                 
            return $this->goHome();
        }
        else {
            $model->email=$email;
            return $this->render('forgot_email',  compact('model'));
        }   
        }
        
        
       public function actionRecoverEmail($token=''){ 
           
           if(!$token || !\app\models\Member::findByPasswordResetToken($token))
           {
               throw new \yii\web\BadRequestHttpException();
           }
           
           $model = new \app\models\PasswordRecovery();
        
        if ($model->load(Yii::$app->request->post()) && $model->recover()) {
            return $this->goHome();
        }
        else {
            $model->token=$token;
            return $this->render('password_recovery',  compact('model'));
        }
  
    }
    
    public function actionSignUp(){
         if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignUp;
        if ($model->load(Yii::$app->request->post()) && $model->signUp()) {
          //  return $this->goBack();
        
            return $this->redirect('page?view=mail_sent&email='.$model->email.'&result=success');
            //  return $this->render('q-test/view-action',['view'=>'mail_sent','email'=>$model->email,'result'=>'success']);
        }
        return $this->render('sign-up', [
            'model' => $model,
        ]);
    }
    



    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    
    public function actionResendConfirmation(){
        $email=Yii::$app->getRequest()->post('email');
        if($email){
            //regenerate token and send
            $member=  \app\models\Member::findByUsername($email);
            if($member!=NULL){
                $member->generatePasswordResetToken();
                $member->scenario=  \app\models\Member::SCENARIO_LOGIN;
                $member->save();
                
               $message='Thanks for signing up for QTest! Please confirm your account.'; 
               $link="http://localhost/qtest/q-test/confirmation?token=$member->password_reset_token";
               $subject='QTest - Confirmation Email';
               $link_message='Confirm account';
                SignUp::sendEmail($member,$message,$link,$subject,$link_message);
                return $this->redirect(['page','view'=>'mail_sent','email'=>$member->email,'result'=>'success']);
            }
            else{
                return $this->redirect(['page','view'=>'mail_sent','email'=>null,'result'=>'fail']);
            }
        }
        else{
            //error
            return $this->redirect(['page','view'=>'mail_sent','email'=>null,'result'=>'fail','view'=>'mail_sent']);
        }
    }

    public function actions() {
        return  [
            'googy'=>'app\components\GoogyPookyAction',
            'page'=>[
                'class'=>'yii\web\ViewAction']
            ,
            'auth'=>[
                'class'=>'yii\authclient\AuthAction',
                'successCallback'=>[$this,'successCallback'],
            ]
           ];
    }
    
    public function successCallback($client)
{
       
    $attributes = $client->getUserAttributes();
        // user login or signup comes here
        /*
        Checking facebook email registered yet?
        Maxsure your registered email when login same with facebook email
        die(print_r($attributes));
        */
        
        //$user = \common\modules\auth\models\User::find()->where([’email’=>$attributes[’email’]])->one();
//        $user=  \app\models\Member::find()->where([['email']=>$attributes['email']])->one();
        
    
    $oauth_type=(new \ReflectionClass($client))->getShortName();
    
    if($oauth_type=='Google'){
        $email=$attributes['emails'][0]['value'];
        $gattributes['name']=$attributes['displayName'];
        $gattributes['email']=$email;
        $gattributes['id']=$attributes['id'];
        $attributes=$gattributes;
        
    }
    elseif ($oauth_type=='Facebook'){
        $email=$attributes['email'];
    }
    $user= \app\models\Member::findOne(['email'=>$attributes['email'],'oauth_type'=>$oauth_type]);
        if(!empty($user)){
            Yii::$app->user->login($user);
        
        }else{
            
            $user=\app\models\Member::registerOAuthUser($attributes,$oauth_type);
            Yii::$app->user->login($user);
// Save session attribute user from FB
//            $session = Yii::$app->session;
//            $session['attributes']=$attributes;
            // redirect to form signup, variabel global set to successUrl
            $this->successUrl = \yii\helpers\Url::to(['q-test/index']);
        }
}
public $successUrl = 'Success';
}
?>

