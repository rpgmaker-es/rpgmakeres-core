CREATE TABLE IF NOT EXISTS `penalties` (
                                           `uid` int NOT NULL,
                                           `user` int NOT NULL,
                                           `description` varchar(255) NOT NULL DEFAULT '',
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `duration` datetime DEFAULT NULL,
    `solved` datetime DEFAULT NULL,
    PRIMARY KEY (`uid`),
    KEY `user` (`user`),
    CONSTRAINT `FK__user` FOREIGN KEY (`user`) REFERENCES `user` (`uid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Table used for register user penalties (sanctions)';
