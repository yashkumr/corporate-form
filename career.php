<?php
ob_start();
session_start();

include 'connection.php';

$targetDir = "documents/";
$flag = true;

$nameErr = $middlenameErr = $lnameErr = $companynameErr = $registeraddressErr = $countryErr = $stateErr = $gstnumberErr = $spendErr = $passwordErr = $confirmpasswordErr = $pointnameErr = $pointnumberErr = $panphotoErr = $aadharphotoErr = $agreementphotoErr = $contactErr =  $emailErr =  $photoErr =  $pincodeErr =  $aadharErr = $panErr = $aadhaarErr = $aviationErr = $photoProperPath = $destination_folder = $aviationProperPath = null;
$name = $middlename = $lname = $companyname = $registeraddress = $country = $state = $gstnumber = $spend =  $contact = $password = $confirmpassword = $pointname = $pointnumber = $email  = $state = $pincode = $aadharphoto = $panphoto  = $agreementphoto =  NULL;
$file_path = "";

$sucess = '';

if (isset($_POST['submit'])) {



    $name = mysqli_real_escape_string($con, $_POST['name']);
    $middlename = mysqli_real_escape_string($con, $_POST['middlename']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $companyname = mysqli_real_escape_string($con, $_POST['companyname']);
    $registeraddress = mysqli_real_escape_string($con, $_POST['registeraddress']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $pincode = mysqli_real_escape_string($con, $_POST['pincode']);
    $gstnumber = mysqli_real_escape_string($con, $_POST['gstnumber']);
    $spend = mysqli_real_escape_string($con, $_POST['spend']);

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $contact = mysqli_real_escape_string($con, $_POST['contact']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirmpassword = mysqli_real_escape_string($con, $_POST['confirmpassword']);

    $pointname = mysqli_real_escape_string($con, $_POST['pointname']);
    $pointnumber = mysqli_real_escape_string($con, $_POST['pointnumber']);
    // $aadharphoto = mysqli_real_escape_string($con, $_POST['aadharphoto']);
    // $panphoto = mysqli_real_escape_string($con, $_POST['panphoto']);
    // $agreementphoto = mysqli_real_escape_string($con, $_POST['agreementphoto']);
    $created_at = date('Y-m-d h:i:s');

    if ($password != $confirmpassword) {
        $confirmpasswordErr = "password is not matched";
        $flag = false;
    }

    // email format check and not matched in the database 
    $emailQuery = "Select * from career where email = '$email'";
    $emailQueryCheck = mysqli_query($con, $emailQuery);

    if (mysqli_num_rows($emailQueryCheck) > 0) {
        $emailErr =  "This Email is already register";
        $flag = false;
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $emailErr = "$email is not a valid";
            $flag = false;
        }
    }
    //email end


    //GST validation

    if ($gstnumber) {
        // GST Format: 2 digits (State Code), 10 alphanumeric characters (PAN), 1 digit, 1 alphabet, 1 alphanumeric (check code)
        $pattern = "/^([0-9]{2})([A-Z]{5}[0-9]{4}[A-Z]{1})([0-9]{1})([A-Z]{1})([A-Z0-9]{1})$/";

        if (!preg_match($pattern, $gstnumber)) {
            $gstnumberErr = "Please enter valid GST number";
            $flag = false;
        }
    }

    if (!preg_match("/^[a-zA-Z'-]+$/", $name)) {
        $nameErr = "only alphabet and white space allowed";
        $flag = false;
    }

    // if (!preg_match("/^[a-zA-Z'-]+$/", $fathername)) {
    //     $fatherErr = "only alphabet and white space allowed";
    //     $flag =  false;
    // }

    // Name and Father name validation end

    //number validation
    if (!preg_match('/^[0-9]{10,10}+$/', $contact)) {
        $contactErr = "Invalid Phone Number";
        $flag = false;
    }
    //number validation end

    //pincode validaiton
    if (!preg_match('/^[0-9]{6,6}+$/', $pincode)) {
        $pincodeErr = "pincode must be in numeric";
        $flag = false;
    }
    //pincode validation end 

    //aadharphoto validation



    $allowed_extension = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];


    if (isset($_FILES['aadharphoto']) && $_FILES['aadharphoto']['error'] == 0) {
        $extension_1 = explode('.', $_FILES['aadharphoto']['name']);
        $file_name = $extension_1[0] . '_' . time() . '.' . $extension_1[1];
        $destination_folder = 'documents/';
        $aadharphotoProperPath = $destination_folder . $file_name;

        if (in_array($extension_1[1], $allowed_extension)) {

            move_uploaded_file($_FILES['aadharphoto']['tmp_name'], $aadharphotoProperPath);
        } else {
            $aadharphotoErr = 'pdf, docx, jpg, jpeg, and png file format are allowed';
            $flag = false;
        }
    } else {
        //check file size 10mb 

        if ($_FILES["aadharphoto"]["size"] > 10485760) {
            $aadharphotoErr = "Sorry, your file is too large.";
            $flag = false;
        }
    }

    //aadharphoto validation end


    //panphoto
    $allowed_extension2 = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];


    if (isset($_FILES['panphoto']) && $_FILES['panphoto']['error'] == 0) {
        $extension_2 = explode('.', $_FILES['panphoto']['name']);
        $file_name2 = $extension_2[0] . '_' . time() . '.' . $extension_2[1];
        $destination_folder = 'documents/';
        $panphotoProperPath = $destination_folder . $file_name2;

        if (in_array($extension_2[1], $allowed_extension2)) {

            move_uploaded_file($_FILES['panphoto']['tmp_name'], $panphotoProperPath);
        } else {
            $panphotoErr = 'pdf, docx, jpg, jpeg, and png file format are allowed';
            $flag = false;
        }
    } else {
        //check file size 10mb 

        if ($_FILES["panphoto"]["size"] > 10485760) {
            $panphotoErr = "Sorry, your file is too large.";
            $flag = false;
        }
    }

    //panphoto

    //agreementphoto
    $allowed_extension3 = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];


    if (isset($_FILES['agreementphoto']) && $_FILES['agreementphoto']['error'] == 0) {
        $extension_3 = explode('.', $_FILES['agreementphoto']['name']);
        $file_name3 = $extension_3[0] . '_' . time() . '.' . $extension_3[1];
        $destination_folder = 'documents/';
        $agreementphotoProperPath = $destination_folder . $file_name3;

        if (in_array($extension_3[1], $allowed_extension3)) {

            move_uploaded_file($_FILES['agreementphoto']['tmp_name'], $agreementphotoProperPath);
        } else {
            $agreementphotoErr = 'pdf, docx, jpg, jpeg, and png file format are allowed';
            $flag = false;
        }
    } else {
        //check file size 10mb 

        if ($_FILES["panphoto"]["size"] > 10485760) {
            $agreementphotoErr = "Sorry, your file is too large.";
            $flag = false;
        }
    }

    //agreementphoto





    // error and final submission
    if ($flag && isset($_POST['declare'])) {

        $insertCareerData = "INSERT INTO career(name, middlename, lname, companyname, registeraddress, country, state, pincode, gstnumber, spend, email, contact, password, confirmpassword, pointname, pointnumber,panphoto, aadharphoto, agreementphoto, created_at) VALUES ('$name','$middlename','$lname','$companyname','$registeraddress','$country','$state','$pincode','$gstnumber','$spend','$email','$contact', '$password','$confirmpassword','$pointname','$pointnumber','$panphotoProperPath','$aadharphotoProperPath','$agreementphotoProperPath','$created_at')";
        $result = mysqli_query($con, $insertCareerData);




        if ($result) {

            //email send code
            $to = 'info@corporateplus.com';
            $subject = "Online Registration Enquiry Notification";
            $message = "
            <html>
            <head>
              <title>'$subject'</title>
            </head>
            <body>
              <h1 style='color: orange; text-align: center;'> Corporate + </h1>
              <hr>
              <p style='text-align: center; font-size: 20px; font-weight: bold;'>Applied For: Registration</p>
              <hr>
              <p>Name: $name</p>
              <hr>
              <hr>
              <h5>Company Details</h5>
              <hr>
              <p>Company Name: $companyname</p>
              <hr>
              <p>Register Address: $registeraddress</p>
              <hr>
              <p>Country: $country</p>
              <hr>
              <p>State: $state</p>
              <hr>
              <p>Pincode: $pincode</p>
              <hr>
              <p>GST No : $gstnumber</p>
              <hr>
              <p>Annual Spend: $spend</p>
              <hr>
              <h5>Business Account Contact Details </h5>
              <hr>
              <p>Email: $email</p>
              <hr>
              <p>Contact: $contact</p>
              <hr>
              <h5> Point Contact Name <h5>
              <p>: $pointname</p>
              <hr>
              <p>AadharCard: $pointnumber</p>
              <hr>
              <span>PanCard: <a href='dilip.jrinfotechs.com/career/$panphotoProperPath' download >$name</a></span>
              <hr>
              <hr>
              <span>Aadhar Card: <a href='dilip.jrinfotechs.com/career/$aadharphotoProperPath' download >$name</a></span>
              <hr>
               <hr>
              <span>Agreement Photo: <a href='dilip.jrinfotechs.com/career/$agreementphotoProperPath' download >$name</a></span>
             
            </body>
            </html>
            ";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <' . $email . '>' . "\r\n";
            $mail = mail($to, $subject, $message, $headers);


            $to1 = $email;
            $subject1 = "ShineAirways Registration";
            $message1 = "Thanks You For Regsitering you details on Shine Airways We will reach out to you soon";
            $headers1 = "MIME-Version: 1.0" . "\r\n";
            $headers1 .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers1 .= 'From: <info@corporateplus.com>' . "\r\n";
            $mail1 = mail($to1, $subject1, $message1, $headers1);

            //email code end

            echo "<script>alert('Thank you for registration with us.Our team will contact you soon')</script>";
        }
    }
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Document</title>
    <link rel="stylesheet" href="./career.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <!-- Option 1: Include in HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="../assets/css/style.css" rel="stylesheet">

    <!--  -->
    <!-- <link rel="stylesheet" href="../styles.css" /> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="css.css" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />



