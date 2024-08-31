<?php
require '../Conexao.php';

class Papel
{
  private $id;
  private $papel;

  public function getPapel()
  {
    return $this->papel;
  }

  public function setPapel($papel)
  {
    $this->papel = $papel;
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function salvarPapel()
  {
    try {
      $conexao = new Conexao();
      $conn = $conexao->getConexao();
      $stmt = $conn->prepare("INSERT INTO papel (papel) VALUES (?)");
      $stmt->execute([$this->getPapel()]);
    } catch (PDOException $th) {
      return ['status' => 'error', 'message' => $th->getMessage()];
    }
    return ['status' => 'success', 'message' => 'Papel salvo com sucesso'];
  }

  public function listarPapeis()
  {
    try {
      $conexao = new Conexao();
      $conn = $conexao->getConexao();
      $stmt = $conn->query("SELECT * FROM papel");
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $th) {
      return ['status' => 'error', 'message' => $th->getMessage()];
    }
  }

  public function deletarPapel()
  {
    try {
      $conexao = new Conexao();
      $conn = $conexao->getConexao();
      $stmt = $conn->prepare("DELETE FROM papel WHERE id_papel = :id");
      $stmt->bindParam(":id", $this->getId(), PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $th) {
      return ['status' => 'error', 'message' => $th->getMessage()];
    }
    return ['status' => 'success', 'message' => 'Papel deletado com sucesso'];
  }
}
?>