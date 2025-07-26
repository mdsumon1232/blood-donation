<?php 

require_once('./userExit.php');

   class User{

    private $conn;

    private $name;
    private $email;
    private $phone;
    private $permanentAddress;
    private $currentAddress;
    private $bloodGroup;
    private $gender;
    private $role;
    private $lastDonate;
    private $profile;
    
    private $password;
    private $Referral;

    private $status = 0;

    private $insertUser;



    public function __construct($conn){
        $this -> conn = $conn;
    }

    public function Signup($name, $email,$phone,$PA,$CA,$BG,$gender,$role,$LD,$profile,$password,$Referral){
      
        $this -> name = $name;
        $this -> email = $email;
        $this -> phone = $phone;
        $this -> permanentAddress = $PA;
        $this -> currentAddress = $CA;
        $this -> bloodGroup = $BG;
        $this -> gender = $gender;
        $this -> role = $role;
        $this -> lastDonate = $LD;
        $this -> profile = $profile;
        $this -> password = $password;
        $this -> Referral = $Referral;
      
        $userExit = new UserExit($this -> conn);

        if($userExit -> dataExit($email , $phone) === false ){
         echo "This email and phone number already exit";
        }else{
         
        $this -> insertUser= $this-> conn -> prepare("INSERT INTO user (fullName,email,phone,currentAddress,permanentAddress,bloodGroup,gender,role,referralCode,	status,lastDonate,profileImage,	password) 
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?) ");

        $this -> insertUser -> bind_param('sssssssssisss', $this -> name,$this->email,$this->phone,$this->permanentAddress,$this->currentAddress,$this->bloodGroup,
                                          $this ->gender, $this -> role , $this ->  Referral,$this -> status , $this -> lastDonate, $this -> profile , $this -> password                                  
     );

     if($this -> insertUser -> execute()){
        return "Registration successfully";
     }else{
        return "something wrong try again";
     }

  }


    }

   }



?>