<?php
/**
 * Active Record para Doacao
 */
class Doacao extends TRecord
{
    const TABLENAME  = 'doacao';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';
  
    private $usuario;
    private $objetos;
    private $itens;
  
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
     * Retorna o sobrenome do usuário
     */
    function get_usuario_celular()
    {
        if (empty($this->usuario))
        {
            $this->usuario = new Usuario($this->usuario_id);
        }
        
        return $this->usuario->sobrenome;
    }
  
    /** 
     * Retorna a descrição do status
     */
    function get_status_descricao()
    {
        switch ($this->status)
        {
            case 'P':
                return 'PENDENTE';
                break;
            case 'C';
                return 'CONCLUIDA';
                break;
        }
    }
    
    /**
     * Retorna itens
     */
    public function getItens()
    {
        if (empty($this->itens))
        {
            $repos = new TRepository('Item');
      
            // define filtro
            $criteria = new TCriteria;
            $criteria->add(new TFilter('doacao_id', '=', $this->id));
      
            // recupera itens
            $this->itens = $repos->load($criteria);
      
            return $this->itens;
        }
    }
  
    /**
     * Muda status da doação verificando se a mudança faz sentido
     */
    public function mudarStatus($status)
    {
        if ($this->status == 'P')
        {
            if ($status == 'C')
            {
                $this->status = $status;
                return true;
            }
        }
    
        return false;
    }
  
    /**
     * Reinicia agregação
     */
    public function clearParts()
    {
        $this->objetos = array();
    }
  
    /**
     * Adiciona um objeto
     */
    public function addObjeto(Objeto $objeto)
    {
        $this->objetos[] = $objeto;
    }
  
    /**
     * Retorna um vetor de objetos
     */
    public function getObjetos()
    {
        return $this->objetos;
    }
  
    public function load($id)
    {
        $this->objetos = parent::loadAggregate('Objeto', 'Item', 'doacao_id', 'objeto_id', $id);
        
        // carrega o próprio objeto
        return parent::load($id);
    }
  
    public function store()
    {
        // Armazena o próprio objeto
        parent::store();
        
        parent::saveAggregate('Item', 'doacao_id', 'objeto_id', $this->id, $this->objetos);
    }
  
    public function delete($id = NULL)
    {
        parent::deleteComposite('Item', 'doacao_id', $this->id);
        
        // Apaga o próprio objeto
        parent::delete($id);
    }
}
?>