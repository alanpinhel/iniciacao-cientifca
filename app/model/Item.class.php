<?php
/**
 * Active Record para Item
 */
class Item extends TRecord
{
    const TABLENAME  = 'item';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';

    private $doacao;
    private $objeto;
  
    /**
     * Retorna doacao
     */
    function getDoacao()
    {
        if (empty($this->doacao))
        {
            $this->doacao = new Doacao($this->doacao_id);
        }
        
        return $this->doacao;
    }

    /**
     * Retorna objeto
     */
    function getObjeto()
    {
        if (empty($this->objeto))
        {
            $this->objeto = new Objeto($this->objeto_id);
        }
        
        return $this->objeto;
    }

    /** 
     * Retorna a descrição do status
     */
    function get_status_descricao()
    {
        switch ($this->status) 
        {
            case 'P':
                return _t('PENDENTE');
                break;
            case 'A':
                return _t('ACEITO');
                break;
            case 'R':
                return _t('RECUSADO');
                break;
        }
    }
  
    /**
     * Muda status do item verificando se mudança faz sentido
     */
    public function mudarStatus($status)
    {
        if ($this->status == 'P')
        {
            if ($status == 'A')
            {
                $this->status = $status;
                return true;
            }
            elseif ($status == 'R')
            {
                $this->status = $status;
                return true;
            }
        }
        return false;
    }
}
?>