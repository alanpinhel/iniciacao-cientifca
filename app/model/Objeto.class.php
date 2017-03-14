<?php
/**
 * Active Record para Objeto
 */
class Objeto extends TRecord
{
    const TABLENAME  = 'objeto';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';
  
    private $usuario;
    private $fotos;
    private $cor;
    private $tipo;
  
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
  
    /**
     * Retorna celular do usuario
     */
    function get_usuario_celular()
    {
        if (empty($this->usuario))
        {
            $this->usuario = new Usuario($this->usuario_id);
        }
        
        return $this->usuario->celular;
    }
  
    /**
     * Retorna o nome da cor
     */
    function get_cor_nome()
    {
        if (empty($this->cor))
        {
            $this->cor = new Cor($this->cor_id);
        }
        
        return $this->cor->nome;
    }
  
    /**
     * Retorna o código RGB da cor
     */
    function get_cor_rgb()
    {
        if (empty($this->cor))
        {
            $this->cor = new Cor($this->cor_id);
        }
    
        return $this->cor->rgb;
    }
  
    /**
     * Retorna o nome do tipo
     */
    function get_tipo_nome()
    {
        if (empty($this->tipo))
        {
            $this->tipo = new Tipo($this->tipo_id);
        }
        
        return $this->tipo->nome;
    }
  
    /**
     * Retorna a descrição do status
     */
    function get_status_descricao()
    {
        switch ($this->status)
        {
            case 'A':
                return _t('ANALISANDO');
                break;
            case 'B':
                return _t('BLOQUEADO');
                break;
            case 'D':
                return _t('DISPONIVEL');
                break;
            case 'N':
                return _t('NEGOCIANDO');
                break;
            case 'T':
                return _t('TRANSPORTANDO');
                break;
            case 'I':
                return _t('INDISPONIVEL');
                break;
        }
    }
  
    /**
     * Muda status da doação verificando se a mudança faz sentido
     */
    public function mudarStatus($status)
    {
        if ($this->status == 'A')
        {
            if ($status == 'B')
            {
                $this->status = $status;
                return true;
            }
            elseif ($status == 'D')
            {
                $this->status = $status;
                return true;
            }
        }
        elseif ($this->status == 'D')
        {
            if ($status == 'N')
            {
                $this->status = $status;
                return true;
            }
        }
        elseif ($this->status == 'N')
        {
            if ($status == 'D')
            {
                $this->status = $status;
                return true;
            }
            elseif ($status == 'T')
            {
                $this->status = $status;
                return true;
            }
            elseif ($status == 'I')
            {
                $this->status = $status;
                return true;
            }
        }
        elseif ($this->status == 'T')
        {
            if ($status == 'I')
            {
                $this->status = $status;
                return true;
            }
        }
    
        return false;
    }
  
    /**
     * Reinicia composição
     */
    public function clearParts()
    {
        $this->fotos = array();
    }
  
    /**
     * Adiciona uma foto
     */
    public function addFoto(Foto $foto)
    {
        $this->fotos[] = $foto;
    }
  
    /**
     * Retorna um vetor de fotos
     */
    public function getFotos()
    {
        return $this->fotos;
    }
  
    /**
     * Carrega do banco de dados
     */
    public function load($id)
    {
        $this->fotos = parent::loadComposite('Foto', 'objeto_id', $id);
        
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
        
        parent::saveComposite('Foto', 'objeto_id', $this->id, $this->fotos);
    }
  
    /**
     * Deleta do banco de dados
     */
    public function delete($id = NULL)
    {
        parent::deleteComposite('Foto', 'objeto_id', $this->id);
        
        // Apaga o próprio objeto
        parent::delete($id);
    }
}
?>