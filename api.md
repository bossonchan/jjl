# APIs Document

## Example

How to request APIs and get data/error correctly:
```javascript
var baseUrl = "http://example.com/index.php"; // remember to include '/index.php'
$.ajax({
  "type"    : "POST",
  "url"     : baseUrl + "/users",
  "dataType": "json",
  "data": {
    "u_name": "xxx",
    "password": "lajdflajdsf"
  },
  "success": function(data) {
    console.log("Get data here..", data);
  },
  "error": function(err) {
    console.log("Get error message here..", err.responseText);
  }
});
```

## Session

### Login

POST /session

**params**

- u_name
- password

**status**

- 400, 409
- 200
```json
{
  "uid"       : 123123,
  "u_name"    : "xxx",

  "u_profile" : "",
  "u_photo"   : "/avatar.png",
  "u_gender"  : "M",

  "address"   : "",
  "lastVisit" : "2015-10-11 21:00:00"
}
```

### Logout

DELETE /session

**status**

- 409
- 200
```
{
  "uid"       : 123123,
  "u_name"    : "xxx",

  "u_profile" : "",
  "u_photo"   : "/avatar.png",
  "u_gender"  : "M",

  "address"   : "",
  "lastVisit" : "2015-10-11 21:00:00"
}
```

## Users

### Register

POST /users

**params**

- u_name
- password
- block_id
- u_gender - optional
- u_profile - optional
- u_profile - optional
- u_photo - optional
- address - optional

**status**

- 400, 403
- 200
```
{
  "uid"       : 123123,
  "u_name"    : "xxx",

  "u_profile" : "",
  "u_photo"   : "/avatar.png",
  "u_gender"  : "M",

  "address"   : "",
  "lastVisit" : "2015-10-11 21:00:00"
}
```

### Get user info by id

GET /users/`:uid`

**params**

- uid

**status**

- 404
- 200
```
{
  "uid"       : 123123,
  "u_name"    : "xxx",

  "u_profile" : "",
  "u_photo"   : "/avatar.png",
  "u_gender"  : "M",

  "address"   : "",
  "lastVisit" : "2015-10-11 21:00:00"
}
```

### Get current user info (use this API to check if current user is logged in)

GET /users/me

**status**

- 401
- 200
```
{
  "uid"       : 123123,
  "u_name"    : "xxx",

  "u_profile" : "",
  "u_photo"   : "/avatar.png",
  "u_gender"  : "M",

  "address"   : "",
  "lastVisit" : "2015-10-11 21:00:00"
}
```

## Hoods && Blocks

### Get hood list

GET /hoods?`offset`=0&`count`=10

**params**

- offset - optional, default 0
- count  - optional, default 10, use -1 to get total items

**status**

- 400
- 200
```
{
  "offset"     : 1,
  "count"      : 1,
  "nextOffset" : 2,
  "total"      : 100,
  "hoods": [
    {
      "hood_id": 123123,
      "h_name" : "xxx",
      "h_s"    : 10.1,
      "h_w"    : 10.1,
      "h_h"    : 10.1,
      "h_e"    : 10.1,
    }
  ]
}
```
### Get block list by a given hood id

GET /hoods/`:hood_id`/blocks?`offset`=0&`count`=10

**params**

- hood_id
- offset - optional, default 0
- count  - optional, default 10, use -1 to get total items

**status**

- 400, 404
- 200
```
{
  "offset"     : 1,
  "count"      : 1,
  "nextOffset" : 2,
  "total"      : 100,
  "blocks": [
    {
      "block_id": 123123,
      "b_name"  : "xxx",
      "hood_id" : 1222,
      "b_s"     : 10.1,
      "b_w"     : 10.1,
      "b_h"     : 10.1,
      "b_e"     : 10.1,
    }
  ]
}
```

### Join block

POST /blocks/`:block_id`/apply

**params**

- block_id

**status**

- 401, 404
- 200
```
{
  "block_id": 123123,
  "b_name"  : "xxx",
  "hood_id" : 1222,
  "b_s"     : 10.1,
  "b_w"     : 10.1,
  "b_h"     : 10.1,
  "b_e"     : 10.1,
}
```

### Get members of block

GET /blocks/`:block_id`/users?`offset`=0&`count`=10

**params**
- block_id
- offset - optional, default 0
- count  - optional, default 10, use -1 to get total items

**status**
```
{
  "offset"     : 1,
  "count"      : 1,
  "nextOffset" : 2,
  "total"      : 100,
  "users": [
    {
      "uid"       : 123123,
      "u_name"    : "xxx",

      "u_profile" : "",
      "u_photo"   : "/avatar.png",
      "u_gender"  : "M",

      "address"   : "",
      "lastVisit" : "2015-10-11 21:00:00"
    }
  ]
}
```

## Follow

### Follow someone

POST /follow/`:uid`

**params**

- uid

**status**

- 401, 404
- 200
```
{
  "uid"       : 123123,
  "u_name"    : "xxx",

  "u_profile" : "",
  "u_photo"   : "/avatar.png",
  "u_gender"  : "M",

  "address"   : "",
  "lastVisit" : "2015-10-11 21:00:00"
}
```

