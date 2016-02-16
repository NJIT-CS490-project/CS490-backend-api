DROP TABLE IF EXISTS `Session`; -- : User
DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
	`userID`   INT          NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
	`created`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
	
	PRIMARY KEY (`userID`),
	UNIQUE KEY (`username`)
);

CREATE TABLE `Session` ( -- : User
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
