<?php 
include 'whatsapp.php';

$get = new Whatsapp();

$pesan = $get->sendMessage('089670402864', 'hallo');

var_dump($pesan);