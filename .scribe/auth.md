# Authenticating requests

Authenticate request to this API's enpoints by sending an **`Authotization`** Header with the value **`"Bearer {TOKEN}"`**

## Session Token

To authenticate users, you can use the following endpoint:

GET http://{baseUrl}/sanctum/token

### Parameters

- `email`: The user's email address.
- `password`: The user's password.

### First User 

- `email`: yoda@gmail.com
- `password`: 12345678
