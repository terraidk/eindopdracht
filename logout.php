<?php
session_start();
session_destroy();
header("Location: eindopdracht.php");
?>