<?php
require '../model/Usuario.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

try {
  if ($method === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'login') {
    $email = $_POST['emailLogin'] ?? '';
    $senha = $_POST['senhaLogin'] ?? '';

    if ($email && $senha) {
      $usuario = new Usuario();
      $usuario->setEmail($email);
      $resultado = $usuario->autenticar($senha);
      echo json_encode($resultado);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Email ou senha ausentes']);
    }
  }

  if ($method === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'editar') {
      $id = $_POST['id'] ?? '';
      if ($id) {
        $usuario = new Usuario();
        $usuario->setId($id);
        $usuario->setNome($_POST['nome'] ?? '');
        $usuario->setEmail($_POST['email'] ?? '');
        $usuario->setSenha(password_hash($_POST['senha'] ?? '', PASSWORD_DEFAULT));
        $usuario->setPapel($_POST['papel'] ?? '');
        $resultado = $usuario->salvarUsuario();
        echo json_encode($resultado);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
      }
    } elseif ($acao === 'deletar') {
      $id = $_POST['id'] ?? '';
      if ($id) {
        $usuario = new Usuario();
        $usuario->setId($id);
        $resultado = $usuario->deletarUsuario();
        echo json_encode($resultado);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
      }
    } else {
      $usuario = new Usuario();
      $usuario->setNome($_POST['nome'] ?? '');
      $usuario->setEmail($_POST['email'] ?? '');
      $usuario->setSenha(password_hash($_POST['senha'] ?? '', PASSWORD_DEFAULT));
      $usuario->setPapel($_POST['papel'] ?? '');
      $resultado = $usuario->salvarUsuario();
      echo json_encode($resultado);
    }
  } elseif ($method === 'GET') {
    $usuario = new Usuario();
    $usuarios = $usuario->listarUsuarios();
    echo json_encode($usuarios);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido']);
  }
} catch (Exception $e) {
  echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>