### Cancel following someone

DELETE /follow/`:uid`

DELETE /follow/`:uid`

**params**

- uid

**status**

- 401, 404
- 200
```
{
  "uid"       : 123123,
  "u_name"    : "xxx",

  "u_profile" : "",
  "u_photo"   : "/avatar.png",
  "u_gender"  : "M",

  "address"   : "",
  "lastVisit" : "2015-10-11 21:00:00"
}
```

## Messages

### Get message list

GET /messages?`offset`=0&`count`=10&`sort`=1&`type`=all

**params**

- type   - enum(all, private, friend, neighbor)
- offset - optional, default 0
- count  - optional, default 10, use -1 to get total items
- sort   - optional, default -1, use 1 for descending and -1 for ascending

**status**

- 400
- 200
```
{
  "offset"     : 1,
  "count"      : 1,
  "nextOffset" : 2,
  "total"      : 100,
  "type":      : "all",
  "messages": [
    {
      "mid"      : 123123123,
      "m_type"   : "private",

      "m_title"  : "abcd",
      "m_content": "alsjflkasdjflasdfj",
      "m_time"   : "2015-12-12 21:00:00",

      "m_from"   : {
        "uid"       : 123123,
        "u_name"    : "xxx",

        "u_profile" : "",
        "u_photo"   : "/avatar.png",
        "u_gender"  : "M",

        "address"   : "",
        "lastVisit" : "2015-10-11 21:00:00"
      },
      "m_to"     : {
        "uid"       : 123123,
        "u_name"    : "xxx",

        "u_profile" : "",
        "u_photo"   : "/avatar.png",
        "u_gender"  : "M",

        "address"   : "",
        "lastVisit" : "2015-10-11 21:00:00"
      },
      "m_hood"   : 123123
    }
  ]
}
```

## Create a message

POST /messages

**params**

- m_type - enum(private, neighbor, friend)
- m_title
- m_content
- m_to
- m_hood

**status**

- 400, 401, 403
- 200
```
{
  "mid"      : 123123123,
  "m_type"   : "private",
 
  "m_title"  : "abcd",
  "m_content": "alsjflkasdjflasdfj",
  "m_time"   : "2015-12-12 21:00:00",
 
  "m_from"   : {
    "uid"       : 123123,
    "u_name"    : "xxx",
 
    "u_profile" : "",
    "u_photo"   : "/avatar.png",
    "u_gender"  : "M",
 
    "address"   : "",
    "lastVisit" : "2015-10-11 21:00:00"
  },
  "m_to"     : {
    "uid"       : 123123,
    "u_name"    : "xxx",
 
    "u_profile" : "",
    "u_photo"   : "/avatar.png",
    "u_gender"  : "M",
 
    "address"   : "",
    "lastVisit" : "2015-10-11 21:00:00"
  },
  "m_hood"   : 123123
}
```

## Friends

### Get friend list

GET /friends?`offset`=0&`count`=10

**params**

- offset - optional, default 0
- count  - optional, default 10, use -1 to get total items

**status**

- 400, 401
- 200
```
  "offset"     : 1,
  "count"      : 1,
  "nextOffset" : 2,
  "total"      : 100,
  "friends": [
    {
      "uid"       : 123123,
      "u_name"    : "xxx",

      "u_profile" : "",
      "u_photo"   : "/avatar.png",
      "u_gender"  : "M",

      "address"   : "",
      "lastVisit" : "2015-10-11 21:00:00"
    }
  ]
```

### Delete friend

DELETE /friends/`:uid`

**params**

- uid

**status**

- 401, 404
- 200
```
{
  "uid"       : 123123,
  "u_name"    : "xxx",

  "u_profile" : "",
  "u_photo"   : "/avatar.png",
  "u_gender"  : "M",

  "address"   : "",
  "lastVisit" : "2015-10-11 21:00:00"
}
```

### Send friend request

POST /friend_request

**params**

- u_name

**status**

- 400, 403, 404
- 200
```
{
  "uid"       : 123123,
  "u_name"    : "xxx",

  "u_profile" : "",
  "u_photo"   : "/avatar.png",
  "u_gender"  : "M",

  "address"   : "",
  "lastVisit" : "2015-10-11 21:00:00"
}
```

### Get friend requests

GET /friend_request?`count`=10*`offset`=0

**params**

- offset - optional, default 0
- count  - optional, default 10, use -1 to get total items

**status**

- 401
- 200
```
{
  "requests": [
    {
      "uid"       : 123123,
      "u_name"    : "xxx",

      "u_profile" : "",
      "u_photo"   : "/avatar.png",
      "u_gender"  : "M",

      "address"   : "",
      "lastVisit" : "2015-10-11 21:00:00"
    }
  ]
}
```

### Accept/Delete friend request

PUT /friend_request/`:uid`

**params**

- uid
- action - enum(accpet, reject)

**status**

- 401, 404
- 200
```
{
  "uid"       : 123123,
  "u_name"    : "xxx",

  "u_profile" : "",
  "u_photo"   : "/avatar.png",
  "u_gender"  : "M",

  "address"   : "",
  "lastVisit" : "2015-10-11 21:00:00"
}
```
