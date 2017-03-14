-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Máquina: 127.0.0.1
-- Data de Criação: 16-Mar-2016 às 12:14
-- Versão do servidor: 5.5.43-0ubuntu0.14.04.1
-- versão do PHP: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `dadivar`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(13) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome`) VALUES
(1, 'ADMINISTRADOR'),
(2, 'USUARIO');

-- --------------------------------------------------------

--
-- Estrutura da tabela `comentario`
--

CREATE TABLE IF NOT EXISTS `comentario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_envio` date NOT NULL,
  `hora_envio` time NOT NULL,
  `texto` text NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `objeto_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comentario_usuario1_idx` (`usuario_id`),
  KEY `fk_comentario_objeto1_idx` (`objeto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Extraindo dados da tabela `comentario`
--

INSERT INTO `comentario` (`id`, `data_envio`, `hora_envio`, `texto`, `usuario_id`, `objeto_id`) VALUES
(7, '2016-01-25', '07:02:45', 'fsdasd', 52, 1),
(10, '2016-01-25', '08:28:23', 'sasa', 52, 1),
(14, '2016-01-25', '08:42:58', 'teste', 52, 1),
(15, '2016-01-25', '08:44:49', 'teste', 52, 2),
(16, '2016-01-25', '08:46:26', 'Por que está doando o violão?', 52, 8),
(17, '2016-01-25', '08:47:54', 'Por que está doando o violão?', 52, 8),
(18, '2016-01-25', '09:09:47', 'teste34343', 52, 1),
(19, '2016-01-25', '20:03:38', 'Olá, estou precisando um óculos desse, pois, o meu quebrou. !!! Poderíamos marcar de se encontrar?', 25, 3),
(20, '2016-01-25', '20:08:42', 'Que Show !!! Esse modelo eu não conhecia !!!', 25, 2),
(21, '2016-01-25', '20:08:58', 'teste1', 25, 2),
(22, '2016-01-25', '20:13:02', 'A modelo vem junto rss', 25, 3),
(23, '2016-01-27', '22:41:50', 'Boa noite, gostaria de saber quantas marchas ela possui.', 25, 2),
(24, '2016-01-27', '22:48:19', 'sasa', 52, 13),
(25, '2016-01-27', '23:01:01', 'SHOW!!!!', 25, 3),
(26, '2016-01-28', '00:07:29', 'testetetstsatsa', 52, 13),
(27, '2016-01-28', '00:07:35', 'TOP!!!', 25, 3),
(28, '2016-01-28', '00:08:31', 'Oculos maneiro', 25, 5),
(29, '2016-01-28', '00:08:42', 'UAAAL', 25, 5),
(30, '2016-01-28', '21:53:49', 'oi', 25, 2),
(31, '2016-01-28', '22:23:37', 'Testi', 25, 5),
(32, '2016-01-28', '22:23:54', 'ribimboca', 25, 5),
(33, '2016-01-28', '22:33:53', 'Olá, quantas marchas ela possui?', 25, 4),
(34, '2016-01-28', '22:35:26', 'Bacana', 25, 4),
(35, '2016-01-28', '23:00:43', 'BOnita', 25, 13),
(36, '2016-01-29', '00:43:11', 'Que bacana, ', 25, 2),
(37, '2016-01-29', '00:45:47', 'Qual marca ele é amigo?\r\n', 25, 8),
(38, '2016-01-29', '06:50:44', 'Não está vendo que é Giannini? seu animal!', 52, 8),
(39, '2016-02-15', '19:24:44', 'Esta Camiseta é muito bonita !', 18, 13);

-- --------------------------------------------------------

--
-- Estrutura da tabela `conversa`
--

CREATE TABLE IF NOT EXISTS `conversa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Extraindo dados da tabela `conversa`
--

INSERT INTO `conversa` (`id`, `data_criacao`, `hora_criacao`) VALUES
(15, '2016-02-05', '22:13:17');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cor`
--

