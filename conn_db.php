<?php
    $link = mysqli_connect('localhost', 'root', '', 'secprog_task2');
    if (!$link) {
        exit('Could not connect: ' . mysqli_connect_error());
    }
?>