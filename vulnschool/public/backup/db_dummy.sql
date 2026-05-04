-- VULN-INFO-002: Database backup exposed
-- Dump created on 2026-04-20
CREATE TABLE `users_dump` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users_dump` (`id`, `username`, `password`) VALUES
(1, 'admin_super', '$2y$10$dummyhashformigrateddata001'),
(2, 'dosen_lama', '$2y$10$dummyhashformigrateddata002');
