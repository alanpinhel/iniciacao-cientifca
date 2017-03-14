<?php
/**
 * Active Record para Conversa
 */
class Conversa extends TRecord
{
    const TABLENAME  = 'conversa';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial'; // {max: Ult. Registro +1, serial: Deixa BD no controle}
  
    private $mensagens;
    private $usuarios;
  
    /**
     * Reinicia composição e agregação
     */
    public function clearParts()
    {
        $this->mensagens = array();
        $this->usuarios  = array();
    }
  
    /**
     * Adiciona uma mensagem
     */
    public function addMensagem(Mensagem $mensagem)
    {
        $this->mensagens[] = $mensagem;
    }
  
    /**
     * Retorna um vetor de mensagens
     */
    public function getMensagens()
    {
        return $this->mensagens;
    }
  
    /**
     * Adiciona um usuário
     */
    public function addUsuario(Usuario $usuario)
    {
        $this->usuarios[] = $usuario;
    }
  
    /**
     * Retorna um vetor de usuarios
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }
    
    /**
     * Carrega do banco de dados
     */
    public function load($id)
    {
        $this->mensagens = parent::loadComposite('Mensagem', 'conversa_id', $id);
        $this->usuarios  = parent::loadAggregate('Usuario', 'Integrante', 'conversa_id', 'usuario_id', $id);
        
        // Carrega o próprio objeto
        return parent::load($id);
    }
  
    /**
     * Salva no banco de dados
     */
    public function store()
    {
        // Armazena o próprio objeto
        parent::store();
        
        parent::saveComposite('Mensagem', 'conversa_id', $this->id, $this->mensagens);
        parent::saveAggregate('Integrante', 'conversa_id', 'usuario_id', $this->id, $this->usuarios);
    }

    /**
     * Deleta do banco de dados
     */
    public function delete($id = NULL)
    {
        parent::deleteComposite('Mensagem', 'conversa_id', $this->id);
        parent::deleteComposite('Integrante', 'conversa_id', $this->id);
    
        // Apaga o próprio objeto
        parent::delete($id);
    }
}
?>