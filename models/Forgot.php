<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Forgot extends Model{
   public $email;
   public $reCaptcha;
   
   private $_user = false;
   
   public function attributeLabels() {
       return [
           'reCaptcha'=>'',
           'email'=>'Email'
       ];
   }
   public function rules()
    {
        return [
            // password_confirm and password are both required
           // ['email','email'],
            ['reCaptcha','required','message'=>'Verify you\'re not robot'],
            ['email', 'required'],
        ];
    }
    
    public function send_email(){
    
        
            $member=  \app\models\Member::findByUsername($this->email,true);
            if($member!=NULL){
                $member->generatePasswordResetToken();
               // $member->scenario=  \app\models\Member::SCENARIO_LOGIN;
                $member->save();
                $message='Use next link to recover your password ';
                $link="http://localhost/qtest/q-test/recover-email?token=$member->password_reset_token";
                $subject='QTest - Password Recovery Email';
                $link_message="Change password";
                SignUp::sendEmail($member,$message,$link,$subject,$link_message);
                return true;
            }
            else{
                $this->addError('email','Member with such username/email couldn\'t be found.');
                return false;
                
            }
   }
//   public function send_email()
//    {
//        if ($this->validate()) {
//        
//            //change password and sigin in user
//            
//            $member=  Member::findByUsername($this->email);
//            if($member){
//                //$member->scenario=  Member::SCENARIO_LOGIN;
//                $member->generatePasswordResetToken();
//                $member->save();
//                
//                $message=""
//                $link=
//                SignUp::sendEmail($member, $message, $link)
//                
//                return true;
//                
//            }
//            else{
//                throw new \yii\web\BadRequestHttpException();
//            }
//            return false;
//        }
//    }
}
