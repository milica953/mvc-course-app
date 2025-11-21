<?php
session_start();


session_unset();
session_destroy();


header("Location: /mvc-course-app/index.php");
exit();
?>