CREATE TABLE IF NOT EXISTS `cor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(8) NOT NULL,
  `rgb` varchar(7) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_UNIQUE` (`nome`),
  UNIQUE KEY `rgb_UNIQUE` (`rgb`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `cor`
--

INSERT INTO `cor` (`id`, `nome`, `rgb`) VALUES
(1, 'PRETO', '#000000'),
(2, 'BRANCO', '#ffffff'),
(3, 'VERMELHO', '#ff0000'),
(6, 'AMARELO', '#ffff00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `doacao`
--

CREATE TABLE IF NOT EXISTS `doacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_registro` date NOT NULL,
  `hora_registro` time NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'P',
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pedido_usuario1_idx` (`usuario_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Extraindo dados da tabela `doacao`
--

INSERT INTO `doacao` (`id`, `data_registro`, `hora_registro`, `status`, `usuario_id`) VALUES
(1, '2016-02-01', '22:41:51', 'P', 25),
(2, '2016-02-01', '22:48:25', 'P', 25),
(3, '2016-02-01', '22:51:21', 'P', 25),
(4, '2016-02-01', '23:00:19', 'P', 25),
(12, '2016-02-02', '00:05:46', 'P', 25),
(14, '2016-02-02', '06:40:27', 'P', 52),
(15, '2016-02-03', '22:21:16', 'P', 52),
(16, '2016-02-07', '20:21:50', 'P', 53),
(17, '2016-02-08', '10:48:52', 'P', 52),
(18, '2016-02-08', '12:22:09', 'P', 52),
(19, '2016-02-08', '14:38:12', 'P', 53),
(20, '2016-02-08', '14:55:44', 'P', 52),
(21, '2016-02-08', '19:47:41', 'P', 52),
(24, '2016-02-10', '19:06:19', 'P', 25),
(25, '2016-02-15', '19:25:00', 'P', 18);

-- --------------------------------------------------------

--
-- Estrutura da tabela `foto`
--

CREATE TABLE IF NOT EXISTS `foto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `arquivo` varchar(255) NOT NULL,
  `objeto_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_foto_objeto1_idx` (`objeto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Extraindo dados da tabela `foto`
--

INSERT INTO `foto` (`id`, `arquivo`, `objeto_id`) VALUES
(1, 'uploads/14991940268oculosdesolrayban.jpg', 1),
(2, 'uploads/14991940268bicicleta_houston.jpg', 2),
(3, 'uploads/14991940268bicicleta_houston.jpg', 4),
(5, 'uploads/14991940268oculosdesolrayban.jpg', 5),
(6, 'uploads/14991940268bicicleta_houston.jpg', 6),
(7, 'uploads/14991940268oculosdesolrayban.jpg', 7),
(13, 'uploads/14991940268violo-folk-eletrico-gf1r-giannini-preto-de-aco-rosewood-16441-MLB6547148761_072014-F.jpg', 8),
(14, 'uploads/14991940268violo-folk-eletrico-gf1r-giannini-preto-de-aco-rosewood-16441-MLB6547148761_072014-F.jpg', 8),
(15, 'uploads/14991940268oculosdesolrayban.jpg', 3),
(18, 'uploads/9693_14991940268_Camiseta-Hollister-original-masculina.jpg', 13),
(19, 'uploads/3920_14991940268_Camisetas-Hollister-azul.jpg', 13),
(20, 'uploads/2172_14991468840_7556538c5d.jpg', 14),
(21, 'uploads/8029_14991468840_Harry-Potter-e-a-Pedra-Filosofal-livro.jpg', 15),
(22, 'uploads/6856_14991468840_pt_BR-timeline-image-harry-potter-e-o-calice-de-fogo-publicado-1346420995.jpg', 15),
(23, 'uploads/4441_14981203558_2.jpg', 16),
(24, 'uploads/1648_14996801310_Tulips.jpg', 17),
(25, 'uploads/4059_14991940268_IMG_1909.JPG', 18),
(26, 'uploads/7812_14991940268_791_14991940268_IMG_1902.JPG', 19),
(27, 'uploads/88_14991940268_866_14991940268_IMG_1894.JPG', 19),
(28, 'uploads/6610_14991940268_1401_14991940268_IMG_1895.JPG', 19),
(29, 'uploads/2883_14991940268_IMG_1909.JPG', 20);

-- --------------------------------------------------------

--
-- Estrutura da tabela `integrante`
--

CREATE TABLE IF NOT EXISTS `integrante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_integracao` date NOT NULL,
  `hora_integracao` time NOT NULL,
  `conversa_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`,`conversa_id`,`usuario_id`),
  KEY `fk_usuario_conversa_conversa_idx` (`conversa_id`),
  KEY `fk_usuario_conversa_usuario1_idx` (`usuario_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=252 ;

--
-- Extraindo dados da tabela `integrante`
--

INSERT INTO `integrante` (`id`, `data_integracao`, `hora_integracao`, `conversa_id`, `usuario_id`) VALUES
(250, '0000-00-00', '00:00:00', 15, 52),
(251, '0000-00-00', '00:00:00', 15, 25);

-- --------------------------------------------------------

--
-- Estrutura da tabela `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` char(1) NOT NULL DEFAULT 'P',
  `doacao_id` int(11) NOT NULL,
  `objeto_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_item_doacao1_idx` (`doacao_id`),
  KEY `fk_item_objeto1_idx` (`objeto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Extraindo dados da tabela `item`
--

INSERT INTO `item` (`id`, `status`, `doacao_id`, `objeto_id`) VALUES
(1, 'A', 14, 1),
(2, 'R', 15, 2),
(3, 'R', 16, 5),
(4, 'A', 17, 14),
(5, 'A', 18, 3),
(6, 'R', 18, 8),
(9, 'A', 19, 6),
(10, 'A', 20, 2),
(11, 'R', 21, 15),
(12, 'P', 24, 4),
(13, 'P', 25, 5),
(14, 'P', 25, 13);

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagem`
--

CREATE TABLE IF NOT EXISTS `mensagem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_envio` date NOT NULL,
  `hora_envio` time NOT NULL,
  `texto` varchar(200) NOT NULL,
  `conversa_id` int(11) NOT NULL,
  `remetente_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mensagem_conversa1_idx` (`conversa_id`),
  KEY `fk_mensagem_usuario1_idx` (`remetente_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=76 ;

--
-- Extraindo dados da tabela `mensagem`
--

INSERT INTO `mensagem` (`id`, `data_envio`, `hora_envio`, `texto`, `conversa_id`, `remetente_id`) VALUES
(70, '2016-02-09', '17:31:08', 'teste123', 15, 52),
(71, '2016-02-09', '17:32:27', 'teste456', 15, 52),
(72, '2016-02-09', '17:34:18', 'teste789', 15, 25),
(73, '2016-02-10', '17:12:22', 'Que doido tio', 15, 25),
(74, '2016-02-26', '09:01:03', 'teste1', 15, 52),
(75, '2016-02-26', '09:01:13', 'teste de scroll', 15, 52);

-- --------------------------------------------------------

--
-- Estrutura da tabela `objeto`
--

CREATE TABLE IF NOT EXISTS `objeto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_registro` date NOT NULL,
  `hora_registro` time NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `marca` varchar(20) DEFAULT NULL,
  `descricao` text NOT NULL,
  `tamanho` varchar(3) DEFAULT NULL,
  `condicao` char(1) NOT NULL,
  `etiqueta` varchar(255) DEFAULT NULL,
  `status` char(1) NOT NULL DEFAULT 'A',
  `usuario_id` int(11) NOT NULL,
  `cor_id` int(11) NOT NULL,
  `tipo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_objeto_usuario1_idx` (`usuario_id`),
  KEY `fk_objeto_cor1_idx` (`cor_id`),
  KEY `fk_objeto_tipo1_idx` (`tipo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Extraindo dados da tabela `objeto`
--

INSERT INTO `objeto` (`id`, `data_registro`, `hora_registro`, `titulo`, `marca`, `descricao`, `tamanho`, `condicao`, `etiqueta`, `status`, `usuario_id`, `cor_id`, `tipo_id`) VALUES
(1, '2016-01-12', '06:39:00', 'Óculos de sol preto', 'Ray-Ban', 'Óculos de sol preto modelo Wayfarer, estou doando porque saiu modelo novo.', 'U', 'U', 'wayfarer;ray-ban;rayban;oculos;', 'N', 52, 1, 2),
(2, '2016-01-12', '22:43:00', 'Bicicleta', 'Houston', 'Bicicleta aro 26, Houston, 2 anos de uso.', NULL, 'U', 'bike;bicicleta;houston;', 'N', 52, 3, 2),
(3, '2016-01-12', '06:39:00', 'Óculos de sol preto', 'Ray-Ban', 'Óculos de sol preto modelo Wayfarer, estou doando porque saiu modelo novo.', 'U', 'U', 'wayfarer;ray-ban;rayban;oculos;', 'N', 52, 1, 2),
(4, '2016-01-12', '22:43:00', 'Bicicleta', 'Houston', 'Bicicleta aro 26, Houston, 2 anos de uso.', NULL, 'U', 'bike;bicicleta;houston;', 'N', 52, 3, 2),
(5, '2016-01-12', '06:39:00', 'Óculos de sol preto', 'Ray-Ban', 'Óculos de sol preto modelo Wayfarer, estou doando porque saiu modelo novo.', 'U', 'U', 'wayfarer;ray-ban;rayban;oculos;', 'N', 52, 1, 2),
(6, '2016-01-12', '22:43:00', 'Bicicleta', 'Houston', 'Bicicleta aro 26, Houston, 2 anos de uso.', NULL, 'U', 'bike;bicicleta;houston;', 'N', 52, 3, 2),
(7, '2016-01-12', '06:39:00', 'Óculos de sol preto', 'Ray-Ban', 'Óculos de sol preto modelo Wayfarer, estou doando porque saiu modelo novo.', 'U', 'U', 'wayfarer;ray-ban;rayban;oculos;', 'B', 52, 1, 2),
(8, '2016-01-19', '22:43:00', 'Violão Elétrico Giannini Preto Maravilhoso!', 'giannini', '<ul>\r\n<li>Preto</li>\r\n<li>Elétrico</li>\r\n<li>110v</li>\r\n<li>Cordas de Aço</li>\r\n</ul>', NULL, 'U', 'violão;sertanejo;música;instrumento musical;', 'D', 52, 2, 140),
(13, '2016-01-27', '20:44:57', 'Camisetas Hollister', 'Hollister', '<p>Estou doando duas camisetas <span style="background-color: yellow;">Hollister</span> comprei, mas ficaram grandes.<br></p>', 'S', 'U', 'camiseta,hollister,t-shirt', 'N', 52, 1, 70),
(14, '2016-02-08', '10:42:55', 'Cadeira de área', NULL, '<p>Cadeira de área verde.<br></p>', NULL, 'U', 'cadeira,area', 'I', 53, 1, 95),
(15, '2016-02-08', '19:45:09', 'Livros Harry Potter', NULL, '<ul><li>Harry Potter e a Pedra Filosofal</li><li>Harry Potter e o Cálice de Fogo</li></ul><p><br>Os livros são muito <span style="background-color: yellow;">divertidos</span> valem a pena!<br></p>', NULL, 'U', 'livro,book,harry,potter', 'D', 53, 1, 141),
(16, '2016-02-11', '22:01:55', 'Pen Drive 8 GB', 'sandisk', '<p>Estou doando ele pois comprei um maior.</p>', 'U', 'U', 'informatica,pendrive,computador,8gb', 'A', 25, 3, 108),
(17, '2016-02-15', '19:32:31', 'tulipas', 'Tulipas', '<p>Uma flor bem bonita !</p>', NULL, 'N', NULL, 'A', 18, 6, 134),
(18, '2016-03-04', '08:25:51', 'teste', 'teste', '<p>teste<br></p>', 'tes', 'U', 'testeteste', 'A', 52, 1, 2),
(19, '2016-03-04', '08:36:02', 'teste', 'teste', '<p>teste<br></p>', 'tes', 'U', 'teste', 'A', 52, 1, 2),
(20, '2016-03-04', '08:39:33', 'teste', 'teste', '<p>teste<br></p>', 'tes', 'U', 'teste,teste1,teste2', 'A', 52, 1, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `opiniao`
--

CREATE TABLE IF NOT EXISTS `opiniao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_envio` date NOT NULL,
  `hora_envio` time NOT NULL,
  `texto` text NOT NULL,
  `visualizada` tinyint(1) NOT NULL DEFAULT '0',
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_opiniao_usuario1_idx` (`usuario_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Extraindo dados da tabela `opiniao`
--

INSERT INTO `opiniao` (`id`, `data_envio`, `hora_envio`, `texto`, `visualizada`, `usuario_id`) VALUES
(2, '2016-01-17', '14:00:00', 'Legal o site, mas eu não sei usar direito poderiam fazer um manual em PDF. Vocês tem que divulgar mais o site, nas mídias sociais a ideia é muito legal.', 0, 18),
(12, '0000-00-00', '00:00:00', 'O site esta ruim', 1, 18),
(13, '0000-00-00', '00:00:00', 'teste 123', 1, 52),
(14, '2016-01-23', '10:41:20', 'teste 123', 1, 52),
(15, '2016-01-23', '10:41:26', 'O site esta ruim', 1, 18),
(16, '2016-02-09', '17:11:01', 'o menu ficou melhor com esses nomes e a organização ficou legal também.', 1, 53),
(17, '2016-02-10', '18:02:22', 'O site é muito legal, Parabens !', 0, 25);

-- --------------------------------------------------------

--
-- Estrutura da tabela `secao`
--

CREATE TABLE IF NOT EXISTS `secao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome_UNIQUE` (`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Extraindo dados da tabela `secao`
--

INSERT INTO `secao` (`id`, `nome`) VALUES
(8, 'BANHO'),
(4, 'CASA'),
(6, 'ELETRONICO'),
(1, 'FEMININO'),
(3, 'INFANTIL'),
(5, 'INFORMATICA'),
(2, 'MASCULINO'),
(7, 'OUTROS');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo`
--

CREATE TABLE IF NOT EXISTS `tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pai_id` int(11) NOT NULL DEFAULT '0',
  `nome` varchar(21) NOT NULL,
  `secao_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tipo_secao1_idx` (`secao_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=149 ;

--
-- Extraindo dados da tabela `tipo`
--

INSERT INTO `tipo` (`id`, `pai_id`, `nome`, `secao_id`) VALUES
(1, 0, 'ACESSORIOS', 1),
(2, 1, 'OCULOS', 1),
(3, 1, 'RELOGIOS', 1),
(4, 1, 'BIJOUX', 1),
(5, 1, 'MEIA-CALCA', 1),
(7, 1, 'CINTOS', 1),
(8, 1, 'JOIAS', 1),
(9, 1, 'CARTEIRAS', 1),
(10, 1, 'LENCOS', 1),
(11, 1, 'CHAPEUS', 1),
(12, 1, 'CABELOS', 1),
(13, 0, 'ROUPAS', 1),
(14, 13, 'BLUSAS', 1),
(15, 13, 'COLETES', 1),
(16, 13, 'CALCAS', 1),
(17, 13, 'CAMISAS', 1),
(18, 13, 'CAMISETAS', 1),
(19, 13, 'CASACOS', 1),
(20, 13, 'JALECOS', 1),
(21, 13, 'MACACOES', 1),
(22, 13, 'SAIAS', 1),
(23, 13, 'SHORTS', 1),
(24, 13, 'TERNOS', 1),
(25, 13, 'VESTIDOS', 1),
(26, 0, 'BELEZA', 1),
(27, 26, 'PERFUMES', 1),
(28, 26, 'MAQUIAGEM', 1),
(29, 26, 'COSMETICOS', 1),
(30, 26, 'CABELOS', 1),
(31, 26, 'UNHA', 1),
(32, 0, 'CALCADOS', 1),
(33, 32, 'BOTAS', 1),
(34, 32, 'SANDALIAS', 1),
(35, 32, 'RASTEIRINHAS', 1),
(36, 32, 'SAPATILHAS', 1),
(37, 32, 'SAPATOS', 1),
(38, 32, 'TENIS', 1),
(39, 0, 'BOLSAS', 1),
(40, 39, 'OMBRO', 1),
(41, 39, 'MOCHILAS', 1),
(42, 39, 'DE MAO', 1),
(43, 0, 'PRAIA', 1),
(44, 0, 'LINGERIE', 1),
(45, 0, 'CASAMENTO', 1),
(46, 0, 'ESPORTES', 1),
(47, 0, 'OUTROS', 1),
(48, 0, 'ACESSORIOS', 2),
(49, 48, 'OCULOS', 2),
(50, 48, 'RELOGIOS', 2),
(51, 48, 'CARTEIRAS', 2),
(52, 48, 'BONES', 2),
(53, 48, 'BOLSAS', 2),
(54, 48, 'CHAPEUS', 2),
(55, 48, 'LENCOS', 2),
(56, 0, 'BELEZA', 2),
(57, 56, 'PERFUMES', 2),
(58, 56, 'CREMES', 2),
(59, 0, 'CALCADOS', 2),
(60, 59, 'BOTAS', 2),
(61, 59, 'TENIS', 2),
(62, 59, 'SANDALIAS', 2),
(63, 59, 'SAPATOS', 2),
(64, 0, 'ROUPAS', 2),
(65, 64, 'BERMUDAS', 2),
(66, 64, 'BLAZER', 2),
(67, 64, 'CASACOS', 2),
(68, 64, 'CALCAS', 2),
(69, 64, 'CAMISAS', 2),
(70, 64, 'CAMISETAS', 2),
(71, 64, 'COLETES', 2),
(72, 64, 'JALECOS', 2),
(73, 0, 'PRAIA', 2),
(74, 0, 'ESPORTES', 2),
(75, 0, 'OUTROS', 2),
(76, 0, 'ACESSORIOS', 3),
(77, 76, 'ANDADORES', 3),
(78, 76, 'CADEIRINHAS', 3),
(79, 0, 'ROUPAS', 3),
(80, 79, 'MENINAS', 3),
(81, 79, 'MENINOS', 3),
(82, 79, 'BEBES', 3),
(83, 0, 'PEZINHOS', 3),
(84, 83, 'MENINAS', 3),
(85, 83, 'MENINOS', 3),
(86, 83, 'BEBES', 3),
(87, 0, 'BRINQUEDOS', 3),
(88, 87, 'CRESCIDOS', 3),
(89, 87, 'PELUCIAS', 3),
(90, 87, 'PEQUENINOS', 3),
(91, 0, 'BERCOS', 3),
(92, 0, 'CARRINHOS', 3),
(93, 0, 'OUTROS', 3),
(94, 0, 'MOVEIS', 4),
(95, 94, 'CADEIRAS', 4),
(96, 94, 'MESAS', 4),
(97, 94, 'POLTRONAS', 4),
(98, 94, 'RACKS', 4),
(99, 94, 'SOFAS', 4),
(100, 94, 'OUTROS', 4),
(101, 0, 'ANTIQUARIOS', 4),
(102, 0, 'DECORACAO', 4),
(103, 0, 'ELETRODOMESTICOS', 4),
(104, 0, 'ILUMINACAO', 4),
(105, 0, 'COZINHAS', 4),
(106, 0, 'OUTROS', 4),
(107, 0, 'COMPUTADORES', 5),
(108, 107, 'DESKTOP', 5),
(109, 107, 'TECLADOS', 5),
(110, 107, 'NOTEBOOK', 5),
(111, 107, 'NETBOOK', 5),
(112, 107, 'MOUSES', 5),
(113, 0, 'TABLETS', 5),
(114, 0, 'OUTROS', 5),
(115, 0, 'TELEFONIA', 6),
(116, 115, 'CELULARES', 6),
(117, 115, 'FIXO', 6),
(118, 0, 'FOTOGRAFIA', 6),
(119, 118, 'DIGITAL', 6),
(120, 118, 'ANALOGICA', 6),
(121, 118, 'LENTE E ACESSORIOS', 6),
(122, 118, 'FILMADORAS', 6),
(123, 0, 'MUSICA', 6),
(124, 0, 'VIDEOGAME', 6),
(125, 124, 'CONSOLES', 6),
(126, 124, 'JOGOS', 6),
(127, 124, 'ACESSORIOS', 6),
(128, 0, 'TVS E DISPLAYS', 6),
(129, 0, 'OUTROS', 6),
(130, 0, 'CACARECOS', 7),
(131, 130, 'CAPINHAS', 7),
(132, 130, 'PEN DRIVE', 7),
(133, 130, 'CANEQUINHAS', 7),
(134, 130, 'OUTROS', 7),
(135, 0, 'JOGOS', 7),
(136, 135, 'TABULEIRO', 7),
(137, 135, 'COMPUTADOR', 7),
(138, 0, 'CARROS', 7),
(139, 0, 'ESPORTES', 7),
(140, 0, 'INSTRUMENTOS MUSICAIS', 7),
(141, 0, 'LIVRARIA', 7),
(142, 0, 'PAPELARIA', 7),
(143, 0, 'ANIMAIS', 7),
(144, 0, 'MUSICA', 7),
(145, 0, 'FILMES E SERIES', 7),
(146, 0, 'VINTAGE E RETRO', 7),
(147, 0, 'MALAS', 7),
(148, 107, 'Notebook', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `celular` varchar(11) NOT NULL,
  `data_cadastro` date NOT NULL,
  `nome` varchar(15) NOT NULL,
  `sobrenome` varchar(15) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `senha` varchar(32) NOT NULL,
  `codigo_verificacao` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '0',
  `categoria_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `celular_UNIQUE` (`celular`),
  KEY `fk_usuario_categoria1_idx` (`categoria_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=59 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `celular`, `data_cadastro`, `nome`, `sobrenome`, `foto`, `sexo`, `senha`, `codigo_verificacao`, `ativo`, `categoria_id`) VALUES
(18, '14996801310', '2016-01-05', 'Dellano', 'Herrera', 'uploads/14981347677Koala.jpg', 'M', '698dc19d489c4e4db73e28a713eab07b', 577869, 1, 1),
(25, '14981203558', '2016-01-08', 'Luis Guilherme', 'Martins Peres', 'uploads/14981203558asdasasdas.png', 'M', '698dc19d489c4e4db73e28a713eab07b', 865681, 1, 1),
(52, '14991940268', '2016-01-09', 'Alan', 'Pinhel', 'uploads/14991940268IMG_1123.JPG', 'M', '698dc19d489c4e4db73e28a713eab07b', 243144, 1, 1),
(53, '14991468840', '2016-01-17', 'Claudia', 'Letícia', 'uploads/14991468840IMG_0250.JPG', 'F', '698dc19d489c4e4db73e28a713eab07b', 627912, 1, 2),
(54, '11111111111', '2016-03-03', 'teste', 'teste', 'uploads/7465_11111111111_wallhaven-331076.jpg', 'M', '698dc19d489c4e4db73e28a713eab07b', 805438, 0, 2),
(55, '22222222222', '2016-03-03', 'teste', 'teste', 'uploads/7939_22222222222_wallhaven-331076.jpg', 'M', '698dc19d489c4e4db73e28a713eab07b', 919293, 0, 2),
(56, '33333333333', '2016-03-03', 'teste', 'testet', 'uploads/5180_33333333333_perfil.jpg', 'M', '698dc19d489c4e4db73e28a713eab07b', 842544, 0, 2),
(57, '44444444444', '2016-03-04', 'teste', 'teste', 'uploads/3096_44444444444_IMG_1909.JPG', 'M', '698dc19d489c4e4db73e28a713eab07b', 119575, 0, 2),
(58, '32222222222', '2016-03-04', 'teste', 'teste', 'uploads/6902_32222222222_IMG_1907.JPG', 'M', 'd08f5408fc73d024471b648b7b4a749c', 657761, 1, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `visitas`
--

CREATE TABLE IF NOT EXISTS `visitas` (
  `ip_visitante` varchar(15) NOT NULL DEFAULT '',
  `data_acesso` date DEFAULT NULL,
  `hora_acesso` time DEFAULT NULL,
  PRIMARY KEY (`ip_visitante`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `visitas`
--

INSERT INTO `visitas` (`ip_visitante`, `data_acesso`, `hora_acesso`) VALUES
('177.188.95.202', '2016-03-04', '09:16:07'),
('177.95.254.196', '2016-03-03', '01:16:59'),
('179.98.95.85', '2016-03-02', '08:40:09'),
('186.232.81.248', '2016-03-02', '13:55:31'),
('187.57.42.152', '2016-03-06', '22:36:57'),
('187.57.71.219', '2016-03-03', '13:03:51'),
('187.74.1.87', '2016-03-02', '16:05:31'),
('189.44.226.131', '2016-03-03', '18:48:26'),
('191.209.69.128', '2016-03-05', '03:57:32'),
('201.95.147.65', '2016-02-27', '17:15:43');

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `fk_comentario_objeto1` FOREIGN KEY (`objeto_id`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comentario_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `doacao`
--
ALTER TABLE `doacao`
  ADD CONSTRAINT `fk_pedido_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `foto`
--
ALTER TABLE `foto`
  ADD CONSTRAINT `fk_foto_objeto1` FOREIGN KEY (`objeto_id`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `integrante`
--
ALTER TABLE `integrante`
  ADD CONSTRAINT `fk_usuario_conversa_conversa` FOREIGN KEY (`conversa_id`) REFERENCES `conversa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuario_conversa_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `fk_item_doacao1` FOREIGN KEY (`doacao_id`) REFERENCES `doacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_item_objeto1` FOREIGN KEY (`objeto_id`) REFERENCES `objeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `mensagem`
--
ALTER TABLE `mensagem`
  ADD CONSTRAINT `fk_mensagem_conversa1` FOREIGN KEY (`conversa_id`) REFERENCES `conversa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_mensagem_usuario1` FOREIGN KEY (`remetente_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `objeto`
--
ALTER TABLE `objeto`
  ADD CONSTRAINT `fk_objeto_cor1` FOREIGN KEY (`cor_id`) REFERENCES `cor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_objeto_tipo1` FOREIGN KEY (`tipo_id`) REFERENCES `tipo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_objeto_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `opiniao`
--
ALTER TABLE `opiniao`
  ADD CONSTRAINT `fk_opiniao_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tipo`
--
ALTER TABLE `tipo`
  ADD CONSTRAINT `fk_tipo_secao1` FOREIGN KEY (`secao_id`) REFERENCES `secao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_categoria1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
