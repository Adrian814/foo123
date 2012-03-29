<?php

/**
 * This class provides access to user information.
 *
 * @author Adrian Siuda, Tobias Sennhauser
 * created on 2.11.2009
 * last updated: 24.11.2009
 *
 * @TODO: input check in function 'createChangeSettings()'
 *
 */
class userclass {

    function addUser($first, $last, $nick, $pw, $email, $date, $picture, $view, $hobbies)
    {   
        $sql = "insert user (first, last, nick, view, hobbies, pw, email, date, picture) values
                ('" . $first . "',
                '" . $last . "',
                '" . $nick . "',
                '" . $view . "',
                '" . $hobbies . "',
                '" . $pw . "',
                '" . $email . "',
                '" . $date . "',
                '" . $picture . "')";

        //echo "<p>SQL:" . $sql . "</p>";
        
        mysql_query($sql);

        // debug
//        $num = mysql_affected_rows();
//        if($num > 0)
//        {
//            echo "1 Datensatz hinzugefügt";
//        }
//        else
//        {
//            echo "Fehler: Es konnte kein Datensatz hinzugefügt werden!";
//        }
    }

    /**
     * a function which performs the login if the
     * login data is correct. 
     *
     * @param String $user The entered username.
     * @param String $pw The password entered by the user.
     * @return String a message containing the result of the login.
     */
    function login($user, $pw)
    {
        $users = mysql_query('SELECT * FROM user WHERE
                    nick = "' . $user . '" AND
                    pw = "' . md5($pw) . '"');

         // anzahl der gefunden Zeilen des Datensatzes
         $anz = mysql_num_rows($users);

         if ($anz != 0)
         {            
            $_SESSION["nick"] = $user;
            return true;
         }
         else
         {
            return false;
         }
    }

    /**
     * a function to destroy the session, delete the cookie
     * and unset a php session variable for the username.
     *
     * @return String The nickname of the user that has just logged out.
     */
    function logout()
    {
         $nick = $_SESSION["nick"];
         $_SESSION = array();
         session_regenerate_id(true);
         setcookie("Demo_Kalender", FALSE, time()-60);
         return $nick;
    }

 /**
     * creates a cookie for some user.
     * It attaches his/her full name to it and expires after one hour.
     *
     * @param String $user The user which wants to stay logged in.
     */
    function setCookie($user)
    {
        setcookie("Demo_Kalender", $user, time()+3600*24);
        //echo "cookie is set! <p>";
    }

    function checkCookie()
    {
        if(isset($_COOKIE["Demo_Kalender"]))
        {
            $_SESSION["nick"] = $_COOKIE["Demo_Kalender"];
        }
    }

    /**
     * A function to check whether some username exists.
     *
     * @param String $user The user to be checked.
     * @return boolean True if the user was found, else false.
     */
    function checkUser($user)
    {
        $sql = mysql_query('SELECT * FROM user WHERE
                    nick = "' . $user . '"');

         // anzahl der gefunden Zeilen des Datensatzes
         $anz = mysql_num_rows($sql);

         if ($anz != 0)
         {
            return true;
         }
         else
         {
            return false;
         }
    }

    /*
     *
     * @param String $user The user to be inspected.
     * @return String The user's first name.
     */
    function getFirstName($user)
    {
        $db = $this->getDB();

        $sql = mysql_query('SELECT * FROM user WHERE
                    nick = "' . $user . '"');

         // number of the db entries.
         $anz = mysql_num_rows($sql);

         if ($anz != 0)
         {
            $row = mysql_fetch_array($sql);
            //echo "return: " . $row["first"];
            return $row["first"];
         }
         else
         {

            die(mysql_error());
         }
    }

    /*
     *
     * @param String $user The user to be inspected.
     * @return String The user's last name.
     */
    function getLastName($user)
    {
        $db = $this->getDB();

        $sql = mysql_query('SELECT * FROM user WHERE
                    nick = "' . $user . '"');

         // number of the db entries.
         $anz = mysql_num_rows($sql);

         if ($anz != 0)
         {
            $row = mysql_fetch_array($sql);
            return $row["last"];
         }
         else
         {
            die(mysql_error());
         }
    }

    /*
     * a function to get a specific field from the db.
     *
     * @param String $user The user to be inspected.
     * @param String $field The wanted field.
     * @return String The user's field entry.
     */
    function getField($user, $field)
    {
        $db = $this->getDB();

        // debug
        //echo "DB: " . $db . ", User: " . $user . ", Field: ". $field;

        $txt  ='SELECT * FROM user WHERE
                    nick = "' . $user . '"';
        //echo $txt;
        $sql = mysql_query($txt);

         // number of the db entries.
         $anz = mysql_num_rows($sql);
         //echo "anz: " . $anz;

         if ($anz != 0)
         {
            $row = mysql_fetch_array($sql);
            //print_r($row);
            return $row[$field];
         }
         else
         {
            die(mysql_error());
         }
    }

    /**
     * A function to get access to a db.
     *
     * @return DB Resource ID 
     */
    function getDB()
    {
// adi
       $db = mysql_connect("localhost", "siudaa", "saihiechoo3E") or die(mysql_error());
        mysql_select_db("proj2009-siudaa", $db) or die(mysql_error());
//
// vanderweg
//        $db = mysql_connect("localhost", "dev.ipirate.ch", "ipirate1") or die(mysql_error());
//        mysql_select_db("dev_ipirate_ch", $db) or die(mysql_error());

// ig
//        $db = mysql_connect('localhost', 'phpmyadmin', 'mausfalle');
//        mysql_select_db("phpmyadmin", $db) or die(mysql_error());

        date_default_timezone_set('Europe/Paris');

        return $db;
    }

    function createSettings($user, $param)
    {
        // fields order of db
        $db_fields = array("date", "first", "last",
            "view", "hobbies", "email");
        $label_fields = array("Member since: ", "First Name: ", "Last Name: ",
            "Political View:", "Hobbies:", "eMail:");

        // draw table
        echo "<table border=0>";
        echo "<tr><th colspan='2'>";
        if ($param) 
            echo "<h2 class='settings'>Settings for $user</h2>";
        else
            echo "<h2 class='settings'>Profile for $user</h2>";
        echo "</th></tr>";

        // profile picture
        $picture = $this->getField($user, "picture");
        //echo "Picture: " . $picture;
        echo "<tr><td>";
        echo "<img src='pictures/profiles/" . $picture . "' alt='profile image' width=200 height=200>";
        echo "</td><td>";
        echo "<table border=0 id='settings_table'>";

        for($i = 0; $i < count($db_fields); $i++)
        {
            echo "<tr>";
            echo "<td id='settings'>";
            echo $label_fields[$i];
            echo "</td><td>";
            echo $this->getField($user, $db_fields[$i]);
            echo "</td></tr>";
        }
        echo "</table>";

        echo "</td></tr>";
        
        if ($param) {
            // 2nd row: button
            echo "<tr><td style='padding-top:20px;'>";
            echo '<input type="button" value="Change Settings" name="changeSettings" onclick="window.location.href=\'changeSettings.php\'"/>';
            echo "</td></tr>";
            echo "</table>";
        }
        else
            echo "</table>";
    }

    /*
     * creates a form to change the settings for a specific user.
     * on submit 'settings.php' is called where the fields are updated.
     */
    function createChangeSettings($user)
    {
        // fields order of db
        $db_fields = array("first", "last",
            "view", "hobbies", "email");
        $label_fields = array("First Name: ", "Last Name: ",
            "Political View:", "Hobbies:", "eMail:");

        echo "<form enctype='multipart/form-data' name='changeSettings' action='settings.php' method='post'>";
        echo "<table border='0'>";
        for($i = 0; $i < count($label_fields); $i++)
        {
            echo "<tr><td>";
            echo $label_fields[$i];
            echo "</td><td>";
            echo "<input name='". $db_fields[$i] . "' type='text' value='" . $this->getField($user, $db_fields[$i]) . "'>";
            echo "</td></tr>";
        }
        
        // upload image
        $max_image_size = 3000000;
        echo "<tr><td>";
        // MAX_FILE_SIZE must precede the file input field
        echo '<input type="hidden" name="MAX_FILE_SIZE" value="' . $max_image_size . '" />';
        // Name of input element determines name in $_FILES array
        echo 'Change profile picture: </td><td><input name="userfile" type="file" />';
        echo "</td></tr>";

        // change password
        echo "<tr><td style='line-height: 50px; padding-top:20px;'>";
        echo '<p><input type="checkbox" name="pw"> Change password</p>';
        echo "</td></tr><tr><td>";
        echo "New Password: </td><td>";
        echo "<input name='pw1' type='password'>";
        echo "</td></tr>";

        echo "<tr><td>";
        echo "Re-enter Password: </td><td>";
        echo "<input name='pw2' type='password'>";
        echo "</td></tr>";
        echo "</table>";

        // button
        echo "<p><input type='submit' name='submit' value='Save Settings'></p>";
        echo "</form>";
    }

    /*
     * creates a login form.
     */
    function createLogin()
    {
        echo "<h2>Login</h2>";
        echo '<form name="login" action="login.php" method="post">';
        echo '<input name="nick" type="text"> Nickname <br>';
        echo '<input name="pass" type="password"> Password <p>';
        echo '<p><input type="checkbox" name="cookie"> Stay signed in</p>';
        echo '<input name="submit" type="submit" value="Sign In">';
        echo '<input name="reset" type="reset" value="Reset"> </p>';
        echo "</form>";

        echo 'No account yet? Get one <a class="nav" href="signup.php">here</a>.';
        echo '<p>Forgot your password? Get it <a class="nav" href="requestPassword.php">here</a>.</p>';
    }

    /**
     * a function to change the user settings.
     * Note that not all fields have to be new.
     *
     * @param String $user The user to be changed.
     * @param String $first The user's first name.
     * @param String $last The user's last name.
     * @param String $view The user's political view.
     * @param String $hobbies The user's hobbies.
     * @param String $email The user's email address.
     * @param String $picture The user's profile pictures.
     * @return boolean or String $ret true on success or the sql error on failure.
     */
    function changeSettings($user, $first, $last, $view, $hobbies, $email, $picture)
    {
         $db = $this->getDB();

         $text = 'UPDATE user
                  SET  first = "'. $first . '",
                       last = "'. $last . '",
                       nick = "'. $user . '",
                       view = "'. $view . '",
                       hobbies = "'. $hobbies . '",
                       pw = "'. $this->getField($user, "pw") . '",
                       email = "'. $email . '",
                       date = "'. $this->getField($user, "date") . '",
                       picture = "'. $picture . '"
                  WHERE nick = "' . $user . '"';
         //echo $text . "<p>";

         $sql = mysql_query($text);

    }

    /**
     * Checks if an email address exists and, if yes, sends an email to the user
     * and resets the login information of this user.
     *
     * @param String $email The email address of the user.
     * @return String An confirmation or error message.
     */
    function sendMail($email)
    {

        include("phpmailer/class.phpmailer.php");
        //include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

        date_default_timezone_set('Europe/Paris');

        $mail             = new PHPMailer();

        $user = $this->getNickname($email);
        if(!$user)
        {
            return "error: email address not found!";
        }

        $pw = $this->createPassword();
        $this->setPassword($user, $pw);
        $url = "http://diuf-student.unifr.ch/2009/siudaa/project/code/login.php";

        $body = "<h3>Dear activist </h3>
                Since you seem to have misplaced your login, here you go: <p>
                <table border=0><tr><td>
                Username:</td><td>" . $user . "</td></tr><tr><td>
                Password:</td><td>" . $pw ." </td></tr></table>
                <p>
                You can login <a href='". $url . "'> here</a>.
                </p>";

        $mail->IsSMTP();
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 465;                   // set the SMTP port for the GMAIL server

        $mail->Username   = "democal2009@gmail.com";
        $mail->Password   = "2009democal";

        $mail->From       = "no-reply@democal2009.ch";
        $mail->FromName   = "Demo Calendar";

        $mail->Subject    = "Login Information";

     //   $mail->Body       = "Hi,<br>This is the HTML BODY<br>";                      //HTML Body
        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->WordWrap   = 50; // set word wrap

        $mail->MsgHTML($body);

        $mail->AddAddress($email, "Admin");


        $mail->IsHTML(true); // send as HTML

        /* debug */
        if(!$mail->Send()) {
          return "Mailer Error: " . $mail->ErrorInfo;
        } else {
          return "email successfully sent to " . $email;
        } 
    }

    /**
     * Given an email address, this function extracts the corresponding nickname,
     * provided the address exists.
     *
     * @param String $email The email of the user
     * @return String/False The desired username or false if the email address doesn't exists.
     */
    function getNickname($email)
    {
        $db = $this->getDB();

        $query = 'SELECT * FROM user WHERE
                    email = "' . $email . '"';
        //echo $query;

        $sql = mysql_query($query);

         // number of the db entries.
         $anz = mysql_num_rows($sql);

         if ($anz != 0)
         {
            $row = mysql_fetch_array($sql);
            return $row["nick"];
         }
         else
         {
            return false;
         }
    }

    /**
     * Generates a 8 digit password.
     *
     * @return String The generated password.
     */
    function createPassword()
    {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);

        $i = 0;
        $pass = '' ;

        while ($i <= 7) {

            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;

    }

    /**
     * Resets the password of a user.
     *
     * @param <type> $user The user who has misplaces his password.
     * @param <type> $pw The new password that was previously generated
     */
    function setPassword($user, $pw)
    {
        $db = $this->getDB();

         $text = 'UPDATE user
                  SET  pw = "'. md5($pw) . '"
                  WHERE nick = "' . $user . '"';

         $sql = mysql_query($text);
    }

    
    /*
     * creates and displays a form to request a new password.
     */
    function createRequestPassword()
    {
        echo "<h3>Request new password</h3>";
        echo "<p>Enter your eMail address to request your login information. </p>";
        echo '<form name="email" action="requestPassword.php" method="POST">';
        echo '<input type="text" name="email" value="" />';
        echo '<input type="submit" value="Get Login Data" name="submitRequestEmail" />';
        echo '</form>';
    }

    /**
     * a function which consumes a day, month and year that
     * checks whether some events occur on the given date.
     *
     * @param String $day The day of that date.
     * @param String $month The month of that date.
     * @param String $year The year of that date.
     * @return boolean True of one or more events occour on this date.
     */
    function checkEvent($day, $month, $year)
    {
        date_default_timezone_set('Europe/Paris');

        $date = date("Y-m-d", mktime(0,0,0,$month, $day, $year));
        //echo $date;
        
        $db = $this->getDB();
        //echo $db;

        $sql = mysql_query('SELECT * FROM Events WHERE
                    date = "' . $date . '"');
        //echo $sql;

         // number of the db entries.
         $anz = mysql_num_rows($sql);

         if ($anz != 0)
         {
            return true;
         }
         else
            return false;
         
    }

    /**
     * A function to get the event id's of a corresponding date.
     *
     * @param Date $date The date of the events to be searched.
     * @return array An array of id's of the events on that day.
     */
    function getEventID($date)
    {
        $db = $this->getDB();
        //echo $db;


        $sql = mysql_query('SELECT * FROM Events WHERE
                    date = "' . $date . '"');

        // number of the db entries.
         $anz = mysql_num_rows($sql);



         if ($anz != 0)
         {
             $i = 0;
             $row = mysql_fetch_array($sql);
//             while($row = mysql_fetch_assoc($sql))
//             {
//                echo $row["EventId"] . "<br>";
//                $id[$i] = $row["EventId"];
//                $i++;
//             }
            
            return $row["EventId"];
            //return $id;
         }
         else
         {
            die(mysql_error());
         }
    }

    /**
     * sends an email to the admin account that an event was reported.
     *
     * @param int $id The id of the event that was reported.
     * @return String A confirmation or error message when the message was sent.
     */
    function reportEvent($id)
    {
        include("phpmailer/class.phpmailer.php");
        //include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

        $mail             = new PHPMailer();
        $email = "democal2009@gmail.com";
        date_default_timezone_set('Europe/Paris');

        $url = "http://diuf-student.unifr.ch/2009/siudaa/project/code/EventProfile.php?EventId=$id";

        $body = "<h3>Dear admin </h3>
                An event was reported. You might want to have a look at it. <p>

                You can check it <a href='". $url . "'> here</a>.
                </p>";

        $mail->IsSMTP();
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
        $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
        $mail->Port       = 465;                   // set the SMTP port for the GMAIL server

        $mail->Username   = "democal2009@gmail.com";
        $mail->Password   = "2009democal";

        $mail->From       = "no-reply@democal2009.ch";
        $mail->FromName   = "Demo Calendar";

        $mail->Subject    = "Event Reported!";

     //   $mail->Body       = "Hi,<br>This is the HTML BODY<br>";                      //HTML Body
        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->WordWrap   = 50; // set word wrap

        $mail->MsgHTML($body);

        $mail->AddAddress($email, "Admin");


        $mail->IsHTML(true); // send as HTML

        /* debug */
        if(!$mail->Send()) {
          return "Mailer Error: " . $mail->ErrorInfo;
        } else {
          return "Event successfully reported.";
        }
    }
}


   ?>
