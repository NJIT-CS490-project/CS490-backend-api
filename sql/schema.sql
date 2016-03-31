DROP TABLE IF EXISTS `Event`; -- : User
DROP TABLE IF EXISTS `Session`; -- : User
DROP TABLE IF EXISTS `User`;

CREATE TABLE IF NOT EXISTS `User` (
	`id`       INT          NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`created`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`admin`    TINYINT(1)   NOT NULL DEFAULT 0,

	PRIMARY KEY (`id`),
	UNIQUE KEY (`username`)
);

CREATE TABLE IF NOT EXISTS `Session` ( -- : User
	`id`         VARCHAR(255) NOT NULL,
	`ownerID`    INT          NOT NULL, -- : User.id
	`created`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`expiration` TIMESTAMP,
	`active`     TINYINT(1)   NOT NULL DEFAULT 1,

	PRIMARY KEY (`id`),

	CONSTRAINT `Session_fk_ownerID`
		FOREIGN KEY (`ownerID`)
		REFERENCES `User` (`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `Event` (
	`id`        INT          NOT NULL AUTO_INCREMENT,
	`name`      VARCHAR(255) NOT NULL,
	`ownerID`   INT          NOT NULL,
	`start`     TIMESTAMP    NOT NULL,
	`end`       TIMESTAMP    NOT NULL,
	`location`  VARCHAR(255) NOT NULL,

	CONSTRAINT `Event_pk`
		PRIMARY KEY (`id`),
	
	CONSTRAINT `Event_fk_ownerID`
		FOREIGN KEY (`ownerID`)
		REFERENCES `User` (`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE
);
