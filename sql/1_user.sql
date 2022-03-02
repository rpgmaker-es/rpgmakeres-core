CREATE TABLE IF NOT EXISTS `user` (
                                      `uid` int NOT NULL AUTO_INCREMENT,
                                      `username` varchar(15) NOT NULL,
    `email` varchar(50) NOT NULL,
    `password` varchar(100) NOT NULL,
    `recoverytoken` varchar(50) DEFAULT NULL,
    `recovery_at` datetime DEFAULT NULL,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `active` tinyint NOT NULL DEFAULT '1',
    `suspended` tinyint NOT NULL DEFAULT '0',
    `verified` tinyint NOT NULL DEFAULT '0',
    `permissions` tinyint NOT NULL DEFAULT '0' COMMENT '0 = normal, 4 admin',
    `deleted` tinyint NOT NULL DEFAULT '0',
    `avatar` varchar(50) DEFAULT NULL,
    `url1` varchar(50) DEFAULT NULL,
    `url2` varchar(50) DEFAULT NULL,
    `url3` varchar(50) DEFAULT NULL,
    `url4` varchar(50) DEFAULT NULL,
    PRIMARY KEY (`uid`),
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 COMMENT='Table for storing basic user data';

INSERT INTO `user` (`uid`, `username`, `email`, `password`, `recoverytoken`, `recovery_at`, `created_at`, `active`, `suspended`, `verified`, `permissions`, `deleted`, `avatar`, `url1`, `url2`, `url3`, `url4`) VALUES (1, 'admin', 'mail@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$dmg5Zmo1WEtVcU1xRmVpdw$g9QtZDzEy2q+pj07Y2DP/adEAPmzYVsvjtZnLyD0UNM', NULL, NULL, '2020-12-20 14:50:06', 1, 0, 1, 4, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`uid`, `username`, `email`, `password`, `recoverytoken`, `recovery_at`, `created_at`, `active`, `suspended`, `verified`, `permissions`, `deleted`, `avatar`, `url1`, `url2`, `url3`, `url4`) VALUES (2, 'user', 'correo@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$dmg5Zmo1WEtVcU1xRmVpdw$g9QtZDzEy2q+pj07Y2DP/adEAPmzYVsvjtZnLyD0UNM', NULL, NULL, '2020-12-20 14:50:06', 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`uid`, `username`, `email`, `password`, `recoverytoken`, `recovery_at`, `created_at`, `active`, `suspended`, `verified`, `permissions`, `deleted`, `avatar`, `url1`, `url2`, `url3`, `url4`) VALUES (4, 'user2', 'correo2@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$dmg5Zmo1WEtVcU1xRmVpdw$g9QtZDzEy2q+pj07Y2DP/adEAPmzYVsvjtZnLyD0UNM', NULL, NULL, '2020-12-20 14:50:06', 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`uid`, `username`, `email`, `password`, `recoverytoken`, `recovery_at`, `created_at`, `active`, `suspended`, `verified`, `permissions`, `deleted`, `avatar`, `url1`, `url2`, `url3`, `url4`) VALUES (5, 'user3', 'correo3@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$dmg5Zmo1WEtVcU1xRmVpdw$g9QtZDzEy2q+pj07Y2DP/adEAPmzYVsvjtZnLyD0UNM', NULL, NULL, '2020-12-20 14:50:06', 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`uid`, `username`, `email`, `password`, `recoverytoken`, `recovery_at`, `created_at`, `active`, `suspended`, `verified`, `permissions`, `deleted`, `avatar`, `url1`, `url2`, `url3`, `url4`) VALUES (6, 'user4', 'correo4@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$dmg5Zmo1WEtVcU1xRmVpdw$g9QtZDzEy2q+pj07Y2DP/adEAPmzYVsvjtZnLyD0UNM', NULL, NULL, '2020-12-20 14:50:06', 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`uid`, `username`, `email`, `password`, `recoverytoken`, `recovery_at`, `created_at`, `active`, `suspended`, `verified`, `permissions`, `deleted`, `avatar`, `url1`, `url2`, `url3`, `url4`) VALUES (7, 'user5', 'correo5@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$dmg5Zmo1WEtVcU1xRmVpdw$g9QtZDzEy2q+pj07Y2DP/adEAPmzYVsvjtZnLyD0UNM', NULL, NULL, '2020-12-20 14:50:06', 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`uid`, `username`, `email`, `password`, `recoverytoken`, `recovery_at`, `created_at`, `active`, `suspended`, `verified`, `permissions`, `deleted`, `avatar`, `url1`, `url2`, `url3`, `url4`) VALUES (8, 'user6', 'correo6@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$dmg5Zmo1WEtVcU1xRmVpdw$g9QtZDzEy2q+pj07Y2DP/adEAPmzYVsvjtZnLyD0UNM', NULL, NULL, '2020-12-20 14:50:06', 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`uid`, `username`, `email`, `password`, `recoverytoken`, `recovery_at`, `created_at`, `active`, `suspended`, `verified`, `permissions`, `deleted`, `avatar`, `url1`, `url2`, `url3`, `url4`) VALUES (9, 'user7', 'correo7@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$dmg5Zmo1WEtVcU1xRmVpdw$g9QtZDzEy2q+pj07Y2DP/adEAPmzYVsvjtZnLyD0UNM', NULL, NULL, '2020-12-20 14:50:06', 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`uid`, `username`, `email`, `password`, `recoverytoken`, `recovery_at`, `created_at`, `active`, `suspended`, `verified`, `permissions`, `deleted`, `avatar`, `url1`, `url2`, `url3`, `url4`) VALUES (10, 'user8', 'correo8@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$dmg5Zmo1WEtVcU1xRmVpdw$g9QtZDzEy2q+pj07Y2DP/adEAPmzYVsvjtZnLyD0UNM', NULL, NULL, '2020-12-20 14:50:06', 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`uid`, `username`, `email`, `password`, `recoverytoken`, `recovery_at`, `created_at`, `active`, `suspended`, `verified`, `permissions`, `deleted`, `avatar`, `url1`, `url2`, `url3`, `url4`) VALUES (11, 'user9', 'correo9@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$dmg5Zmo1WEtVcU1xRmVpdw$g9QtZDzEy2q+pj07Y2DP/adEAPmzYVsvjtZnLyD0UNM', NULL, NULL, '2020-12-20 14:50:06', 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`uid`, `username`, `email`, `password`, `recoverytoken`, `recovery_at`, `created_at`, `active`, `suspended`, `verified`, `permissions`, `deleted`, `avatar`, `url1`, `url2`, `url3`, `url4`) VALUES (14, 'user10', 'correo10@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$dmg5Zmo1WEtVcU1xRmVpdw$g9QtZDzEy2q+pj07Y2DP/adEAPmzYVsvjtZnLyD0UNM', NULL, NULL, '2020-12-20 14:50:06', 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`uid`, `username`, `email`, `password`, `recoverytoken`, `recovery_at`, `created_at`, `active`, `suspended`, `verified`, `permissions`, `deleted`, `avatar`, `url1`, `url2`, `url3`, `url4`) VALUES (15, 'user11', 'correo11@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$dmg5Zmo1WEtVcU1xRmVpdw$g9QtZDzEy2q+pj07Y2DP/adEAPmzYVsvjtZnLyD0UNM', NULL, NULL, '2020-12-20 14:50:06', 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user` (`uid`, `username`, `email`, `password`, `recoverytoken`, `recovery_at`, `created_at`, `active`, `suspended`, `verified`, `permissions`, `deleted`, `avatar`, `url1`, `url2`, `url3`, `url4`) VALUES (16, 'user12', 'correo12@mail.com', '$argon2id$v=19$m=65536,t=4,p=1$dmg5Zmo1WEtVcU1xRmVpdw$g9QtZDzEy2q+pj07Y2DP/adEAPmzYVsvjtZnLyD0UNM', NULL, NULL, '2020-12-20 14:50:06', 1, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL);