</head>


<body>
    <!-- navbar -->

    <!-- navbar -->

    <div class="container-fluid border">
        <div class="row">

            <div class="col-md-12 border">

                <div class=" corporate-form">



                    <form class="row g-3 needs-validation" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>"
                        method="POST" enctype="multipart/form-data">


                        <div class="corpo-img">
                            <img src="./corporate.PNG" alt="logo">
                        </div>


                        <div class="col-md-12 pb-0 mb-0 ">
                            <h5> Personal Details</h5>

                        </div>
                        <hr class="mt-0 pt-0 " />
                        <div class="col-md-4">

                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>"
                                placeholder="First Name" required>
                            <span style="color:red"><?php if (isset($nameErr)) echo $nameErr ?></span>
                        </div>
                        <div class="col-md-4">

                            <input type="text" name="middlename" class="form-control" value="<?php echo $middlename; ?>"
                                placeholder="Middle Name" required>
                            <span style="color:red"><?php if (isset($middlenameErr)) echo $nameErr ?></span>
                        </div>
                        <div class="col-md-4">

                            <input type="text" name="lname" class="form-control" value="<?php echo $lname; ?>"
                                placeholder="Last Name" required>
                            <span style="color:red"><?php if (isset($lnameErr)) echo $lnameErr ?></span>
                        </div>

                        <div class="col-md-12 pb-0 mb-0 ">

                            <h5> Company Details</h5>
                            <hr class="mt-0 pt-0 " />

                        </div>

                        <div class="col-md-12">
                            </strong></span></label>
                            <input type="text" name="companyname" class="form-control" value="<?php echo $companyname; ?>"
                                placeholder="Enter Company Name" required>
                            <span style="color:red"><?php if (isset($companynameErr)) echo $companynameErr ?></span>
                        </div>
                        <div class="col-md-12">

                            <input type="text" name="registeraddress" class="form-control" value="<?php echo $registeraddress; ?>"
                                placeholder="Registered Address" required>
                            <span style="color:red"><?php if (isset($registeraddressErr)) echo $registeraddressErr ?></span>
                        </div>



                        <!-- <div class="col-6">
                            <label for="education" class="form-label">Education<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <select id="inputState" class="form-select" name="education" value="<?= $education; ?>"
                                required>
                                <option seletcted value="10th">10th</option>
                                <option value="12th">12th</option>
                                <option value="Graduation">Graduation</option>
                                <option value="Post Graduation">Post Graduation</option>
                                <option value="Others">Ohters</option>
                            </select>
                        </div> -->

                        <!-- <div class="col-6">
                            <label for="applied" class="form-label">Applied For<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <select id="inputState" class="form-select" name="applied" required>
                                <option seletcted value="Travel Manager">Travel Manager</option>
                                <option value="Project Manager">Project Manager</option>
                                <option value="Tourism Manager">Tourism Manager</option>
                                <option value="Sales Manager">Sales Manager</option>
                                <option value="Area Manager">Area Manager</option>
                                <option value="Assistant Branch Manager">Assistant Branch Manager</option>
                                <option value="Hr Executive">Hr Executive</option>
                                <option value="Supervisor">Supervisor</option>
                                <option value="Sales Executive">Sales Executive</option>
                                <option value="IT Manager">IT Manager</option>
                                <option value="HR Manager">HR Manager</option>
                                <option value="PSA">PSA</option>
                                <option value="CSA">CSA</option>
                                <option value="Travel & Tourism">Travel & Tourism</option>
                                <option value="Cabin  Crew">Cabin Crew</option>
                                <option value="Ticket Support Executive">Ticket Support Executive</option>
                                <option value="Customer Realtionship Manager">Customer Realtionship Manager</option>


                            </select>
                        </div> -->
                        <!-- <div class="col-6">
                            <label for="address" class="form-label">Address<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <input type="text" class="form-control" name="address" placeholder="Address"
                                value="<?= $pincode ?>" required>
                            <span class="error"> <?php if (isset($pincodeErr)) echo $pincodeErr ?></span>

                        </div> -->

                        <div class="col-4">

                            <select name="country" id="country" class="form-select" required>
                                <option value="" selected disabled>Select a country</option>
                                <option value="Andhra Pradesh">India</option>
                                <option value="Arunachal Pradesh">Pakistan</option>
                                <option value="Assam">England</option>
                                <option value="Bihar">Austrelia</option>
                                <option value="Assam">USA</option>
                                <option value="Bihar">Canada</option>
                                <option value="Assam">Russia</option>
                                <option value="Bihar">Japan</option>


                            </select>
                            <span class="error"> <?php if (isset($countryErr)) echo $countryErr ?></span>
                        </div>

                        <div class="col-4">

                            <input type="text" class="form-control" name="state" placeholder="State"
                                required value="<?= $state; ?>">
                            <span class="error"> <?php if (isset($stateErr)) echo $stateErr ?></span>
                        </div>

                        <div class="col-4">


                            <input type="text" class="form-control" maxlength="6" name="pincode" placeholder="Pincode"
                                required value="<?= $pincode; ?>">
                            <span class="error"> <?php if (isset($pincodeErr)) echo $pincodeErr ?></span>

                        </div>
                        <div class="col-6">

                            <input type="text" class="form-control" name="gstnumber"
                                placeholder="GST Number" required value="<?= $gstnumber; ?>">
                            <span class="error"> <?php if (isset($gstnumberErr)) echo $gstnumberErr ?></span>

                        </div>
                        <div class="col-6">

                            <select name="spend" id="spend" class="form-select" required>
                                <option value="" selected disabled>Anual Travel spend</option>
                                <option value="spend">Less than RS. 10 lakh</option>
                                <option value="spend">Rs .10 lakh - Rs. 25 lakh</option>
                                <option value="spend">Rs .25 lakh - Rs. 1 Crore </option>
                                <option value="spend">More than Rs. 5 Crore</option>


                            </select>
                            <span class="error"> <?php if (isset($spendErr)) echo $gstnumberErr ?></span>

                        </div>



                        <div class="col-md-12 pb-0 mb-0 ">
                            <h5> For Business Account Contact Details</h5>

                        </div>
                        <hr class="mt-0 pt-0 fw-bold " />

                        <div class="col-6">

                            <input type="email" class="form-control" name="email" placeholder="E-mail"
                                value="<?php echo $email; ?>" required>
                            <span style="color:red"><?php if (isset($emailErr)) echo $emailErr ?></span>
                        </div>

                        <div class="col-6">

                            <input type="text" class="form-control" name="contact" placeholder="Contact"
                                maxlength="10" value="<?php echo $contact; ?>" required>
                            <span class="error"> <?php if (isset($contactErr)) echo $contactErr ?></span>
                        </div>

                        <div class="col-6">

                            <input type="text" class="form-control" name="password" placeholder="password" maxlength="15"
                                value="<?php echo $password; ?>" required>
                            <span class="error"> <?php if (isset($passwordErr)) echo $passwordErr ?></span>
                        </div>

                        <div class="col-6">

                            <input type="text" class="form-control" name="confirmpassword" placeholder="confirm password"
                                maxlength="" value="<?php echo $confirmpassword; ?>" required>
                            <span class="error"> <?php if (isset($confirmpasswordErr)) echo $confirmpasswordErr ?></span>
                        </div>

                        <div class="col-md-6 pb-0 mb-0 ">
                            <h5> Point Of Contact</h5>

                            <input type="text" class="form-control" name="pointname" placeholder="contact name"
                                maxlength="" value="<?php echo $pointname; ?>" required>
                            <span class="error"> <?php if (isset($pointnameErr)) echo $pointnameErr ?></span>


                        </div>
                        <div class="col-md-6 pb-0 mb-0 ">

                            <h5 style="font-size: 13px; mb-0"> Contact Number</h5>
                            <input type="text" class="form-control mt-0" name="pointnumber" placeholder=" contact number"
                                maxlength="10" value="<?php echo $pointnumber; ?>" required>
                            <span class="error"> <?php if (isset($pointnumberErr)) echo $pointnumberErr ?></span>


                        </div>

                        <!-- <div class="col-md-6">
                            <label for="aadharcard" class="form-label">Aadhaar No<span class="text-danger"><strong>
                                        *</strong></span></label>
                            <input type="text" class="form-control" id="aadharcard" name="aadhaarcard" maxlength="12"
                                value="<?= $aadhaarcard ?>" placeholder="Adharcard" required>
                            <span class="error"> <?php if (isset($aadhaarErr)) echo $aadhaarErr ?></span>

                        </div> -->



                        <div class="col-md-6">
                            <label for="photoupload" style="font-size:14px; color:#000000; font-weight: 500;">Upload Pan card<span
                                    class="text-danger"><strong> *</strong></span> </label><br>
                            <input type="file" class="form-control" id="inputGroupFile01" name="panphoto" required>
                            <span class="error"><?php if (isset($panphotoErr)) echo $panphotoErr ?></span>
                        </div>
                        <div class="col-md-6">
                            <label for="photoupload" style="font-size: 14px;color: #000000; font-weight: 500;">Upload Aadhaar Card<span class="text-danger"><strong>
                                        *</strong></span> </label><br>
                            <input type="file" class="form-control" id="inputGroupFile01" name="aadharphoto" required>
                            <span class="error"><?php if (isset($aadharphotoErr)) echo $aadharphotoErr ?></span>
                        </div>


                        <div class="col-md-12">
                            <label for="photoupload" style="font-size: 14px;color: #000000; font-weight: 500;">Agreement<span class="text-danger"><strong>
                                        *</strong></span> </label><br>
                            <input type="file" class="form-control" id="inputGroupFile01" name="agreementphoto" required>
                            <span class="error"><?php if (isset($agreementphotoErr)) echo $agreementphotoErr ?></span>
                        </div>

                        <div class="col-md-12 alert ">

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="declare" id="flexRadioDefault1" required>
                                <label class="form-check-label" for="flexRadioDefault1" style="font-size: 13px;color: #333;font-weight:500;">By registering, you agree to the business term of service and privacy policy of corporate+
                                </label>

                            </div>
                        </div>


                        <div class="col-12">
                            <button type="submit" name="submit" class="">SUBMIT</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>
    <!-- footer -->

    <!-- footer -->

    <!-- <img src="../images/shine-logo.png" alt=""> -->
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
</script>

<!-- <script src="./script.js" type="module"></script> -->
<script src="../script.js" type="module"></script>
<script src="../assets/js/faq.js"></script>