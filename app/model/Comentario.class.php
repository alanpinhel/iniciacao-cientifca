<?php
/**
 * Active Record para Comentario
 */
class Comentario extends TRecord
{
    const TABLENAME  = 'comentario';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial'; // {max: Ult. Registro +1, serial: Deixa BD no controle}
  
    private $usuario;
    private $objeto;
  
    /**
     * Retorna o nome do usuario
     */
    function get_usuario_nome()
    {
        if (empty($this->usuario))
        {
            $this->usuario = new Usuario($this->usuario_id);
        }
        
        return $this->usuario->nome;
    }
  
    /**
     * Retorna o sobrenome do usuário
     */
    function get_usuario_sobrenome()
    {
        if (empty($this->usuario))
        {
            $this->usuario = new Usuario($this->usuario_id);
        }
        
        return $this->usuario->sobrenome;
    }
  
    /**
     * Retorna a foto do usuario
     */
    function get_usuario_foto()
    {
        if (empty($this->usuario))
        {
            $this->usuario = new Usuario($this->usuario_id);
        }
    
        return $this->usuario->foto;
    }
}
?>