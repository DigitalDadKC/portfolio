SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `unit_of_measurements` (`id`, `UOM`) VALUES
(1, '%'),
(2, 'Each'),
(3, 'Ft'),
(4, 'Yd'),
(5, 'SF'),
(6, 'Acre'),
(7, 'Cu Ft'),
(8, 'Unit'),
(9, 'Hour'),
(10, 'Week'),
(11, 'Day'),
(12, 'Lump Sum');

COMMIT;