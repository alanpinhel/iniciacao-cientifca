<?php
/**
 * Active Record para Usuario
 */
class Usuario extends TRecord
{
    const TABLENAME  = 'usuario';
    const PRIMARYKEY = 'id';
    const IDPOLICY   = 'serial';
  
    private $categoria;
  
    /**
     * Retorna o nome da categoria
     */
    function get_categoria_nome()
    {
        if (empty($this->categoria))
        {
            $this->categoria = new Categoria($this->categoria_id);
        }
      
        return $this->categoria->nome;
    }
  
    /**
     * Gera código de verificao aleatorio de 6 digitos
     */
    public function gerarCodigoVerificacao()
    {
        $this->codigo_verificacao = rand(100000,999999); 
    }
  
    /**
     * Ativa usuário caso o codigo de verificacao informado
     * seja igual ao gerado pelo cadastro
     */
    public function ativar($codigo_verificacao)
    {
        if ($this->codigo_verificacao == $codigo_verificacao)
        {
            $this->ativo = 1;
            return true;
        }
        else
        {
            $this->ativo = 0;
            return false;
        }
    }
  
    /**
     * Autentica Usuário
     */
    public static function autenticate($celular, $senha)
    {
        $usuario = self::newFromLogin($celular);
        
        if ($usuario instanceof Usuario)
        {
            if (isset($usuario->senha) AND ($usuario->senha == $senha))
            {
                return true;
            }
            else
            {
                throw new Exception(_t('Senha incorreta'));
            }
        }
        else
        {
            throw new Exception(_t('Usuário não encontrado'));
        }
    }
    
    /**
     * Retorna uma instância de usuário a partir do celular
     */
    public static function newFromLogin($celular)
    {
        $repos = new TRepository('Usuario');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('celular', 'LIKE', $celular));
        $criteria->add(new TFilter('ativo', '=', 1));
        $objects = $repos->load($criteria);
        
        if (isset($objects[0]))
        {
            return $objects[0];
        }
    }
  
    /**
     * Envia codigo de verificao por SMS
     */
    public function enviarCodigoVerificacao()
    {
        // caso ocorra erros no servidor de SMS (LocaSMS.com.br), o php retorna um Warning,
        // portanto, nesse caso a parte foi usado o '@' para oculta-la
        @$status = substr(file_get_contents("http://209.133.196.250/painel/api.ashx?action=sendsms&lgn=14991940268&pwd=207604&msg=Codigo%20de%20verificacao%20do%20dadivar%20e%20$this->codigo_verificacao&numbers=$this->celular"), 10, 1); 
      
        // Se o status retornado da API for 1, é questão que enviou corretamente
        if($status == 1)
        {
            return true;
        }
        else
        {
            throw new Exception(_t('Não foi possível enviar SMS'));
        }
    }
  
    /**
     * Criptografa senha
     */
    public function criptografar()
    {
        $this->senha = md5($this->senha);
    }
}
?>