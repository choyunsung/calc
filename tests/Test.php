<?php
/**
 * Created by PhpStorm.
 * User: choyunsung
 * Date: 2018. 8. 3.
 * Time: AM 9:59
 */

include '../vendor/autoload.php';
echo PHP_INT_MAX."<br><br>";
echo "+:    ".steven\calc('111111111111111.10987654321 + 111111.22',8)."<br>";
echo "/:    ".steven\calc('111111111.1111111111 / 111111111.2222222222',8)."<br>";
echo "*:    ".steven\calc('111111111.1111111111 * 1111111.22',8)."<br>";
echo "-:    ".steven\calc('111111111.1111111111 - 111111111.22',8)."<br>";