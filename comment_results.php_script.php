<?php
/*
  comment_results.php_script.php
  Discusss
  (c) Donald Leung.  All rights reserved.
  Released under the Creative Commons Attribution 3.0 License
*/
/*
  N.B. Do NOT edit anything from this script UNLESS the comments say otherwise OR you know PHP
*/

# Variables - Feel free to edit this section
$invalid_request_message = '<h2>Invalid Request</h2><p>Sorry, it seems that you have not accessed this page properly.  Please fill in the commenting form properly and try again.</p>';
$discussion_not_specified_message = '<h2>Discussion Not Specified</h2><p>Sorry, it seems that you have not chosen the discussion to comment on before you started filling in the commenting form.  Please do it properly and try again.</p>';
$required_fields_not_filled_in_message = '<h2>Required Fields Not Filled In</h2><p>Sorry, it seems that you have left some parts of the form blank.  Please <a href="comment.php?file=' . $file . '">fill it in properly</a> and try again.</p>';
$invalid_email_address_message = '<h2>Invalid Email Address</h2><p>Sorry, it seems that you have provided an invalid email address.  Please <a href="comment.php?file=' . $file . '">provide a valid email address</a> and try again.</p>';
$success_message = '<h2>Commenting Successful</h2><p>Your commenting request was successful and your comment has been added to the discussion.</p><p><a href="index.php" class="button">Return to Homepage</a></p>';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (empty($file)) {
    echo $discussion_not_specified_message;
  } else {
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['comment'])) {
      echo $required_fields_not_filled_in_message;
    } else {
      $name = $_POST['name'];
      $email = $_POST['email'];
      $comment = $_POST['comment'];

      # Form Security

      # Method 1 (Default) - Use PHP built-in filters.  Removes HTML tags entirely.  Comment out ONLY IF you want to use Method 2 instead.  If you comment out both methods, your site is vulnerable to XSS attacks and direct PHP injection.
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $email = filter_var($email, FILTER_SANITIZE_EMAIL);
      $comment = filter_var($comment, FILTER_SANITIZE_STRING);

      # Method 2 - Use htmlspecialchars() to convert HTML tags and <script> tags into harmless characters.  If you decide to enable Method 2 REMEMBER to comment out Method 1
      /*
      function prevent_hack($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
      $name = prevent_hack($name);
      $email = prevent_hack($email);
      $comment = prevent_hack($comment);
      */

      if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $edit_comment_file = fopen($file, "a");
        fwrite($edit_comment_file, '<li><div class="row"><div class="4u 12u$(small)"><h3><a href="mailto:' . $email . '">' . $name . '</a></h3><time class="published">' . date('d/m/Y h:i a') . '</time></div><div class="8u 12u$(small)"><p>' . $comment . '</p></div></div></li>');
        fclose($edit_comment_file);
        echo $success_message;
      } else {
        echo $invalid_email_address_message;
      }
    }
  }
} else {
  echo $invalid_request_message;
}
?>
