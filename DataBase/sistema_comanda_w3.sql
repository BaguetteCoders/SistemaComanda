-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 18/04/2026 às 03:09
-- Versão do servidor: 9.1.0
-- Versão do PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_comanda_w3`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `comandas`
--

DROP TABLE IF EXISTS `comandas`;
CREATE TABLE IF NOT EXISTS `comandas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `status` varchar(20) DEFAULT 'aberta',
  `pedidos` varchar(500) NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `comandas`
--

INSERT INTO `comandas` (`id`, `nome`, `status`, `pedidos`, `data_criacao`) VALUES
(1, 'Bruno Gabriel', 'aberta', ' 1 1 3 3 3 1 2 2 2 1 1 13 11 11 11 11 11 11 11 11 11 11 13', '2026-04-17 20:31:22'),
(5, 'Guilhermemeu', 'aberta', '', '2026-04-18 01:42:44');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `preco`, `categoria`, `ativo`) VALUES
(1, 'Self-Service', 49.00, 'Refeicão', 1),
(2, 'Espeto Corrido', 68.00, 'Refeicão', 1),
(3, 'Rodizio Completo', 79.00, 'Refeicão', 1),
(4, 'Self-Service c/ Porção', 59.00, 'Refeicão', 1),
(6, 'Coca-Cola 2L', 17.00, 'Bebidas', 1),
(7, 'Coca-Cola 1L', 15.00, 'Bebidas', 1),
(8, 'Coca-Cola 600ML', 10.00, 'Bebidas', 1),
(9, 'Coca-Cola Lata', 8.00, 'Bebidas', 1),
(10, 'Coca-Cola KS', 8.00, 'Bebidas', 1),
(11, 'Refrigerante Jaboti 2L', 12.00, 'Bebidas', 1),
(13, 'Refrigerante 2L', 14.00, 'Bebidas', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `usertype` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `senha`, `usertype`) VALUES
(1, 'user', '123', 'Atendente'),
(2, 'admin', '123', 'Admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
