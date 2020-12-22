<?php
include("config.php");
?>
<?php
function filterFirstName($field){                                                                                     // Functions to filter user inputs
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);                                                  // Sanitize user name
     if(filter_var($field, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){      // Validate user name
        return $field;
    } else{
        return FALSE;
    }
} 
function filterLastName($field){                                                                                     // Functions to filter user inputs
    $field = filter_var(trim($field), FILTER_SANITIZE_STRING);                                                  // Sanitize user name
     if(filter_var($field, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){      // Validate user name
        return $field;
    } else{
        return FALSE;
    }

}  
function filterAge($field){
 $field = filter_var(trim($field), FILTER_SANITIZE_NUMBER_INT);
  $min=18;
  $max=60 ;
   if (filter_var($field, FILTER_VALIDATE_INT, array("options" => array("min_range"=>$min, "max_range"=>$max))) === false) {
 return FALSE;
} else{
    return $field;
}

} 
function filterEmail($field){
    $field = filter_var(trim($field), FILTER_SANITIZE_EMAIL);                                                   // Sanitize e-mail address
    if(filter_var($field, FILTER_VALIDATE_EMAIL)){                                                              // Validate e-mail address
        return $field;
    } else{
        return FALSE;
    }
}

function validating($field){
   $field = filter_var($field, FILTER_SANITIZE_NUMBER_INT); 
if(preg_match('/^[0-9]{10}+$/', $field)) {
return $field;
}else{
return FALSE;
}
}

$firstnameErr = $lastnameErr =$ageErr = $emailErr = $phoneErr= $pErr=$pcErr=$imgErr= "";
$firstname = $lastname = $age = $email= $phone= $password = $cpassword = $img="";
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate first name
    if(empty($_POST["fname"])){
        $firstnameErr = "Please enter your first name.";
    } else{
        $firstname = filterFirstName($_POST["fname"]);
        if($firstname == FALSE){
            $firstnameErr = "Please enter a valid name.";
        }
    }
    if(empty($_POST["lname"])){
        $lastnameErr = "Please enter your last name.";
    } else{
        $lastname = filterLastName($_POST["lname"]);
        if($lastname == FALSE){
            $lastnameErr = "Please enter a valid name.";
        }
    }

    //validate age
    if(empty($_POST["age"])){
        $ageErr = "Please enter your age.";
    }else{
        $age=filterAge($_POST["age"]);
         if($age == FALSE){
            $ageErr = "Please enter a valid age.";
        }
    }

    if(empty($_POST["email"])){
        $emailErr = "Please enter your email address.";     
    } 
       else{ $email = filterEmail($_POST["email"]);
        if($email == FALSE){
            $emailErr = "Please enter a valid email address.";
        }
    
       else{     $s="select * from formentry where emailid= '$email'";
    $res=mysqli_query($conn,$s);
    $num=mysqli_num_rows($res);
    if($num==1){
       $emailErr="email already exist"; 
    }
}
}

    
    if(empty($_POST["phone"])){
        $phoneErr = "Please enter 10 digit phone number.";     
    } else{
        $phone = validating($_POST["phone"]);
        if($phone == FALSE){
            $phoneErr = "Please enter a valid phone number.";
        }
    }

    if(empty($_POST["password"])){
        $pErr= "please enter password";
    } else{
        $password =$_POST['password'];
    }
    if(empty($_POST["cpassword"])){
        $pcErr= "please enter password";
    } else{
        $cpassword =$_POST['cpassword'];
    }
    if($password != $cpassword){
$pErr="enter valid password";
}
    
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Registeration Form</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

            <form  method="post" enctype="multipart/form-data">
            <div class="container">
                <div class="para">
                <h3> REGISTRATION FORM </h3>
                <p>Please fill in this form to create an account.</p>
</div>
    <hr>

    <div><input type="text" name="fname" class="fname" placeholder="First Name" value="<?php echo $firstname; ?>"><span class="error"><?php echo $firstnameErr; ?></span></div>
    <div><input type="text" name="lname"  class="lname"  placeholder="Last Name" value="<?php echo $lastname; ?>"><span class="error"><?php echo $lastnameErr; ?></span></div>             
   <div><input type="email" name="email" class="email"  placeholder="Email"  value="<?php echo $email; ?>"><span class="error"><?php echo $emailErr; ?></span>
    <br>
    <div><input type="tel" id="phone" name="phone" class="contact" placeholder="Phone Number" value="<?php echo $phone; ?>"><span class="error"><?php echo $phoneErr; ?></span><br></div>
    <div><input type="text" name="age" placeholder="Age" class="age" value="<?php echo $age; ?>"><span class="error"><?php echo $ageErr; ?></span></div>
    </div> 
           <div>  <input type="password" name="password" class="pwd" placeholder="Enter Password" value="<?php echo $password; ?>"><span class="error"><?php echo $pErr; ?></span></div>
           <div>  <input type="password" name="cpassword" class="pwd" placeholder="Confirm Password" value="<?php echo $password; ?>"><span class="error"><?php echo $pcErr; ?></span></div>

    </div>
    <br>
    <label for="formFileLg" class="img">Image File:</label>
    <input  name="img" id="formFileLg" value="<?php echo $img; ?>" type="file" /><span class="error"><?php echo $imgErr; ?></span><br>
    <input type="submit" name="submit" value="Submit" class="registerbtn">
</div>

<div class="container signin">
    <p>
        Already a member? <a href="login.php">Sign in</a>
    </p>
</div>    
</form>
</body>
</html>

<?php
if(isset($_POST['submit'])) {  
    $password_1 = md5($password);
    //$img =$_POST['img'];

$filename = $_FILES['img']['name'];
        $tempname = $_FILES['img']['tmp_name'];
       $folder = "uploads/".$filename;
move_uploaded_file($tempname,$folder);
    if($firstnameErr == "" && $lastnameErr == "" && $emailErr == "" && $phoneErr == "" && $pErr == "" && $pcErr == "" && $ageErr == "") { 
       
        echo "<h3 color = #FF0001> <b>You have sucessfully registered.</b> </h3>"; 
         $query= "INSERT INTO details(fname,lname,email,number,age,password,rpassword,image) VALUES('$firstname','$lastname','$email','$phone','$age','$password_1','$cpassword','$filename') ";
        if($conn->query($query)){
            echo "done";
        }
        else{
            echo $conn->error;
        }
     $data = mysqli_query($conn,$query);

        
        $firstname = $lastname = $age = $email= $phone= $password = $cpassword = $img="";

         } else {  
        echo "<h3> <b>Your form values is incorrect .</b> </h3>";  
    }  
    }  
    ?>
