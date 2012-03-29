<?php session_start(); ?>

<html>
    <head>
        <?php
            include "ini_set.inc.php";
            include "design.class.php";
            include "user.class.php";

            $user = new userclass();
            $user->checkCookie();

            $design = new designclass();
            $design->createHeader("Demo Kalender > About", "css/web_tech.css");


        ?>

    </head>
    <body>
        <div id="all">

            <?php

                $design->createNavigation(  "navigation",
                                            "navigation_logo",
                                            "navigation_title",
                                            "navigation_nav",
                                            "nav",
                                            //"loginLinks",
                                            "navigation_login");

            ?>
            <div id="body">
                <div id="content">
                    <h2>About</h2>
                     The purpose of this website is to create a plattform for political activists.
                     An activist can create an account in order to add new events to the list. Being
                     a member one can also comment and rate existing events.
                     
                     <p>This website was written by Adrian Siuda and Tobias Sennhauser
                     for a project called "Web Technologies" taught at University of Fribourg in 2009.</p>

                     <p>
                         Comments and remarks can be sent to the <a href="mailto:democal2009@gmail.com">admin</a>.
                     </p>

                </div>
                <div id="calendar">
                    <?php

                        include "cal.php";
                        //include "Calendar.php";

                    ?>
                </div>
            </div>
        </div>
    </body>
</html>