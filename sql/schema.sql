DROP TABLE IF EXISTS `Session`; -- : User
DROP TABLE IF EXISTS `User`;

CREATE TABLE IF NOT EXISTS `User` (
	`userID`   INT          NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`created`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,

	PRIMARY KEY (`userID`),
	UNIQUE KEY (`username`)
);

CREATE TABLE IF NOT EXISTS `Session` ( -- : User
	`sessionID`  VARCHAR(255) NOT NULL,
	`ownerID`    INT          NOT NULL, -- : User.userID
	`created`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`expiration` TIMESTAMP,
	`active`     TINYINT(1)   NOT NULL DEFAULT 1,

	PRIMARY KEY (`sessionID`),

	CONSTRAINT `Session_fk_ownerID`
		FOREIGN KEY (`ownerID`)
		REFERENCES `User` (`userID`)
		ON UPDATE CASCADE
		ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `Organization` (
	`organizationID` INT          NOT NULL AUTO_INCREMENT,
	`name`           VARCHAR(255) NOT NULL,

	CONSTRAINT (`Organization_pk`)
		PRIMARY KEY (`organiizationID`),

	CONSTRAINT (`Organization_uk_name`)
		UNIQUE KEY (`name`)
);

CREATE TABLE IF NOT EXISTS `Event` (
	`eventID`   INT          NOT NULL AUTO_INCREMENT,
	`name`      VARCHAR(255) NOT NULL,
	`timeStart` TIMESTAMP    NOT NULL,
	`timeEnd`   TIMESTAMP    NOT NULL,
	`location`  VARCHAR(255) NOT NULL,
	`organization`

	CONSTRAINT `Event_pk`
		PRIMARY KEY (`eventID`),

	CONSTRAINT `Event_uk_name`
		UNIQUE KEY (`name`)
);

CREATE TABLE IF NOT EXISTS `EventManager` (
	`eventID`       INT NOT NULL,
	`managerUserID` INT NOT NULL,
	`added`         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

	CONSTRAINT `EventManager_pk`
		PRIMARY KEY (`eventID`, `managerUserID`),

	CONSTRAINT `EventManager_fk_Event`
		FOREIGN KEY (`eventID`)
		REFERENCES ``

);
