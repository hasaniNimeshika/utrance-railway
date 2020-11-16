<?php

include_once "../classes/core/Model.php";
include_once "HandlerFactory.php";
include_once "../middlewares/FormValidation.php";
include_once "../middlewares/Sanitization.php";


class UserModel extends Model
{
    public $first_name;
    public $last_name;
    public $street_line1;
    public $street_line2;
    public $city;
    public $contact_num;
    public $email_id;
    public $user_role = "user";
    public $user_password;
    public $user_active_status = 1;
    public $user_confirm_password;
    public $searchUserByNameOrId;
    public $user_new_password;
    public $photo = "Chris-user-profile.jpg";

    public $resultArray;
    public $detailsArray;
    public $defaultPassword;
    public $id;
    private $errorArray = [];
    private $registerSetValueArray = [];
    public $array=[];

    public $PasswordResetToken;


    private function populateValues() {
        
        return ['first_name' => $this->first_name, 'last_name' => $this->last_name, 'street_line1' => $this->street_line1, 'street_line2' => $this->street_line2, 'city' => $this->city, 'contact_num' => $this->contact_num, 'email_id' => $this->email_id, 'user_role' => $this->user_role, 'user_password' => $this->user_password, 'user_active_status' => $this->user_active_status ];
    }

    public static function getUser($id) {
        $query = App::$APP->db->pdo->prepare("SELECT * FROM users WHERE id=:id");
        $query->bindValue(":id", $id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public function findUser($category) {
        $findUser = new HandlerFactory();
        if($category === 'email_id') return $findUser->getOne('users', $category, $this->email_id);
        if($category === 'PasswordResetToken') return $findUser->getOne('users', $category, $this->PasswordResetToken);

    }

    public function register(){ 
       
        $array=['first_name'=> $this->first_name,'last_name'=>$this->last_name,'street_line1' => $this->street_line1,'street_line2' => $this->street_line2,'city'=>$this->city,'contact_num' => $this->contact_num,'email_id' => $this->email_id,'user_password' => $this->user_password,'user_confirm_password' => $this->user_confirm_password];
        $registerValidation=new FormValidation();
        $validationState=$registerValidation->runValidators($array);
        if($validationState ==="success"){
            $this->runSanitization();
            $createUser = new HandlerFactory();
            return $createUser->createOne('users', $this->populateValues());
         }else{
            return $validationState;
         }
       

        
    }

    public function updateMyProfile() { 
        $array=['id'=>$this->id,'first_name'=> $this->first_name,'last_name'=>$this->last_name,'street_line1' => $this->street_line1,'street_line2' => $this->street_line2,'city'=> $this->city,'contact_num' => $this->contact_num,'email_id' => $this->email_id];
        var_dump("hello");
        $updateValidation=new FormValidation();
        $validationState=$updateValidation->runUpdateValidators($array);
        
        if ($validationState ==="success") {
           $this->runSanitization();
           $updateUser = New HandlerFactory();
           
           return $updateUser->updateOne('users', 'id', $this->id, $this->populateValues());
        }else{
            return $validationState;
        }
        
    }


    public function updatePassword(){
        $array=['user_password' => $this->user_password,'user_confirm_password' => $this->user_confirm_password,'user_new_password' => $this->user_new_password,'email_id' => $this->email_id];
        $updatePassword=new FormValidation();
        var_dump($array);
        $validationState=$updatePassword->runPasswordUpdateValidation($array);
       // var_dump($validationState);
        
        
        if($validationState === "success"){
            $this->sanitizeFormPassword($this->user_new_password);
            $this->user_password=$this->user_new_password;
            $this->passwordHashing();
            $query = App::$APP->db->pdo->prepare("UPDATE users SET user_password=:up WHERE email_id=:email");
            $query->bindValue(":up", $this->user_password);
            $query->bindValue(":email", $this->email_id);
            $query->execute();
            return 'success';

        }
        return $validationState;
    }


    public function registerSetValue($registerSetValueArray)
    { //Ashika
        if (empty($registerSetValueArray['firstNameError'])) {
            $registerSetValueArray['first_name'] = $this->first_name;
        }
        if (empty($registerSetValueArray['lastNameError'])) {
            $registerSetValueArray['last_name'] = $this->last_name;
        }
        if (empty($registerSetValueArray['streetLine1Error'])) {
            $registerSetValueArray['street_line1'] = $this->street_line1;
        }

        if (empty($registerSetValueArray['streetLine2Error'])) {
            $registerSetValueArray['street_line2'] = $this->street_line2;
        }

        if (empty($registerSetValueArray['contactNumError'])) {
            $registerSetValueArray['contact_num'] = $this->contact_num;
        }
        if (empty($registerSetValueArray['email_id_error'])) {
            $registerSetValueArray['email_id'] = $this->email_id;
        }
        if (empty($registerSetValueArray['cityError'])) {
            $registerSetValueArray['city'] = $this->city;
        }

        if (empty($registerSetValueArray['passwordError'])) {
            $registerSetValueArray['user_password'] = $this->user_password;
        }

        if (empty($registerSetValueArray['passwordMatchError'])) {
            $registerSetValueArray['user_password'] = $this->user_password;
        }
        return $registerSetValueArray;

    }

    public function getUpdateUserDetails() ////Ashika 

    {
        $this->resultArray['first_name'] = $this->first_name;
        $this->resultArray['last_name'] = $this->last_name;
        $this->resultArray['street_line1'] = $this->street_line1;
        $this->resultArray['street_line2'] = $this->street_line2;
        $this->resultArray['contact_num'] = $this->contact_num;
        $this->resultArray['city'] = $this->city;
        $this->resultArray['email_id'] = $this->email_id;
        return $this->resultArray;
    }

    // asindu - sanitization

    private function runSanitization(){
        $this->first_name = $this->sanitizeFormUsername($this->first_name);
        $this->last_name = $this->sanitizeFormUsername($this->last_name);
        $this->email_id = $this->sanitizeFormEmail($this->email_id);
        $this->street_line1 = $this->sanitizeFormStreet($this->street_line1);
        $this->street_line2 = $this->sanitizeFormStreet($this->street_line2);
        $this->city=$this->sanitizeFormString($this->city);
        $this->contact_num=$this->sanitizeContactNumber($this->contact_num);
        $this->user_password = $this->sanitizeFormPassword($this->user_password);
        $this->passwordHashing();
    }

     private function sanitizeFormString($inputText) //Asindu

     {
        $inputText = strip_tags($inputText); //remove html tags
        $inputText = str_replace(" ", "", $inputText); // remove white spaces
        $inputText = strtolower($inputText);
        $inputText=trim($inputText); // lowering the text
        return ucfirst($inputText); // capitalize first letter
    }

    private function sanitizeFormStreet($inputText){
        $inputText = strip_tags($inputText);
        $inputText = strtolower($inputText); // lowering the text
        $inputText=trim($inputText); 
        return ucfirst($inputText); // capitalize first letter
  
    }

    private function sanitizeContactNumber($inputText){
        $inputText = strip_tags($inputText);
        $inputText=trim($inputText);
        return $inputText; 
    }

    private function sanitizeFormUsername($inputText){//Asindu
        $inputText = strip_tags($inputText); //remove html tags
        $inputText = ucfirst($inputText);
        $inputText=trim($inputText); 
        return str_replace(" ", "", $inputText); // remove white spaces
    }

    private function sanitizeFormPassword($inputText){
        $inputText=trim($inputText); 
        return strip_tags($inputText); //remove html tags
    }

    private function sanitizeFormEmail($inputText){//Asindu
        $inputText = strip_tags($inputText); //remove html tags
        $inputText=trim($inputText); 
        return str_replace(" ", "", $inputText); // remove white spaces
    }

    private function passwordHashing()
    {
        $this->user_password = password_hash($this->user_password, PASSWORD_BCRYPT);
    }

    

    public function createPasswordResetToken()
    {
        $resetToken = bin2hex(random_bytes(32));
        $resetTokenEncrypted = hash('sha256', $resetToken);
        $query = App::$APP->db->pdo->prepare("UPDATE users SET PasswordResetToken=:prt WHERE email_id=:email");
        $query->bindValue(":prt", $resetTokenEncrypted);
        $query->bindValue(":email", $this->email_id);
        $query->execute();
        $date = new DateTime();
        $date->add(new DateInterval('PT10M'));
        $date = $date->format('Y-m-d H:i:s');
        $query = App::$APP->db->pdo->prepare("UPDATE users SET PasswordResetExpires=:pre WHERE email_id=:email");
        $query->bindValue(":pre", $date);
        $query->bindValue(":email", $this->email_id);
        $query->execute();
        return $resetToken;
    }

    /*public function updatePassword()
    {
        $this->validatePassword($this->user_password, $this->user_confirm_password);
        if (empty($this->errorArray)) {
            $this->sanitizeFormPassword($this->user_password);
            $this->passwordHashing();
            $query = App::$APP->db->pdo->prepare("UPDATE users SET user_password=:up WHERE email_id=:email");
            $query->bindValue(":up", $this->user_password);
            $query->bindValue(":email", $this->email_id);
            $query->execute();
            return 'success';
        }
        return $this->errorArray;
    }*/

}
