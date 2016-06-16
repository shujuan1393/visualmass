<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../config/db.php';

$timezone = 'UTC';
if (is_link('/etc/localtime')) {
    // Mac OS X (and older Linuxes)    
    // /etc/localtime is a symlink to the 
    // timezone in /usr/share/zoneinfo.
    $filename = readlink('/etc/localtime');
    if (strpos($filename, '/usr/share/zoneinfo/') === 0) {
        $timezone = substr($filename, 20);
    }
} elseif (file_exists('/etc/timezone')) {
    // Ubuntu / Debian.
    $data = file_get_contents('/etc/timezone');
    if ($data) {
        $timezone = $data;
    }
} elseif (file_exists('/etc/sysconfig/clock')) {
    // RHEL / CentOS
    $data = parse_ini_file('/etc/sysconfig/clock');
    if (!empty($data['ZONE'])) {
        $timezone = $data['ZONE'];
    }
}
 
date_default_timezone_set($timezone);
$time = date("H");

$inv = "Select * from inventory";
$invres = mysqli_query($link, $inv);

if (!mysqli_query($link, $inv)) {
    die(mysqli_error($link));
} else {
    while($row = mysqli_fetch_assoc($invres)) {
        $qty = $row['quantity'];
        
        if (intval($qty) === 10) {
            $getSupplier = "Select * from partners where pid='".$row['pid']."';";
        }
    }
}