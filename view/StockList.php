<?php
include $_SERVER['DOCUMENT_ROOT'] . '/database/MySQL.php';

$result = (new \Database\MySQL())->query('SELECT * FROM `stock` ORDER BY created DESC');

$data['data'] = $result->fetch_all();

echo json_encode($data);
