# CS490 Project - Backend API

| Path                        | Meaning                        |
|-----------------------------|--------------------------------|
| ```POST /user/create.php``` | Creates a new user account.    |
| ```POST /user/login.php```  | Logs the user into the system. |

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

## Base URL

`https://web.njit.edu/~mjc55/CS490`
`
