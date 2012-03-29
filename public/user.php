<?php
    session_start();
    include "ini_set.inc.php";
  
    if(isset($_POST["submit"])) {
      
       /**
        * @TODO add javascript form checker
        */

       // captcha
       if ($_SESSION["captcha_spam"] != $_POST["sicherheitscode"]) {
           die("Captcha wasn't correct!");
       }

       // check mandatory fields
       if (!$_POST['first'] || !$_POST['last'] || !$_POST['nick'] ||
           !$_POST["pass1"] || !$_POST["pass2"]) {
            die('You did not complete all of the required fields');
       }

       if ($_POST["pass1"] != $_POST["pass2"]) {
            die('Passwords do not match!');
       }

       if (!get_magic_quotes_gpc()) {
            $_POST['nick'] = addslashes($_POST['nick']);
        }

        // encrypt the password and add slashes if needed
        $_POST['pass1'] = md5($_POST['pass1']);
        if (!get_magic_quotes_gpc()) {
            $_POST['pass1'] = addslashes($_POST['pass1']);
            $_POST['nick'] = addslashes($_POST['nick']);
        }

        include "user.class.php";
       date_default_timezone_set('Europe/Paris');

       $user = new userclass();
       $db = $user->getDB();

       $user_exists = $user->checkUser($_POST["nick"]);

       if(!$user_exists)
       {
           // adds the user to the db
           $user->addUser($_POST["first"],
                      $_POST["last"],
                      $_POST["nick"],
                      $_POST["pass1"],
                      date("Y-m-d"),
                      $_POST["view"],
                      $_POST["hobbies"]);
       }
       
     }

?>

<html>
    <head>
       <?php
            //ainclude "ini_set.inc.php";
            include "design.class.php";

            $design = new designclass();
            $design->createHeader("Demo Kalender > User", "css/web_tech.css");
        ?>
    </head>
    <body>
        <div id="all">

            <?php

                $design->createNavigation("navigation", "navigation_logo", "navigation_title", "navigation_nav", "nav", "navigation_login");

            ?>
            <div id="content">
      
                <h2>Create New Account</h2>
                <form action="user.php" method="post">
                    <input name="first" type="text"> First Name* <br>
                    <input name="last" type="text"> Last Name* <br>
                    <input name="view" type="text"> Political View <br>
                    <input name="hobbies" type="text"> Hobbies <p>

                    <input name="nick" type="text"> Nickname* <br>
                    <input name="pass1" type="password"> Password* <br>
                    <input name="pass2" type="password"> Re-enter Password* <p>
                    <img alt="captcha" src="printCAPTCHA.php" border="0" title="Sicherheitscode"> Case Sensitive!<p>
                    <input type="text" name="sicherheitscode" size="5"> Security Code<p>

                    <input name="submit" type="submit" value="Sign Up">
                    <input name="reset" type="reset" value="Reset">
                    <p>* mandatory!</p>

                </form>
                 <?php

                    if(isset($user_exists))
                    {
                        if($user_exists) {
                            echo "<p><font color='#B40404'> Nickname '". $_POST["nick"] . "' already taken! </font></p>";
                        }
                    }
                ?>
            </div>
            <div id="calendar">
                Hier kommt der Kalender hin..
            </div>
        </div>
    </body>
</html>