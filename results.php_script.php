<?php
/*
  results.php_script.php
  Discusss
  (c) Donald Leung.  All rights reserved.
  Released under the Creative Commons Attribution 3.0 License
*/

# Note: Do NOT edit UNLESS you know PHP OR the comments say otherwise

# Variables - Feel free to edit
$required_fields_not_filled_in_message = '<h2>Required Fields Not Filled In</h2><p>Sorry, it seems that you have left out required fields.  Why don\'t you <a href="create_discussion.php">fill in the form properly</a> and try again?</p>'; // Display this message if the user did not fill in all the fields in the previous discussion form
$page_not_accessed_properly_message = '<h2>Invalid Request</h2><p>Sorry, this page was not accessed properly.  Please <a href="create_discussion.php">fill in the form</a> and try again.</p>'; // Display this message if the user tries to go to this page directly without passing through the discussion form
$site_name = "Discusss"; // The title of the page when the discussion/post is generated
$invalid_email_address_message = "<h2>Invalid Email Address</h2><p>Sorry, it seems that the email you provided is invalid.  Are you sure you have typed it in correctly?  Please <a href='create_discussion.php'>go back</a> and try again.</p>";

# Checking if page is accessed properly
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['title']) || empty($_POST['content'])) {
    echo $required_fields_not_filled_in_message;
  } else {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    # Form Security (IMPORTANT) - 2 methods, you choose

    # Method 1 - Use PHP's built-in filters (removes all html tags) - this is the default setting, comment out if you don't want this feature enabled BUT REMEMBER to uncomment Method 2; otherwise your site could be hacked through XSS (Cross-Site Scripting) or direct PHP injection
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $content = filter_var($content, FILTER_SANITIZE_STRING);

    # Method 2 - Neutralise all HTML tags by converting them into HTML special characters (e.g. the "<" character is converted into "&lt;" so the HTML tags are literally displayed to the screen)
    # This method is commented out - uncomment it to enable BUT if you do so REMEMBER to comment out Method 1; otherwise, some content display errors may occur
    /*
      function prevent_hack($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
      $name = prevent_hack($name);
      $email = prevent_hack($email);
      $title = prevent_hack($title);
      $content = prevent_hack($content);
    */

    # Validating Email Address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
      $filename_clash_prevention_value = rand();
      $final_filename = $filename_clash_prevention_value . "_" . $title . ".php";
      $final_txt_filename = $filename_clash_prevention_value . "_" . $title . ".txt";
      $final_comments_filename = $filename_clash_prevention_value . "_" . $title . ".comments.html";
      $discussion_php = fopen($final_filename, "w");
      fwrite($discussion_php, '<!DOCTYPE HTML>
      <!--
      	HTML + CSS + Javascript + Skel Framework by ...
      	Future Imperfect by HTML5 UP
      	html5up.net | @n33co
      	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
      -->
      <!--
      	PHP Functionality by ...
      	Discusss
      	(c) Donald Leung.  All rights reserved.
      	Released under the Creative Commons Attribution 3.0 License
      -->
      <html>
      	<head>
      		<title>' . $site_name . ' - ' . $title . '</title>
      		<meta charset="utf-8" />
      		<meta name="viewport" content="width=device-width, initial-scale=1" />
      		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
      		<link rel="stylesheet" href="assets/css/main.css" />
      		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
      		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
      	</head>
      	<body>

      		<!-- Wrapper -->
      			<div id="wrapper">

      				<!-- Header -->
      					<' . '?php require "header.php"; ?' . '>

      				<!-- Main -->
      					<div id="main">

      							<article class="post">
                      <header>
                        <div class="title">
                          <h2>' . $title . '</h2>
                        </div>
                        <div class="meta">
                          <time class="published">' . date('d/m/Y h:i a') . '</time>
                          <a href="mailto:' . $email . '" class="author"><span class="name">' . $name . '</span></a>
                        </div>
                      </header>
                      <' . '?php
                      $' . 'content_file = fopen("' . $final_txt_filename . '", "r") or die("Unable to open file!");
                      while (!feof($' . 'content_file)) {
                        echo "<p>" . fgets($' . 'content_file) . "</p>";
                      }
                      fclose($' . 'content_file);
                      ?' . '>
                      <h2>Comments</h2>
                      <ul class="alt">
                        <' . '?php
                        $' . 'comments_file = fopen("' . $final_comments_filename . '", "r") or die("Unable to open file!");
                        if (filesize("' . $final_comments_filename . '") > 0) {
                          echo fread($' . 'comments_file, filesize("' . $final_comments_filename . '"));
                        } else {
                          echo "<li>There are currently no comments in this discussion.</li>";
                        }
                        fclose($' . 'comments_file);
                        ?' . '>
                      </ul>
                      <footer>
                        <ul class="actions">
                          <li><a href="comment.php?file=' . $final_comments_filename . '" class="button big">Leave a Comment</a></li>
                        </ul>
                        <ul class="stats">
      										<li><a href="flag.php?file=' . $final_filename . '" class="icon fa-flag">Flag Post</a></li>
      									</ul>
                      </footer>
      							</article>

      					</div>

      			</div>

      		<!-- Scripts -->
      			<script src="assets/js/jquery.min.js"></script>
      			<script src="assets/js/skel.min.js"></script>
      			<script src="assets/js/util.js"></script>
      			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
      			<script src="assets/js/main.js"></script>

      	</body>
      </html>');
      fclose($discussion_php);
      $discussion_content = fopen($final_txt_filename, "w");
      fwrite($discussion_content, $content);
      fclose($discussion_content);
      $discussion_comments = fopen($final_comments_filename, "w");
      fclose($discussion_comments);
      $post_links = fopen("post_links.html", "a");
      fwrite($post_links, '<li>
        <article>
          <header>
            <h3><a href="' . $final_filename . '">' . $title . '</a></h3>
            <time class="published">' . date('d/m/Y h:i a') . '</time>
          </header>
        </article>
      </li>');
      fclose($post_links);
      $post_summaries = fopen("post_summaries.html", "a");
      $reopen_discussion_content = fopen($final_txt_filename, "r") or die("Unable to open file!");
      if (filesize($final_txt_filename) <= 250) {
        $summary_content = fread($reopen_discussion_content, filesize($final_txt_filename));
      } else {
        $summary_content = '';
        for ($i = 0; $i < 250; $i++) {
          $summary_content .= fgetc($reopen_discussion_content);
        }
        $summary_content .= ' ... ';
      }
      fclose($reopen_discussion_content);
      fwrite($post_summaries, '<article class="post">
        <header>
          <div class="title">
            <h2><a href="' . $final_filename . '">' . $title . '</a></h2>
          </div>
          <div class="meta">
            <time class="published">' . date('d/m/Y h:i a') . '</time>
            <a href="mailto:' . $email . '" class="author"><span class="name">' . $name . '</span></a>
          </div>
        </header>
        ' . '<p>' .  $summary_content . '</p>' . '
        <footer>
          <ul class="actions">
            <li><a href="' . $final_filename . '" class="button big">View Full Discussion</a></li>
          </ul>
        </footer>
      </article>');
      fclose($post_summaries);

      # Success Message - you CAN edit the HTML CONTENT ONLY but AT YOUR OWN RISK.  Do NOT edit ANY variables passed into this success message (e.g. $final_filename)
      echo '<h2>Discussion Successfully Created</h2><p>Thank you for creating a discussion.  Your <a href="' . $final_filename . '">discussion</a> has been successfully created.  Feel free to check it out.  Alternatively, you can return to the homepage.</p><p><a href="index.php" class="button">Return to Homepage</a></p>';

    } else {
      echo $invalid_email_address_message;
    }
  }
} else {
  echo $page_not_accessed_properly_message;
}
?>
