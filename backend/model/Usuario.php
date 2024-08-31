<?php
class Usuario
{
  private $id;
  private $email;
  private $nome;
  private $senha;
  private $papel;

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getId()
  {
    return $this->id;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function setNome($nome)
  {
    $this->nome = $nome;
  }

  public function getNome()
  {
    return $this->nome;
  }

  public function setSenha($senha)
  {
    $this->senha = $senha;
  }

  public function getSenha()
  {
    return $this->senha;
  }

  public function setPapel($papel)
  {
    $this->papel = $papel;
  }

  public function getPapel()
  {
    return $this->papel;
  }

  public function salvarUsuario()
  {
    try {
      $conexao = new Conexao();
      $conn = $conexao->getConexao();
      if ($this->id) {
        $stmt = $conn->prepare("UPDATE usuario SET nome = ?, email = ?, senha = ?, papel = ? WHERE id_usuario = ?");
        $stmt->execute([$this->getNome(), $this->getEmail(), $this->getSenha(), $this->getPapel(), $this->getId()]);
      } else {
        $stmt = $conn->prepare("INSERT INTO usuario (nome, email, senha, papel) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->getNome(), $this->getEmail(), $this->getSenha(), $this->getPapel()]);
      }
      return ['status' => 'success', 'message' => 'Usuário salvo com sucesso'];
    } catch (PDOException $th) {
      return ['status' => 'error', 'message' => $th->getMessage()];
    }
  }

  public function deletarUsuario()
  {
    try {
      $conexao = new Conexao();
      $conn = $conexao->getConexao();
      $stmt = $conn->prepare("DELETE FROM usuario WHERE id_usuario = ?");
      $stmt->execute([$this->getId()]);
      return ['status' => 'success', 'message' => 'Usuário deletado com sucesso'];
    } catch (PDOException $th) {
      return ['status' => 'error', 'message' => $th->getMessage()];
    }
  }

  public static function listarUsuarios()
  {
    try {
      $conexao = new Conexao();
      $conn = $conexao->getConexao();
      $stmt = $conn->query("SELECT * FROM usuario");
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $th) {
      return ['status' => 'error', 'message' => $th->getMessage()];
    }
  }

  public function listarUsuarioPorId()
  {
    try {
      $conexao = new Conexao();
      $conn = $conexao->getConexao();
      $stmt = $conn->prepare("SELECT u.id_usuario, u.nome, u.email, p.papel FROM usuario u INNER JOIN papel p ON u.id_papel = p.id_papel WHERE u.id_usuario = ?");
      $stmt->execute([$this->getId()]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result;
    } catch (PDOException $th) {
      return ['status' => 'error', 'message' => $th->getMessage()];
    }
  }
  public function autenticar($senha)
  {
    try {
      $conexao = new Conexao();
      $conn = $conexao->getConexao();
      $stmt = $conn->prepare("SELECT * FROM usuario WHERE email = ?");
      $stmt->execute([$this->getEmail()]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user && password_verify($senha, $user['senha'])) {
        return ['status' => 'success', 'message' => 'Usuário autenticado com sucesso', 'usuario' => $user];
      } else {
        return ['status' => 'error', 'message' => 'Email ou senha incorretos'];
      }
    } catch (PDOException $th) {
      return ['status' => 'error', 'message' => $th->getMessage()];
    }
  }

}
?>