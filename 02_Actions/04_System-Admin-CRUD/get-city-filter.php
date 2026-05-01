<?php
include_once '../00_Config/config.php';

header('Content-Type: application/json');

$province = $_GET['province'] ?? '';

if (empty($province)) {
  echo json_encode([]);
  exit;
}

$stmt = $pdo->prepare("SELECT DISTINCT City FROM users WHERE Province = ? ORDER BY City");
$stmt->execute([$province]);
$cities = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($cities);