<?php
class Conexao
{
    public function getConexao()
    {
        return new PDO("mysql:host=localhost;dbname=TrabalhoPHP", "root", "");
    }
}
?>