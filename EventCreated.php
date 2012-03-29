
    <?php
        include "ini_set.inc.php";

//        include "user.class.php";
//        $user = new userclass();
        $db = $user->getDB();
        
        if (!$db) { 
            echo 'Error: Could not connect to database.';
            exit; 
        }
        
        $mysql = mysql_select_db('proj2009-siudaa');
        //$mysql = mysql_select_db('phpmyadmin');

        if(!$mysql) {
            echo 'Cannot select database.'; 
            exit;
        }
    
            // checks, if the time was correctly enterd
            if (!((00 <=  $_POST['Hours'] && $_POST['Hours'] < 24) && (00 <=  $_POST['Minutes'] && $_POST['Minutes'] < 60))) {
                echo "<font color='red'>Please enter a valid time!</font><br><br>";
                echo "<a href='./CreateEvent.php'>< Back to \"Create Event\"</a><br>"; 
            }

            // checks, if all fields were filled
            elseif (empty($_POST['Title'])
                    or empty($_POST['Type'])
                    or empty($_POST['SampleYear']) or empty($_POST['SampleMonth']) or empty($_POST['SampleDay'])
                    or empty($_POST['Hours']) or empty($_POST['Minutes'])
                    or empty($_POST['Location'])
                    or empty($_POST['Issue'])) {
                        echo "<font color='red'>Please fill all the mandatory fields!</font><br><br>
                        <a href='./CreateEvent.php'>< Back to \"Create Event\"</a><br>";
                    }
            else {
            // fills the events table in the database
            $query = "INSERT INTO Events (Creator, Title, Type, Date, Time, Location, Organizer, Issue, Description)"
                      ."VALUES (\"".$_SESSION["nick"]."\","
                                ."\"".$_POST['Title']."\","
                                ."\"".$_POST['Type']."\","
                                ."\"".$_POST['SampleYear']."-".$_POST['SampleMonth']."-".$_POST['SampleDay']."\","
                                ."\"".$_POST['Hours'].":".$_POST['Minutes'].":"."00"."\","
                                ."\"".$_POST['Location']."\","
                                ."\"".$_POST['Organizer']."\","
                                ."\"".$_POST['Issue']."\","
                                ."\"".$_POST['Description']."\");";

            //echo $query;
            mysql_query($query);
           }
    ?>