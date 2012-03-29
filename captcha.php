<?php 
   session_start(); 
   unset($_SESSION['captcha_spam']); 

   function randomString($len) { 
      function make_seed(){ 
         list($usec , $sec) = explode (' ', microtime()); 
         return (float) $sec + ((float) $usec * 100000); 
      } 
      srand(make_seed());  
      //Der String $possible enth�lt alle Zeichen, die verwendet werden sollen 
      $possible="123456789"; 
      $str=""; 
      while(strlen($str)<$len) { 
        $str.=substr($possible,(rand()%(strlen($possible))),1); 
      } 
   return($str); 
   } 

   $text = randomString(5);  //Die Zahl bestimmt die Anzahl stellen 
   $_SESSION['captcha_spam'] = $text; 
          
   header('Content-type: image/png'); 
   $img = ImageCreateFromPNG('captcha/captcha.PNG'); //Backgroundimage
   $color = ImageColorAllocate($img, 0, 0, 0); //Farbe 
   $ttf = $_SERVER['DOCUMENT_ROOT']."/php_zeugs/schlafanalysator/captcha/XFILES.TTF"; //Schriftart
   echo $ttf . "<p>";
   $ttfsize = 25; //Schriftgr�sse 
   $angle = rand(0,5); 
   $t_x = rand(5,30); 
   $t_y = 35; 
   imagettftext($img, $ttfsize, $angle, $t_x, $t_y, $color, $ttf, $text); 
   imagepng($img); 
   imagedestroy($img); 
?> 
