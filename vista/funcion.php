<?php
$globalVar=10;

function myfunction(){
    global $globalVar;
    echo $globalVar;

}
?>