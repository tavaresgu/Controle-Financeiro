-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/07/2024 às 04:02
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `controle financeiro titan`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_conta_pagar`
--

CREATE TABLE `tbl_conta_pagar` (
  `id_conta_pagar` bigint(20) UNSIGNED NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_pagar` date NOT NULL,
  `pago` tinyint(4) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `valor_pagto` decimal(10,2) DEFAULT NULL,
  `data_pagto` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbl_empresa`
--

CREATE TABLE `tbl_empresa` (
  `id_empresa` bigint(20) NOT NULL,
  `nome` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbl_empresa`
--

INSERT INTO `tbl_empresa` (`id_empresa`, `nome`) VALUES
(1, 'Titan1'),
(2, 'Titan2'),
(3, 'Titan3'),
(4, 'Titan4');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbl_conta_pagar`
--
ALTER TABLE `tbl_conta_pagar`
  ADD PRIMARY KEY (`id_conta_pagar`),
  ADD UNIQUE KEY `id_conta_pagar` (`id_conta_pagar`),
  ADD KEY `id_conta_pagar_2` (`id_conta_pagar`);

--
-- Índices de tabela `tbl_empresa`
--
ALTER TABLE `tbl_empresa`
  ADD PRIMARY KEY (`id_empresa`),
  ADD UNIQUE KEY `id_empresa` (`id_empresa`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbl_conta_pagar`
--
ALTER TABLE `tbl_conta_pagar`
  MODIFY `id_conta_pagar` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `tbl_empresa`
--
ALTER TABLE `tbl_empresa`
  MODIFY `id_empresa` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
