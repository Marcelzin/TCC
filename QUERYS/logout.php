<?php
session_start();

session_destroy();

header("Location: /TCC/index.html");

exit;
?>