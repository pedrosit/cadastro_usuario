<?php
require '../model/Papel.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $acao = $_POST['acao'];

  if ($acao === 'editar') {
  } elseif ($acao === 'deletar') {
    $id = $_POST['id'];
    if ($id) {
      $papel = new Papel();
      $papel->setId($id);
      $papel->deletarPapel();
      echo json_encode(['status' => 'success', 'message' => 'Papel deletado', 'id' => $id]);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
    }
  } else {
    $papel = new Papel();
    $papel->setPapel($_POST['papel']);
    $papel->salvarPapel();
    echo json_encode(['status' => 'success', 'message' => 'Papel criado', 'papel' => $_POST['papel']]);
  }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $papel = new Papel();
  $papeis = $papel->listarPapeis();
  echo json_encode($papeis);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido']);
}
?>