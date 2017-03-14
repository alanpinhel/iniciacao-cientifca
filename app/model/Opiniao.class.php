<?php
/**
 * Active Record para Opiniao
 */
class Opiniao extends TRecord
{
    const TABLENAME  = 'opiniao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';
  
    private $usuario;
  
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
}
?>