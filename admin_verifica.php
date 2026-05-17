<?php
if(!isset($_SESSION['usuario_id'])){
    header("Location: login.php");
    exit;
}

if($_SESSION['tipo'] != 'admin'){
    header("Location: index.php");
    exit;
}
?>