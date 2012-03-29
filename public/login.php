<?php
    session_start();
    include "user.class.php";
    date_default_timezone_set('Europe/Paris');
       
    $user = new userclass();


    if(isset($_POST["submit"])) {

       $db = $user->getDB();

       
       $login = $user->login($_POST["nick"], $_POST["pass"]);

       if(isset($_POST["cookie"]))
       {
            $user->setCookie($_POST["nick"]);
       }
     }

?>

<html>
    <head>
        <?php
            //include "ini_set.inc.php";
            include "design.class.php";
//            include "user.class.php";
//
//            $user = new userclass();
//            $user->checkCookie();

            $design = new designclass();
            $design->createHeader("Demo Kalender", "css/web_tech.css");
        ?>
    </head>
    <body onload="document.login.nick.focus();">
        <div id="all">

            <?php

                $design->createNavigation("navigation", "navigation_logo", "navigation_title", "navigation_nav", "nav", "navigation_login");

            ?>
            <div id="body">
                <div id="content">
                    <!-- CONTENT -->

                <?php

                    if (isset($login))
                    {
                        if($login)
                        {
                            echo "<p> Login Successful, " . $_SESSION["nick"] . "</p>";
                            echo "<p> Redirection in a few seconds... </p>";
                            echo "<meta http-equiv='refresh' content='3; url=index.php'>";
                        }
                        else
                        {
                            echo "<p> <span id='error'>Login failed! Nickname and/or password incorrect. </span></p>";
                            echo "<p> Back to <a class='nav' href='login.php'>Login</a></p>";
                        }

                    }
                    else
                        $user->createLogin();

                ?>
                
            </div>
            <!-- END-CONTENT -->
                <div id="calendar">
                    <?php include "cal.php"; ?>
                </div>
            </div>
        </div>
    </body>
</html>