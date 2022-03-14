<?php
session_start();
$con = mysqli_connect("localhost","root","","social");
if(mysqli_connect_errno()) {
    echo "Failed to connect: ". mysqli_connect_errno();
}
//Declaring variable to prevent errors
$fname = "";//First Name
$lname = "";//Last Name
$em = "";//Email
$em2 = "";//Email2
$password = "";//Password
$password2 = "";//Password2
$date = "";//Sign up Date
$error_array = array();//Holds error messages

if(isset($_POST['register_button'])){

      //Register form values
      //First Name
      $fname = strip_tags($_POST['reg_fname']); //Store in fname variable the value sent by the POST form and remove html tags
      $fname = str_replace(' ', '', $fname); //Take fname var and wherever you find space replace it with '' (remove spaces)
      $fname = ucfirst(strtolower($fname)); //Take the name var and convert all the letters to lowercase and leave only the first letter uppercase
      $_SESSION['reg_fname'] = $fname; //Stores first name into the session variable

       //Last Name
       $lname = strip_tags($_POST['reg_lname']); 
       $lname = str_replace(' ', '', $lname);
       $lname = ucfirst(strtolower($lname)); 
       $_SESSION['reg_lname'] = $lname;

       //Email
       $em = strip_tags($_POST['reg_email']); 
       $em = str_replace(' ', '', $em);
       $em = ucfirst(strtolower($em)); 
       $_SESSION['reg_email'] = $em;

        //Email2
        $em2 = strip_tags($_POST['reg_email2']); 
        $em2 = str_replace(' ', '', $em2);
        $em2 = ucfirst(strtolower($em2)); 
        $_SESSION['reg_email2'] = $em2;

         //Password
       $password = strip_tags($_POST['reg_password']); 
       
        //Password2
        $password2 = strip_tags($_POST['reg_password2']); 

        //Date
       $date = date("Y-m-d"); //Current date

       if($em == $em2) //checking if email is in valid format
       {
           if(filter_var($em, FILTER_VALIDATE_EMAIL))
           {
              $em = filter_var($em, FILTER_VALIDATE_EMAIL);

              //check if email already exists
              $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

              //count the number of rows returned
              $num_rows = mysqli_num_rows($e_check); //containing the amount of results from e_check

              if($num_rows > 0) {
                  array_push($error_array,"Email already in use<br>");
              }
           }
           else {
            array_push($error_array, "Invalid email format<br>");
           }
       }
         else {
            array_push($error_array,"Emails don't match<br>");
         }  
   
         if(strlen($fname)>25 || strlen($fname)<2) {
            array_push($error_array,"Your first name must be between 2 and 25 characters<br>");
        }

        if(strlen($lname)>25 || strlen($lname)<2) {
            array_push($error_array,"Your last name must be between 2 and 25 characters<br>");
      }

      if($password != $password2) {
        array_push($error_array,"Your passwords don't match<br>");
      }
      else {
          if(preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($error_array,"Your password can only contain english characters or numbers<br>");
          }
      }
       if (strlen($password) > 30 || strlen($password)<5) {
        array_push($error_array,"Your password must be between 5 and 30 characters<br>");
       }
    if(empty($error_array)) {
        $password = md5($password); //Encrypt password before sending to database

        //Generate username by concatenating first name and last name
        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

        $i = 0;
        //if username exists add number to username
        while(mysqli_num_rows($check_username_query) != 0) {
            $i++; //add 1 to i
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'"); //Generating an unique username

        }

        //Profile picture assignment
        $rand = rand(1,15); //Random number between 1 and 15
        if ($rand == 1)
        $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
        if ($rand == 2)
        $profile_pic = "assets/images/profile_pics/defaults/head_alizarin.png";
        if ($rand == 3)
        $profile_pic = "assets/images/profile_pics/defaults/head_amethyst.png";
        if ($rand == 4)
        $profile_pic = "assets/images/profile_pics/defaults/head_belize_hole.png";
        if ($rand == 5)
        $profile_pic = "assets/images/profile_pics/defaults/head_carrot.png";
        if ($rand == 6)
        $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
        if ($rand == 7)
        $profile_pic = "assets/images/profile_pics/defaults/head_green_sea.png";
        if ($rand == 8)
        $profile_pic = "assets/images/profile_pics/defaults/head_nephritis.png";
        if ($rand == 9)
        $profile_pic = "assets/images/profile_pics/defaults/head_pomegranate.png";
        if ($rand == 10)
        $profile_pic = "assets/images/profile_pics/defaults/head_pumkin.png";
        if ($rand == 11)
        $profile_pic = "assets/images/profile_pics/defaults/head_red.png";
        if ($rand == 12)
        $profile_pic = "assets/images/profile_pics/defaults/head_sun_flower.png";
        if ($rand == 13)
        $profile_pic = "assets/images/profile_pics/defaults/head_turqoise.png";
        if ($rand == 14)
        $profile_pic = "assets/images/profile_pics/defaults/head_wet_asphalt.png";
        if ($rand == 15)
        $profile_pic = "assets/images/profile_pics/defaults/head_wisteria.png";

        $query = mysqli_query($con, "INSERT INTO users VALUES ('','$fname','$lname','$username','$em','$password','$date','$profile_pic','0','0','no',',')");
        
        array_push($error_array, "<span style='color: #228B22;'>You're all set! Go ahead and login!</span><br>");
 
        //Clear session variables
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";

    }

    }

?>

<html>
    <head>
        <TITLE>Welcome to Cinefils!</TITLE>
    </head>
    <body>
    <form action="register.php" method="POST">
        <input type="text" name="reg_fname" placeholder="First Name" value="<?php 
        if (isset($_SESSION['reg_fname'])) {
             echo $_SESSION['reg_fname'];
        }
        ?>" required>
        <br>
        <?php if(in_array("Your first name must be between 2 and 25 characters<br>", $error_array)) echo "Your first name must be between 2 and 25 characters<br>"; ?>

        <input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
        if (isset($_SESSION['reg_lname'])) {
             echo $_SESSION['reg_lname'];
        }
        ?>" required>
        <br>
        <?php if(in_array("Your last name must be between 2 and 25 characters<br>", $error_array)) echo "Your last name must be between 2 and 25 characters<br>"; ?>

        <input type="email" name="reg_email" placeholder="Email" value="<?php 
        if (isset($_SESSION['reg_email'])) {
             echo $_SESSION['reg_email'];
        }
        ?>" required>
        <br>
        <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
        if (isset($_SESSION['reg_email2'])) {
             echo $_SESSION['reg_email2'];
        }
        ?>" required>
        <br>
        <?php if(in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>"; 
         else if(in_array("Invalid email format<br>", $error_array)) echo "Invalid email format<br>"; 
         else if(in_array("Emails don't match<br>", $error_array)) echo "Emails don't match<br>"; ?>


        <input type="password" name="reg_password" placeholder="Password" required>
        <br>
        <input type="password" name="reg_password2" placeholder="Confirm Password" required>
        <br>
        <?php if(in_array("Your passwords don't match<br>", $error_array)) echo "Your passwords don't match<br>"; 
        else if(in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo "Your password can only contain english characters or numbers<br>"; 
        else if(in_array("Your password must be between 5 and 30 characters<br>", $error_array)) echo "Your password must be between 5 and 30 characters<br>"; ?>

        <input type="submit" name="register_button" value="Register">
        <br>
        <?php if(in_array("<span style='color: #228B22;'>You're all set! Go ahead and login!</span><br>", $error_array)) echo "<span style='color: #228B22;'>You're all set! Go ahead and login!</span><br>"; ?> 

    </form>    

    </body>
</html>