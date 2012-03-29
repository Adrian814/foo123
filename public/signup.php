<?php
session_start();
include "user.class.php";
?>

<html>
    <head>
        <?php
        include "ini_set.inc.php";
        include "design.class.php";

        $design = new designclass();
        $design->createHeader("Demo Kalender > Sign Up", "css/web_tech.css");

        ?>
        <script type="text/javascript" src="js/check.js"> </script>
    </head>
    <body onload="document.signup.first.focus();">
        <div id="all">

            <?php
            $user = new userclass();
            $user->getDB();
            $design->createNavigation("navigation", "navigation_logo", "navigation_title", "navigation_nav", "nav", "navigation_login");

            if(isset($_POST["submit"])) {


            // captcha
                if ($_SESSION["captcha_spam"] != $_POST["sicherheitscode"]) {
                    $msg = "Captcha wasn't correct!";
                }
                // check mandatory fields
                else if (!$_POST['first'] || !$_POST['last'] || !$_POST['nick'] ||
                        !$_POST["pass1"] || !$_POST["pass2"] || !$_POST["email"]) {
                        $msg = 'You did not complete all of the required fields!';
                    }
                    else if ($_POST["pass1"] != $_POST["pass2"]) {
                            $msg = 'Passwords do not match!';
                        }
                        else if($user->checkUser($_POST["nick"])) {
                                $msg = "Username '" . $_POST["nick"] . "' already taken.";
                            }
                            else { // no input error

                            // encrypt the password and add slashes if needed
                                $_POST['pass1'] = md5($_POST['pass1']);
                                if (!get_magic_quotes_gpc()) {
                                    $_POST['pass1'] = addslashes($_POST['pass1']);
                                    $_POST['nick'] = addslashes($_POST['nick']);
                                }

                                date_default_timezone_set('Europe/Paris');

                                // profile picture
                                if($_FILES["userfile"]["error"] != 4) {
                                    // debug
                                    //echo print_r($_FILES["userfile"]) . "<p>";

                                    $name = $_FILES["userfile"]["name"];
                                    $temp_name = $_FILES["userfile"]["tmp_name"];
                                    $path = "pictures/profiles/";
                                    $final_name = substr($user->createPassword(), 0, 3) . "_".$name;

                                    move_uploaded_file($temp_name, $path.$final_name);
                                   
                                    //echo "error: " . $_FILES["userfile"]["error"] . "<p>";
                                   
                                }
                                else // default picture
                                    $final_name = "default.GIF";

                                // adds the user to the db
                                $user->addUser($_POST["first"],
                                    $_POST["last"],
                                    $_POST["nick"],
                                    $_POST["pass1"],
                                    $_POST["email"],
                                    date("Y-m-d"),
                                    $final_name,
                                    $_POST["view"],
                                    $_POST["hobbies"]);

                                $_SESSION["nick"] = $_POST["nick"];

                                echo "<p> Thanks for signing up, " . $_SESSION["nick"] . "!</p>";
                                echo "<p> Redirection in a few seconds... </p>";
                                echo "<meta http-equiv='refresh' content='3; url=index.php'>";
                                exit;
                            }

            }
            ?>
            <div id="body">
                <div id="content">

                    <h2>Create New Account</h2>
                    <form enctype="multipart/form-data" name="signup" action="signup.php" method="post">
                        <input name="first" type="text"> First Name* <br>
                        <input name="last" type="text"> Last Name* <br>
                        <input name="view" type="text"> Political View <br>
                        <input name="hobbies" type="text"> Hobbies
                        
                        <!-- file upload -->
                        <p>
                            <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
                            Choose a profile picture (max size is 3MB):
                            <p>
                                <input name="userfile" type="file" />
                            </p>
                        </p>
                        <p>
                            <input name="nick" type="text"> Nickname* <br>
                            <input name="email" onblur="javascript:validateForm();" type="text"> eMail* <br>
                            <input name="pass1"  type="password"> Password* <br>
                            <input name="pass2" type="password"> Re-enter Password*
                        </p>

                        <!-- captcha -->
                        <img alt="captcha" src="printCAPTCHA.php" border="0" title="Sicherheitscode"> Case Sensitive!<p>
                            <input type="text" name="sicherheitscode" size="5"> Security Code
                        </p>

                        <p>
                            <input name="submit" type="submit" value="Sign Up">
                            <input name="reset" type="reset" value="Reset">
                        </p>
                        <p>* mandatory!</p>

                    </form>
                    <?php

                    if(isset($msg)) {
                        echo "<span id='error'>$msg</span>";
                    }
                    ?>
                </div>
                <div id="calendar">
                    <?php include "cal.php"; ?>
                </div>
            </div>
        </div>
    </body>
</html>