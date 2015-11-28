<?php
/*
  search.php
  Discusss
  (c) Donald Leung.  All rights reserved.
  MIT Licensed
*/

# Note from creator of Discusss: feel free to edit this file however you like UNLESS you have no clue as to how PHP works

# Variables - feel free to edit
$my_website_no_http = "yourdomain.com"; // WARNING!!!  Do NOT add the http protocol in front of your website domain.

header("Location: http://google.com.hk/search?q=" . $_GET['q'] . " site:" . $my_website_no_http); die();
?>
