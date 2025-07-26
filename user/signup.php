<?php 
    
    require_once('userscript.php');
    require_once("../config/db.php");

    $user = new User($conn);

    $message  = "";

   if($_SERVER['REQUEST_METHOD'] === "POST"){
      if(isset($_POST['submit'])){

        $fullname =htmlspecialchars($_POST['fullName']);
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $currentAddress = htmlspecialchars($_POST['currentAddress']);
        $permanentAddress = htmlspecialchars($_POST['permanentAddress']);
        $bloodGroup = htmlentities($_POST['bloodGroup']);
        $gender = htmlspecialchars($_POST['gender']);
        $role = htmlspecialchars($_POST['role']);
        $referralCode = htmlspecialchars($_POST['referralCode']);
        $lastDonate = htmlspecialchars($_POST['lastDonate']);
        $file = $_FILES['profileImage'];
        $password = htmlspecialchars($_POST['password']);
        $confirmPassword = $_POST['confirmPassword'];


    if(preg_match("/^[a-zA-Z0-9._%-+]+@[a-zA-z]+\.[a-zA-Z]{2,}$/i" , $email)){
       if(preg_match("/(\+88)?+01[0-9]{9,}/" , $phone)){
        
        $today = strtotime( date("m/d/y"));
          if(strtotime($lastDonate) <= $today){
            if($password === $confirmPassword){
              if(preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%^&*_\-+.])[a-zA-Z0-9@#$%^&*_\-+.]{8,}$/" , $password)){
                
                if($file['error'] === 0){
                   $imageUpload = '../uploads/';
                    if(!is_dir($imageUpload)){
                        mkdir($imageUpload, 0755, true);
                    }else{
                        $allowedFile = array('image/jpg' , 'image/png' , 'image/jpeg');
                        $imageMime = mime_content_type($file['tmp_name']);
                        if(in_array($imageMime , $allowedFile)){
                             $fileUniqName  = uniqid(). '_'. $file['name'];
                             $fileUploadedPath = $imageUpload . $fileUniqName;
                             if(move_uploaded_file($file['tmp_name'] , $fileUploadedPath)){
                                chmod($fileUploadedPath , 0644);
                              
                                $encryptPassword = password_hash($password , PASSWORD_DEFAULT);

                                $user -> Signup($fullname , $email , $phone,$permanentAddress,$currentAddress,$bloodGroup,$gender ,$role,$lastDonate,$fileUploadedPath,$encryptPassword,$referralCode);

                             }else{
                                $message = "file is not uploaded";
                             }
                        }else{
                            $message = "Please select valid image";
                        }
                    }
                }else{
                    $message = "Please try again";
                }

              }else{
                $message = "Use a strong password";
              }
            }else{
                $message = "Password is not match";
            }
          }else{
             $message = "Input a valid donate date";
          }

       }else{
          $message = "input a valid mobile number";
       }
    }else{
        $message = "Input a valid email";
    }
        


      
    }}


?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registration Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 py-10">

    <div class="max-w-3xl mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-bold mb-6 text-center">User Registration</h2>
        <form id="registrationForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data"
            method="post">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Full Name -->
                <div>
                    <label class="block mb-1 font-medium">Full Name</label>
                    <input type="text" name="fullName" class="w-full border px-3 py-2 rounded" required />
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-1 font-medium">Email</label>
                    <input type="email" name="email" class="w-full border px-3 py-2 rounded" required />
                </div>

                <!-- Phone -->
                <div>
                    <label class="block mb-1 font-medium">Phone</label>
                    <input type="text" name="phone" class="w-full border px-3 py-2 rounded" required />
                </div>

                <!-- Current Address -->
                <div>
                    <label class="block mb-1 font-medium">Current Address</label>
                    <input type="text" name="currentAddress" class="w-full border px-3 py-2 rounded" required />
                </div>

                <!-- Permanent Address -->
                <div>
                    <label class="block mb-1 font-medium">Permanent Address</label>
                    <input type="text" name="permanentAddress" class="w-full border px-3 py-2 rounded" required />
                </div>

                <!-- Blood Group -->
                <div>
                    <label class="block mb-1 font-medium">Blood Group</label>
                    <select name="bloodGroup" class="w-full border px-3 py-2 rounded" required>
                        <option value="">Select</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>

                <!-- Gender -->
                <div>
                    <label class="block mb-1 font-medium">Gender</label>
                    <select name="gender" class="w-full border px-3 py-2 rounded" required>
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Role -->
                <div>
                    <label class="block mb-1 font-medium">Role</label>
                    <select name="role" id="roleSelect" class="w-full border px-3 py-2 rounded" required>
                        <option value="">Select</option>
                        <option value="donar">Donor</option>
                        <option value="volunteer">volunteer</option>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Super Admin</option>
                    </select>
                </div>

                <!-- Referral Code (hidden by default) -->
                <div id="referralField" class="hidden">
                    <label class="block mb-1 font-medium">Referral Code</label>
                    <input type="text" name="referralCode" class="w-full border px-3 py-2 rounded" />
                </div>

                <!-- Last Donate Date -->
                <div>
                    <label class="block mb-1 font-medium">Last Donate Date</label>
                    <input type="date" name="lastDonate" class="w-full border px-3 py-2 rounded" />
                </div>

                <!-- Profile Image -->
                <div>
                    <label class="block mb-1 font-medium">Profile Image</label>
                    <input type="file" name="profileImage" class="w-full border px-3 py-2 rounded" />
                </div>

                <!-- Password -->
                <div>
                    <label class="block mb-1 font-medium">Password</label>
                    <input type="password" name="password" class="w-full border px-3 py-2 rounded" required />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block mb-1 font-medium">Confirm Password</label>
                    <input type="password" name="confirmPassword" class="w-full border px-3 py-2 rounded" required />
                </div>

            </div>

            <button type="submit" name="submit"
                class="mt-6 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Register</button>
        </form>
    </div>


    <?php echo $message ?>

    <script src="../assets/js/signup.js"></script>

</body>

</html>