<?php
    #1. Start session
    session_start();
    
    #2. Clear session
    unset($_SESSION['login']);
    session_destroy();
    header('location: ex00_Login.php');
    exit();