<?php
/**
 * Translation utility class
 * Copyright (c) 2006-2010 Pablo Dall'Oglio
 * @author  Pablo Dall'Oglio <pablo [at] adianti.com.br>
 * @version 2.0, 2007-08-01
 */
class TApplicationTranslator
{
    private static $instance; // singleton instance
    private $lang;            // target language
    
    /**
     * Class Constructor
     */
    private function __construct()
    {
        $this->messages['en'][] = 'File not found';
        $this->messages['en'][] = 'Search';
        $this->messages['en'][] = 'Register';
        $this->messages['en'][] = 'Record saved';
        $this->messages['en'][] = 'Do you really want to delete ?';
        $this->messages['en'][] = 'Record deleted';
        $this->messages['en'][] = 'Function';
        $this->messages['en'][] = 'Table';
        $this->messages['en'][] = 'Tool';
        $this->messages['en'][] = 'Data';
        $this->messages['en'][] = 'Open';
        $this->messages['en'][] = 'New';
        $this->messages['en'][] = 'Save';
        $this->messages['en'][] = 'Find';
        $this->messages['en'][] = 'Delete';
        $this->messages['en'][] = 'Edit';
        $this->messages['en'][] = 'Cancel';
        $this->messages['en'][] = 'Yes';
        $this->messages['en'][] = 'No';
        $this->messages['en'][] = 'January';
        $this->messages['en'][] = 'February';
        $this->messages['en'][] = 'March';
        $this->messages['en'][] = 'April';
        $this->messages['en'][] = 'May';
        $this->messages['en'][] = 'June';
        $this->messages['en'][] = 'July';
        $this->messages['en'][] = 'August';
        $this->messages['en'][] = 'September';
        $this->messages['en'][] = 'October';
        $this->messages['en'][] = 'November';
        $this->messages['en'][] = 'December';
        $this->messages['en'][] = 'Today';
        $this->messages['en'][] = 'Close';
        $this->messages['en'][] = 'The field ^1 can not be less than ^2 characters';
        $this->messages['en'][] = 'The field ^1 can not be greater than ^2 characters';
        $this->messages['en'][] = 'The field ^1 can not be less than ^2';
        $this->messages['en'][] = 'The field ^1 can not be greater than ^2';
        $this->messages['en'][] = 'The field ^1 is required';
        $this->messages['en'][] = 'The field ^1 has not a valid CNPJ';
        $this->messages['en'][] = 'The field ^1 has not a valid CPF';
        $this->messages['en'][] = 'The field ^1 contains an invalid e-mail';
        $this->messages['en'][] = 'Permission denied';
        $this->messages['en'][] = 'Generate';
        $this->messages['en'][] = 'List';
        $this->messages['en'][] = 'Detail';
        $this->messages['en'][] = 'Back';
        $this->messages['en'][] = 'Clear';
        $this->messages['en'][] = 'Not logged';
        $this->messages['en'][] = 'Cell';
        $this->messages['en'][] = 'Password';
        $this->messages['en'][] = 'Language';
        $this->messages['en'][] = 'Login';
        $this->messages['en'][] = 'User not found';
        $this->messages['en'][] = 'Dashboard';
        $this->messages['en'][] = 'Portuguese';
        $this->messages['en'][] = 'English';
        $this->messages['en'][] = 'Forgot my password';
        $this->messages['en'][] = 'Incorrect password';
        $this->messages['en'][] = 'Options';
        $this->messages['en'][] = 'Sign up';
        $this->messages['en'][] = 'Welcome';
        $this->messages['en'][] = 'Visitor to options';
        $this->messages['en'][] = 'Administrator for options';
        $this->messages['en'][] = 'User for options';
        $this->messages['en'][] = 'Object catalog';
        $this->messages['en'][] = 'Create account';
        $this->messages['en'][] = 'Confirm password';
        $this->messages['en'][] = 'Logout';
        $this->messages['en'][] = 'Access information';
        $this->messages['en'][] = 'Personal data';
        $this->messages['en'][] = 'Confirmation';
        $this->messages['en'][] = 'Next';
        $this->messages['en'][] = 'Male';
        $this->messages['en'][] = 'Female';
        $this->messages['en'][] = 'Name';
        $this->messages['en'][] = 'Last name';
        $this->messages['en'][] = 'Photograph';
        $this->messages['en'][] = 'Gender';
        $this->messages['en'][] = 'Verification code';
        $this->messages['en'][] = 'Confirm';
        $this->messages['en'][] = 'Was envoy the code verification per SMS, wait a few minutes.';
        $this->messages['en'][] = 'Upload completed';
        $this->messages['en'][] = 'Could not send SMS';
        $this->messages['en'][] = 'Mandatory';
        $this->messages['en'][] = 'Visitor';
        $this->messages['en'][] = 'Catalog objects';
        $this->messages['en'][] = 'Sets administrator';
        $this->messages['en'][] = 'Registration date';
        $this->messages['en'][] = 'Category';
        $this->messages['en'][] = 'List users';
        $this->messages['en'][] = 'User form';
        $this->messages['en'][] = 'Category form';
        $this->messages['en'][] = 'Color form';
        $this->messages['en'][] = 'Color';
        $this->messages['en'][] = 'Section';
        $this->messages['en'][] = 'Section form';
        $this->messages['en'][] = 'Type form';
        $this->messages['en'][] = 'Type';
        $this->messages['en'][] = 'ORPHAN';
        $this->messages['en'][] = 'Father';
        $this->messages['en'][] = 'Opinion';
        $this->messages['en'][] = 'Opinion form';
        $this->messages['en'][] = 'Send date';
        $this->messages['en'][] = 'Send hour';
        $this->messages['en'][] = 'Text';
        $this->messages['en'][] = 'Displayed';
        $this->messages['en'][] = 'User';
        $this->messages['en'][] = 'What you want ?';
        $this->messages['en'][] = 'Try again';
        $this->messages['en'][] = 'Registration date';
        $this->messages['en'][] = 'Registration hour';
        $this->messages['en'][] = 'Title';
        $this->messages['en'][] = 'Donor';
        $this->messages['en'][] = 'Review';
        $this->messages['en'][] = 'Object form';
        $this->messages['en'][] = 'Status';
        $this->messages['en'][] = 'Description';
        $this->messages['en'][] = 'ANALYZING';
        $this->messages['en'][] = 'BLOCKED';
        $this->messages['en'][] = 'AVAILABLE';
        $this->messages['en'][] = 'UNIQUE';
        $this->messages['en'][] = 'Send opinion';
        $this->messages['en'][] = 'Object';
        $this->messages['en'][] = 'Photos';
        $this->messages['en'][] = 'NEW';
        $this->messages['en'][] = 'USED';
        $this->messages['en'][] = 'Separate tags with commas, for example: bike,cycling,sport';
        $this->messages['en'][] = 'Preview';
        $this->messages['en'][] = 'Review Submitted';
        $this->messages['en'][] = 'Subtype';
        $this->messages['en'][] = 'Condition';
        $this->messages['en'][] = 'Chat';
        $this->messages['en'][] = 'Add to list';
        $this->messages['en'][] = 'Details';
        $this->messages['en'][] = 'Object has already been added';
        $this->messages['en'][] = 'Checkout';
        $this->messages['en'][] = 'List of requests';
        $this->messages['en'][] = 'Empty list';
        $this->messages['en'][] = 'Finished request';
        $this->messages['en'][] = 'Creation date';
        $this->messages['en'][] = 'Creation time';
        $this->messages['en'][] = 'Members';
        $this->messages['en'][] = 'Donation';
        $this->messages['en'][] = 'To manage';
        $this->messages['en'][] = 'Action';
        $this->messages['en'][] = 'Form of members';
        $this->messages['en'][] = 'Add';
        $this->messages['en'][] = 'The user already belongs to the conversation';
        $this->messages['en'][] = 'Donations requested';
        $this->messages['en'][] = 'Requested items to you';
        $this->messages['en'][] = 'Message';
        $this->messages['en'][] = 'Send';
        $this->messages['en'][] = 'Donee';
        $this->messages['en'][] = 'PENDING';
        $this->messages['en'][] = 'ACCEPTED';
        $this->messages['en'][] = 'REFUSED';
        $this->messages['en'][] = 'Item';
        $this->messages['en'][] = 'Change status';
        $this->messages['en'][] = 'Status change does not make sense';
        $this->messages['en'][] = 'Leave a comment...';
        $this->messages['en'][] = 'DEALING';
        $this->messages['en'][] = 'HAULING';
        $this->messages['en'][] = 'UNAVAILABLE';
        $this->messages['en'][] = 'Catalog';
        $this->messages['en'][] = 'Register object';
        $this->messages['en'][] = 'Chat';
        $this->messages['en'][] = 'Requests';
        $this->messages['en'][] = 'Administrator';
        $this->messages['en'][] = 'Published objects';
        $this->messages['en'][] = 'Status is required';
        $this->messages['en'][] = 'User manual';
        $this->messages['en'][] = 'Welcome to Dadivar';
        $this->messages['en'][] = 'Home page';
        $this->messages['en'][] = 'DDD+Number';
        $this->messages['en'][] = 'Confirm account';
        $this->messages['en'][] = 'Active';
          
        $this->messages['pt'][] = 'Arquivo não encontrado';
        $this->messages['pt'][] = 'Pesquisar';
        $this->messages['pt'][] = 'Cadastrar';
        $this->messages['pt'][] = 'Registro salvo';
        $this->messages['pt'][] = 'Deseja realmente excluir ?';
        $this->messages['pt'][] = 'Registro excluído';
        $this->messages['pt'][] = 'Função';
        $this->messages['pt'][] = 'Tabela';
        $this->messages['pt'][] = 'Ferramenta';
        $this->messages['pt'][] = 'Dados';
        $this->messages['pt'][] = 'Abrir';
        $this->messages['pt'][] = 'Novo';
        $this->messages['pt'][] = 'Salvar';
        $this->messages['pt'][] = 'Buscar';
        $this->messages['pt'][] = 'Deletar';
        $this->messages['pt'][] = 'Editar';
        $this->messages['pt'][] = 'Cancelar';
        $this->messages['pt'][] = 'Sim';
        $this->messages['pt'][] = 'Não';
        $this->messages['pt'][] = 'Janeiro';
        $this->messages['pt'][] = 'Fevereiro';
        $this->messages['pt'][] = 'Março';
        $this->messages['pt'][] = 'Abril';
        $this->messages['pt'][] = 'Maio';
        $this->messages['pt'][] = 'Junho';
        $this->messages['pt'][] = 'Julho';
        $this->messages['pt'][] = 'Agosto';
        $this->messages['pt'][] = 'Setembro';
        $this->messages['pt'][] = 'Outubro';
        $this->messages['pt'][] = 'Novembro';
        $this->messages['pt'][] = 'Dezembro';
        $this->messages['pt'][] = 'Hoje';
        $this->messages['pt'][] = 'Fechar';
        $this->messages['pt'][] = 'O campo ^1 não pode ter menos de ^2 caracteres';
        $this->messages['pt'][] = 'O campo ^1 não pode ter mais de ^2 caracteres';
        $this->messages['pt'][] = 'O campo ^1 não pode ser menor que ^2';
        $this->messages['pt'][] = 'O campo ^1 não pode ser maior que ^2';
        $this->messages['pt'][] = 'O campo ^1 é obrigatório';
        $this->messages['pt'][] = 'O campo ^1 não contém um CNPJ válido';
        $this->messages['pt'][] = 'O campo ^1 não contém um CPF válido';
        $this->messages['pt'][] = 'O campo ^1 contém um e-mail inválido';
        $this->messages['pt'][] = 'Permissão negada';
        $this->messages['pt'][] = 'Gerar';
        $this->messages['pt'][] = 'Listar';
        $this->messages['pt'][] = 'Detalhe';
        $this->messages['pt'][] = 'Voltar';
        $this->messages['pt'][] = 'Limpar';
        $this->messages['pt'][] = 'Não logado';
        $this->messages['pt'][] = 'Celular';
        $this->messages['pt'][] = 'Senha';
        $this->messages['pt'][] = 'Idioma';
        $this->messages['pt'][] = 'Entrar';
        $this->messages['pt'][] = 'Usuário não encontrado';
        $this->messages['pt'][] = 'Painel de administração';
        $this->messages['pt'][] = 'Português';
        $this->messages['pt'][] = 'Inglês';
        $this->messages['pt'][] = 'Esqueci minha senha';
        $this->messages['pt'][] = 'Senha incorreta';
        $this->messages['pt'][] = 'Opções';
        $this->messages['pt'][] = 'Cadastrar-se';
        $this->messages['pt'][] = 'Seja bem-vindo';
        $this->messages['pt'][] = 'Opções para visitante';
        $this->messages['pt'][] = 'Opções para administrador';
        $this->messages['pt'][] = 'Opções para usuário';
        $this->messages['pt'][] = 'Catálogo de objetos';
        $this->messages['pt'][] = 'Criar conta';
        $this->messages['pt'][] = 'Confirmar senha';
        $this->messages['pt'][] = 'Sair';
        $this->messages['pt'][] = 'Informações de acesso';
        $this->messages['pt'][] = 'Dados pessoais';
        $this->messages['pt'][] = 'Confirmação';
        $this->messages['pt'][] = 'Próximo';
        $this->messages['pt'][] = 'Masculino';
        $this->messages['pt'][] = 'Feminino';
        $this->messages['pt'][] = 'Nome';
        $this->messages['pt'][] = 'Sobrenome';
        $this->messages['pt'][] = 'Foto';
        $this->messages['pt'][] = 'Sexo';
        $this->messages['pt'][] = 'Código de verificação';
        $this->messages['pt'][] = 'Confirmar';
        $this->messages['pt'][] = 'Foi enviado o código de verificação por SMS, espere alguns minutos.';
        $this->messages['pt'][] = 'Envio completo';
        $this->messages['pt'][] = 'Não foi possível enviar SMS';
        $this->messages['pt'][] = 'Obrigatório';
        $this->messages['pt'][] = 'Visitante';
        $this->messages['pt'][] = 'Catalogo de objetos';
        $this->messages['pt'][] = 'Definir administrador';
        $this->messages['pt'][] = 'Data de cadastro';
        $this->messages['pt'][] = 'Categoria';
        $this->messages['pt'][] = 'Lista de usuários';
        $this->messages['pt'][] = 'Form de usuário';
        $this->messages['pt'][] = 'Form de categoria';
        $this->messages['pt'][] = 'Form de cor';
        $this->messages['pt'][] = 'Cor';
        $this->messages['pt'][] = 'Seção';
        $this->messages['pt'][] = 'Form de seção';
        $this->messages['pt'][] = 'Form de tipo';
        $this->messages['pt'][] = 'Tipo';
        $this->messages['pt'][] = 'ORFAO';
        $this->messages['pt'][] = 'Pai';
        $this->messages['pt'][] = 'Opinião';
        $this->messages['pt'][] = 'Form de opinião';
        $this->messages['pt'][] = 'Data de envio';
        $this->messages['pt'][] = 'Hora de envio';
        $this->messages['pt'][] = 'Texto';
        $this->messages['pt'][] = 'Visualizada';
        $this->messages['pt'][] = 'Usuário';
        $this->messages['pt'][] = 'O que você quer ?';
        $this->messages['pt'][] = 'Tente novamente';
        $this->messages['pt'][] = 'Data de registro';
        $this->messages['pt'][] = 'Hora de registro';
        $this->messages['pt'][] = 'Título';
        $this->messages['pt'][] = 'Doador';
        $this->messages['pt'][] = 'Análise';
        $this->messages['pt'][] = 'Form de objeto';
        $this->messages['pt'][] = 'Status';
        $this->messages['pt'][] = 'Descrição';
        $this->messages['pt'][] = 'ANALISANDO';
        $this->messages['pt'][] = 'BLOQUEADO';
        $this->messages['pt'][] = 'DISPONIVEL';
        $this->messages['pt'][] = 'ÚNICO';
        $this->messages['pt'][] = 'Enviar Opinião';
        $this->messages['pt'][] = 'Objeto';
        $this->messages['pt'][] = 'Fotos';
        $this->messages['pt'][] = 'NOVO';
        $this->messages['pt'][] = 'USADO';
        $this->messages['pt'][] = 'Separe as etiquetas por vírgula, exemplo: bicicleta,ciclismo,esporte';
        $this->messages['pt'][] = 'Visualização';
        $this->messages['pt'][] = 'Comentário Enviado';
        $this->messages['pt'][] = 'Subtipo';
        $this->messages['pt'][] = 'Condição';
        $this->messages['pt'][] = 'Conversa';
        $this->messages['pt'][] = 'Adicionar à lista';
        $this->messages['pt'][] = 'Detalhes';
        $this->messages['pt'][] = 'Objeto já foi adicionado';
        $this->messages['pt'][] = 'Finalizar Pedido';
        $this->messages['pt'][] = 'Lista de Pedidos';
        $this->messages['pt'][] = 'Lista vazia';
        $this->messages['pt'][] = 'Pedido Finalizado';
        $this->messages['pt'][] = 'Data de criação';
        $this->messages['pt'][] = 'Hora de criação';
        $this->messages['pt'][] = 'Integrantes';
        $this->messages['pt'][] = 'Doação';
        $this->messages['pt'][] = 'Gerenciar';
        $this->messages['pt'][] = 'Ações';
        $this->messages['pt'][] = 'Form de integrantes';
        $this->messages['pt'][] = 'Adicionar';
        $this->messages['pt'][] = 'Usuário já pertence à conversa';
        $this->messages['pt'][] = 'Doações solicitadas';
        $this->messages['pt'][] = 'Itens solicitados a você';
        $this->messages['pt'][] = 'Mensagem';
        $this->messages['pt'][] = 'Enviar';
        $this->messages['pt'][] = 'Donatário';
        $this->messages['pt'][] = 'PENDENTE';
        $this->messages['pt'][] = 'ACEITO';
        $this->messages['pt'][] = 'RECUSADO';
        $this->messages['pt'][] = 'Item';
        $this->messages['pt'][] = 'Alterar status';
        $this->messages['pt'][] = 'Mudança de status não faz sentido';
        $this->messages['pt'][] = 'Deixe um comentário...';
        $this->messages['pt'][] = 'NEGOCIANDO';
        $this->messages['pt'][] = 'TRANSPORTANDO';
        $this->messages['pt'][] = 'INDISPONIVEL';
        $this->messages['pt'][] = 'Catálogo';
        $this->messages['pt'][] = 'Publicar objeto';
        $this->messages['pt'][] = 'Conversas';
        $this->messages['pt'][] = 'Solicitações';
        $this->messages['pt'][] = 'Administrador';
        $this->messages['pt'][] = 'Objetos publicados';
        $this->messages['pt'][] = 'Status é obrigatório';
        $this->messages['pt'][] = 'Manual do Usuário';
        $this->messages['pt'][] = 'Bem-vindo ao Dadivar';
        $this->messages['pt'][] = 'Página inicial';
        $this->messages['pt'][] = 'DDD+Número';
        $this->messages['pt'][] = 'Confirmar conta';
        $this->messages['pt'][] = 'Ativo';
    }
    
