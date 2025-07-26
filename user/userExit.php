<?php


class UserExit{

    private $conn;
     private $email;
     private $phone;

   public function __construct($conn){
      $this -> conn = $conn;

    }
    
    public function dataExit($email , $phone){
        
        $this -> email = $email;
        $this -> phone = $phone;

        $dataExit = $this -> conn -> prepare("SELECT email , phone FROM user WHERE email=? OR phone=?");
        $dataExit -> bind_param('ss' , $this -> email , $this -> phone);
        $dataExit ->execute();

        $result = $dataExit -> get_result();

        if($result -> num_rows > 0){
            return false;
        }else{
            return true;
        }

    }
    

    }






?>