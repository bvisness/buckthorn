CREATE TABLE `bio_count` (
  `bc_id` int(11) NOT NULL auto_increment,
  `o_id` int(11) NOT NULL,
  `bc_count` int(11) default NULL,
  `bc_is_buckthorn` tinyint(1) default NULL,
  PRIMARY KEY  (`bc_id`),
  UNIQUE KEY `bc_id_UNIQUE` (`bc_id`),
  KEY `o_id_idx` (`o_id`),
  CONSTRAINT `fk_bio_count_has_observation` FOREIGN KEY (`o_id`) REFERENCES `observation` (`o_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `competition` (
  `c_buckthorn_id` int(11) NOT NULL auto_increment,
  `o_id` int(11) NOT NULL,
  `c_dbh_buckthorn` float default NULL,
  `c_dbh_neighbor_b` float default NULL,
  `c_dbh_neighbor_nb` float default NULL,
  `c_distance_b` float default NULL,
  `c_distance_nb` float default NULL,
  PRIMARY KEY  (`c_buckthorn_id`),
  UNIQUE KEY `b_id_UNIQUE` (`c_buckthorn_id`),
  KEY `fk_competition_has_observation` (`o_id`),
  CONSTRAINT `fk_competition_has_observation` FOREIGN KEY (`o_id`) REFERENCES `observation` (`o_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `membership` (
  `m_id` int(11) NOT NULL auto_increment,
  `r_id` int(11) NOT NULL,
  `t_id` int(11) NOT NULL,
  `begin` datetime default NULL,
  `end` datetime default NULL,
  PRIMARY KEY  (`m_id`),
  UNIQUE KEY `m_id_UNIQUE` (`m_id`),
  KEY `fk_researcher_has_team_team1_idx` (`t_id`),
  KEY `fk_researcher_has_team_researcher1_idx` (`r_id`),
  CONSTRAINT `fk_researcher_has_team_researcher1` FOREIGN KEY (`r_id`) REFERENCES `researcher` (`r_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_researcher_has_team_team1` FOREIGN KEY (`t_id`) REFERENCES `team` (`t_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `notes` (
  `o_id` int(11) NOT NULL,
  `n_habitat` varchar(1024) collate utf8_unicode_ci default NULL,
  `n_general` varchar(1024) collate utf8_unicode_ci default NULL,
  `n_biodiversity` varchar(1024) collate utf8_unicode_ci default NULL,
  `n_competition` varchar(1024) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`o_id`),
  UNIQUE KEY `o_id_UNIQUE` (`o_id`),
  CONSTRAINT `fk_notes_has_observation` FOREIGN KEY (`o_id`) REFERENCES `observation` (`o_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `observation` (
  `o_id` int(11) NOT NULL auto_increment,
  `t_id` int(11) NOT NULL,
  `o_date` datetime NOT NULL,
  `o_latitude` decimal(8,5) NOT NULL,
  `o_longitude` decimal(8,5) NOT NULL,
  `o_quadrantsize` float default NULL,
  `o_numstems` int(11) default NULL,
  `o_foliar` decimal(3,2) default NULL,
  `o_circumference` decimal(4,2) default NULL,
  `o_photos` varchar(200) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`o_id`),
  UNIQUE KEY `o_id_UNIQUE` (`o_id`),
  KEY `t_id_idx` (`t_id`),
  CONSTRAINT `fk_observation_has_team` FOREIGN KEY (`t_id`) REFERENCES `team` (`t_id`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `researcher` (
  `r_id` int(11) NOT NULL auto_increment,
  `r_name` varchar(45) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`r_id`),
  UNIQUE KEY `idresearcher_UNIQUE` (`r_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `team` (
  `t_id` int(11) NOT NULL auto_increment,
  `t_name` varchar(45) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`t_id`),
  UNIQUE KEY `t_id_UNIQUE` (`t_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
