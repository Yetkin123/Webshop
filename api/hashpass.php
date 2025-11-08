<?php
$hashedPassword = password_hash('yes', PASSWORD_DEFAULT);
echo $hashedPassword;
?>