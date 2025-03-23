<?php
    session_start();

    // clears all session variables
    session_unset();

    // destroy
    session_destroy();

    // Moves back to login page
    header("location:../login.php");
?>