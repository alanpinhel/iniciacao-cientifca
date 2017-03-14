<?php
/**
 * Active Record para Tipo
 */
class Tipo extends TRecord
{
    const TABLENAME  = 'tipo';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';
  
    private $secao;
  
    /**
     * Retorna o nome da secao
     */
    function get_secao_nome()
    {
        if (empty($this->secao))
        {
            $this->secao = new Secao($this->secao_id);
        }
        
        return $this->secao->nome;
    }
  
    /**
     * Retorna o nome do tipo pai
     */
    function get_pai_nome()
    {
        if (!empty($this->pai_id))
        {
            $pai = new Tipo($this->pai_id);
            return $pai->nome;
        }
        else
        {
            return _t('ORFAO');
        }
    }
}
?>