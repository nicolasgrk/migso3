-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mar. 26 mars 2024 à 21:55
-- Version du serveur : 5.7.39
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `migso`
--

-- --------------------------------------------------------

--
-- Structure de la table `Answers`
--

CREATE TABLE `Answers` (
  `AnswerID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `QuestionID` int(11) DEFAULT NULL,
  `ChoiceID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Choices`
--

CREATE TABLE `Choices` (
  `ChoiceID` int(11) NOT NULL,
  `QuestionID` int(11) DEFAULT NULL,
  `ChoiceText` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Compatibility`
--

CREATE TABLE `Compatibility` (
  `CompID` int(11) NOT NULL,
  `UserID1` int(11) DEFAULT NULL,
  `UserID2` int(11) DEFAULT NULL,
  `CompatibilityPercent` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Questions`
--

CREATE TABLE `Questions` (
  `QuestionID` int(11) NOT NULL,
  `QuestionText` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `UserID` int(11) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Answers`
--
ALTER TABLE `Answers`
  ADD PRIMARY KEY (`AnswerID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `QuestionID` (`QuestionID`),
  ADD KEY `ChoiceID` (`ChoiceID`);

--
-- Index pour la table `Choices`
--
ALTER TABLE `Choices`
  ADD PRIMARY KEY (`ChoiceID`),
  ADD KEY `QuestionID` (`QuestionID`);

--
-- Index pour la table `Compatibility`
--
ALTER TABLE `Compatibility`
  ADD PRIMARY KEY (`CompID`),
  ADD KEY `UserID1` (`UserID1`),
  ADD KEY `UserID2` (`UserID2`);

--
-- Index pour la table `Questions`
--
ALTER TABLE `Questions`
  ADD PRIMARY KEY (`QuestionID`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Answers`
--
ALTER TABLE `Answers`
  MODIFY `AnswerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Choices`
--
ALTER TABLE `Choices`
  MODIFY `ChoiceID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Compatibility`
--
ALTER TABLE `Compatibility`
  MODIFY `CompID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Questions`
--
ALTER TABLE `Questions`
  MODIFY `QuestionID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Users`
--
ALTER TABLE `Users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Answers`
--
ALTER TABLE `Answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`QuestionID`) REFERENCES `Questions` (`QuestionID`),
  ADD CONSTRAINT `answers_ibfk_3` FOREIGN KEY (`ChoiceID`) REFERENCES `Choices` (`ChoiceID`);

--
-- Contraintes pour la table `Choices`
--
ALTER TABLE `Choices`
  ADD CONSTRAINT `choices_ibfk_1` FOREIGN KEY (`QuestionID`) REFERENCES `Questions` (`QuestionID`);

--
-- Contraintes pour la table `Compatibility`
--
ALTER TABLE `Compatibility`
  ADD CONSTRAINT `compatibility_ibfk_1` FOREIGN KEY (`UserID1`) REFERENCES `Users` (`UserID`),
  ADD CONSTRAINT `compatibility_ibfk_2` FOREIGN KEY (`UserID2`) REFERENCES `Users` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
