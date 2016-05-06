<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../config/db.php';

if (isset($_GET['edit'])) {
    $editid = $_POST['editid'];
    $editcode = $_POST['editcode'];
    $editname = $_POST['editname'];
    
    $updateSql = "UPDATE employeeTypes SET code='". $editcode. "', "
            . "name ='" .$editname. "' where id='". $editid. "'";
    
    if (mysqli_query($link, $updateSql)) {
        unset($_SESSION['addEmpTypeSuccess']);
        unset($_SESSION['updateEmpTypeError']);
        $_SESSION['updateEmpTypeSuccess'] = "Record updated successfully";
        header("Location: accountSettings.php");
    } else {
        echo "Error updating record: " . mysqli_error($link);
    }

} else if (isset($_GET['delete'])) {
    $deletesql = "DELETE FROM employeeTypes where id ='". $_GET['id']."'";
    if (mysqli_query($link, $deletesql)) {
        unset($_SESSION['addEmpTypeSuccess']);
        unset($_SESSION['addEmpTypeError']);
        unset($_SESSION['updateEmpTypeError']);
        $_SESSION['updateEmpTypeSuccess'] = "Record deleted successfully";
        header("Location: accountSettings.php");
    } 
} else if (isset($_GET['add'])) {
    if(empty($_POST['code']) || empty($_POST['name']) ) {
        unset($_SESSION['addEmpTypeSuccess']);
        unset($_SESSION['updateEmpTypeError']);
        $_SESSION['addEmpTypeError'] = "Empty field(s)";
        header('Location: accountSettings.php');
    } else {        
        $code = $_POST['code'];
        $name = $_POST['name'];
        
        $editid = $_POST['editid'];
        if (empty($editid)) {
            $qry = "Select * from employeeTypes where code ='". $code."'";

            $result = mysqli_query($link, $qry);
            if (!mysqli_query($link,$qry))
            {
                echo("Error description: " . mysqli_error($link));
            } else {
                if ($result->num_rows != 0) {
                    unset($_SESSION['updateEmpTypeSuccess']);
                    unset($_SESSION['addEmpTypeSuccess']);
                    unset($_SESSION['updateEmpTypeError']);
                    $_SESSION['addEmpTypeError'] = "Code already exists";
                    header('Location: accountSettings.php');
                } else {               

                    unset($_SESSION['addEmpTypeError']);
                    unset($_SESSION['updateEmpTypeSuccess']);
                    unset($_SESSION['updateEmpTypeError']);
                     $sql = "INSERT INTO employeeTypes (code, name) "
                        . "VALUES ('$code', '$name');";

                    mysqli_query($link, $sql);
                    $_SESSION['addEmpTypeSuccess'] = "Employee type successfully added";
                    header('Location: accountSettings.php');


                } 
            }
        } else {
            $updateSql = "UPDATE employeeTypes SET code='". $code. "', "
            . "name ='" .$name. "' where id='". $editid. "'";
            
            if (mysqli_query($link, $updateSql)) {
                unset($_SESSION['addEmpTypeError']);
                unset($_SESSION['addEmpTypeSuccess']);
                unset($_SESSION['updateEmpTypeError']);
                $_SESSION['updateEmpTypeSuccess'] = "Record updated successfully";
                header("Location: accountSettings.php");
            } else {
                echo "Error updating record: " . mysqli_error($link);
            }
        }
    }
} else if (isset($_GET['save'])) {
    $typesql = "Select * from employeeTypes";
    $typeresult = mysqli_query($link, $typesql);
    
    if (!mysqli_query($link,$typesql))
        {
            echo("Error description: " . mysqli_error($link));
        } else {
            $val = "";
            $count = 1;
            while($typerow = mysqli_fetch_assoc($typeresult)) {
                $code = $typerow['code'];
                $varArr = $_POST[$code];
                $var = "";

                for($i = 0; $i < count($varArr); $i++) {
                    $var .= $varArr[$i];

                    if ($i+1 !== count($varArr)) {
                        $var.=",";
                    }
                }
                $val.= $code . "=". $var."&";
            }
            
            $checkSql = "Select * from settings where type='account'";
            $result = mysqli_query($link, $checkSql);

            if (!mysqli_query($link,$checkSql)) {
                echo("Error description: " . mysqli_error($link));
            } else {
                unset($_SESSION['updateEmpTypeError']);
                $sql = "";
                if ($result->num_rows == 0) {
                    $sql = "INSERT INTO settings (type, value) VALUES"
                            . "('account','$val')";
                } else {
                    $sql = "Update settings set value='".$val."' where type='account'";
                }
                if (mysqli_query($link, $sql)) {
                    $_SESSION['updateAccSetSuccess'] = "Changes saved successfully";
                    header("Location: accountSettings.php");
                } else {
                    echo "Error updating record: " . mysqli_error($link);
                }

            }
        }
    
    
}
