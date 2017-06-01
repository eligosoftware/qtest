<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use yii\base\Model;

/**
 * Description of SignUp
 *
 * @author ilgarrasulov
 */
class SignUp extends Model{
    public $username;
    public $password;
    public $password_confirm;
    public $email;
    public $reCaptcha;
    
    public function rules() {
        return[
            [['username','password','password_confirm','email'],'required'],
            ['reCaptcha','required','message'=>'Verify you\'re not robot'],
            ['email','email'],
            ['email','checkforUnique_email'],
            ['password_confirm','compare','compareAttribute'=>'password','message'=>'Password\s don\'t match'],
            ['username','checkforUnique'],
            [['password','password_confirm'],'string','min'=>7],
//            [['password','password_confirm'],'match','pattern'=>'/?=.*[A-Z]/','message'=>'Password must have at least one capital letter']
        // verifyCode needs to be entered correctly
            ['reCaptcha', \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 
                'secret' => '6Lc0TR8UAAAAAKI5EXIv3MAk5OfYLQZ_KWq5m_Nz']
         ];
    }

    public function checkforUnique($attribute, $params){
        if(!$this->hasErrors()){
            $user= Member::findByUsername($this->username);
            
            if($user){
                $this->addError($attribute, 'Username is taken');
            }
        }
    }
    public function checkforUnique_email($attribute, $params){
        if(!$this->hasErrors()){
            
            $user=  Member::findOne(['email'=>$this->email,'oauth_type'=>'native']);
            
            if($user){
                $this->addError($attribute, 'Email is taken');
            }
        }
    }
    public function signUp()
    {
        if ($this->validate()) {
            
            $member= new Member(['scenario'=>  Member::SCENARIO_REGISTER]);
            $member->load($this->turnToData());
            $member->setPassword($member->password);
            $member->generatePasswordResetToken();
//           
            if($member->save()){
               $message='Thanks for signing up for QTest! Please confirm your account.'; 
               $link="http://localhost/qtest/q-test/confirmation?token=$member->password_reset_token";
               $subject='QTest - Confirmation Email';
               $link_message="Confirm account";
            SignUp::sendEmail($member,$message,$link,$subject,$link_message);
            return true;
           // return \Yii::$app->user->login($member, 3600*24*30);
       }
       return false;
            }
        return false;
    }
    
    public static function  sendEmail($member,$message='',$link='',$subject='',$link_message='link',$template='mail_template'){
              
        \Yii::$app->mailer->compose($template,['username'=>$member->username,'link'=>$link,'message'=>$message,'link_message'=>$link_message])
                ->setFrom([\Yii::$app->params['adminEmail'] =>  \Yii::$app->name.' robot'])
                ->setTo($member->email)
                ->setSubject($subject)
                ->send();
        }
    

    public function turnToData(){
        $data['email']=  $this->email;
        $data['username']=  $this->username;
        $data['password']=   $this->password;
        $data['active']=0;
        
        
        return ['Member'=>$data];
    }
    public function attributeLabels() {
       // $array= parent::attributeLabels();
        return ['reCaptcha' => ''];
        //return $array;
    }
}