    /**
     * Returns the singleton instance
     * @return  Instance of TApplicationTranslator
     */
    public static function getInstance()
    {
        // if there's no instance
        if (empty(self::$instance))
        {
            // creates a new object
            self::$instance = new TApplicationTranslator;
        }
        // returns the created instance
        return self::$instance;
    }
    
    /**
     * Define the target language
     * @param $lang     Target language index
     */
    public static function setLanguage($lang)
    {
        $instance = self::getInstance();
        $instance->lang = $lang;
    }
    
    /**
     * Returns the target language
     * @return Target language index
     */
    public static function getLanguage()
    {
        $instance = self::getInstance();
        return $instance->lang;
    }
    
    /**
     * Translate a word to the target language
     * @param $word     Word to be translated
     * @return          Translated word
     */
    static public function translate($word, $param1 = NULL, $param2 = NULL, $param3 = NULL)
    {
        // get the TApplicationTranslator unique instance
        $instance = self::getInstance();
        // search by the numeric index of the word
        $key = array_search($word, $instance->messages['pt']);
        if ($key !== FALSE)
        {
            // get the target language
            $language = self::getLanguage();
            // returns the translated word
            $message = $instance->messages[$language][$key];
            
            if (isset($param1))
            {
                $message = str_replace('^1', $param1, $message);
            }
            if (isset($param2))
            {
                $message = str_replace('^2', $param2, $message);
            }
            if (isset($param3))
            {
                $message = str_replace('^3', $param3, $message);
            }
            return $message;
        }
        else
        {
            return 'Message not found: '. $word;
        }
    }
    
    /**
     * Translate a template file
     */
    static public function translateTemplate($template)
    {
        // get the TApplicationTranslator unique instance
        $instance = self::getInstance();
        // search by the numeric index of the word
        foreach ($instance->messages['pt'] as $word)
        {
            $translated = _t($word);
            $template = str_replace('_t{'.$word.'}', $translated, $template);
        }
        return $template;
    }
}

/**
 * Facade to translate words
 * @param $word  Word to be translated
 * @param $param1 optional ^1
 * @param $param2 optional ^2
 * @param $param3 optional ^3
 * @return Translated word
 */
function _t($msg, $param1 = null, $param2 = null, $param3 = null)
{
    return TApplicationTranslator::translate($msg, $param1, $param2, $param3);
}
?>