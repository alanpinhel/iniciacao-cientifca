<?php
/**
 * Active Record para Mensagem
 */
class Mensagem extends TRecord
{
    const TABLENAME  = 'mensagem';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';
  
    private $remetente;
  
    /**
     * Retorna o nome do usuario (remetente)
     */
    function get_usuario_nome()
    {
        if (empty($this->remetente))
        {
            $this->remetente = new Usuario($this->remetente_id);
        }
    
        return $this->remetente->nome;
    }
  
    /**
     * Retorna o sobrenome do usuário (remetente)
     */
    function get_usuario_sobrenome()
    {
        if (empty($this->remetente))
        {
            $this->remetente = new Usuario($this->remetente_id);
        }
        
        return $this->remetente->sobrenome;
    }
}
?>