-- ============================================================
-- PATCH ADMIN — PharmaLink
-- À importer APRÈS pharma_annuaire.sql
-- ============================================================

-- Supprimer anciens comptes admin
DELETE FROM `utilisateurs` WHERE `email` IN ('admin@pharmaannuaire.cm','admin@gmail.com');

-- ⚠️ IMPORTANT: Le hash ci-dessous est pour le mot de passe "admin123"
-- Si la connexion ne fonctionne pas, lancez reset_admin.php dans votre navigateur
-- puis supprimez ce fichier.

INSERT INTO `utilisateurs`
  (`nom`,`prenom`,`email`,`mot_de_passe`,`role`,`email_verified`,`created_at`)
VALUES
  ('Admin','Super','admin@gmail.com',
   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
   'admin',1,NOW());

-- Zones (sans colonne slug)
INSERT IGNORE INTO `zones` (`id`,`nom`) VALUES
(1,'Akwa'),(2,'Bonanjo'),(3,'Bali'),(4,'Déïdo'),(5,'Makepe'),(6,'Bonabéri'),(7,'Kotto');

-- Colonne lu dans contacts
ALTER TABLE `contacts` ADD COLUMN IF NOT EXISTS `lu` tinyint(1) DEFAULT 0;

-- Table logs admin
CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `detail` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Si la connexion ne fonctionne pas :
-- 1. Allez sur localhost/pharma_annuaire/reset_admin.php
-- 2. Cliquez sur "Aller à la connexion"
-- 3. Supprimez reset_admin.php
-- ============================================================
