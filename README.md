# CS490 Project - Backend API

All requests should start with the following base url:
``https://web.njit.edu/~mjc55/CS490/public/``

| Path                          | Meaning                                  |
|-------------------------------|------------------------------------------|
| ```POST user/create.php```    | Creates a new user account.              |
| ```POST user/login.php```     | Logs the user into the system.           |
| ```POST user/logout.php```    | Logs the user out of the system.         |
| ```GET  user/self.php```      | Gets information about the current user. |
| ```POST event/create.php```   | Creates a new event.                     |
| ```GET  event/list.php```     | Lists all events.                        |
| ```DELETE event/delete.php``` | Deletes an event.                        |

## ```POST /user/create.php```

Creates a new user account.

Example Request:
```json
{
    "usernaame": "alice",
    "password":  "password"
}
```

Example Response:
```
200 OK
```

Example Response (missing parameter(s)):
```
400 Bad Request
{
    "message": "Missing parameter: <paramname>"
}
```

Example Response (user already exists):
```
400 Bad Request
{
    "message": "Failed to create account."
}
```

## ```POST /user/login.php```

Example Request:
```json
{
    "usernaame": "alice",
    "password":  "password"
}
```

Example Response:
```
200 OK
```

Example Response (missing parameter(s)):
```
400 Bad Request
{
    "message": "Missing parameter: <paramname>"
}
```

Example Response (user does not exist/password does not match):
```
400 Bad Request
{
    "message": "Failed to log in."
}
```

## ```POST /user/logout.php```
Logs the user out of the system. No parameters are required.

## ```GET /user/self.php```
Gets information about the current user.

Sample Response:
```json
{
	"id":        1,
	"username": "alice",
	"created":  "2016-03-31 01:21:27"
	"admin":     false
}
```

## ```POST /event/create.php```
Creates a new event.

Sample Request: (start and end are unix timestamps)
```json
{
	"name":     "Pirate Party",
	"start":     1234,
	"end":       5678,
	"location": "My Place"
}
```

## ```GET event/list.php```
Gets all events.

Sample Response:
```json
[
	{
		"id":        2,
		"name":     "Pirate Party",
		"ownerID":   1,
		"start":     1234,
		"end":       5678,
		"location": "My Place"
	}
]
```

## ```DELETE event/delete.php```
Deletes an event.

Sample Request
```json
{
		"id": 1
}
```
