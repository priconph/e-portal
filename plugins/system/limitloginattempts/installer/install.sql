CREATE TABLE IF NOT EXISTS `#__jt_limitloginattempts` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `retries` int(11) NOT NULL,
  `total_retries` int(11) DEFAULT NULL,
  `lockouts` int(11) DEFAULT NULL,
  `time` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `#__jt_limitloginattempts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `#__jt_limitloginattempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;