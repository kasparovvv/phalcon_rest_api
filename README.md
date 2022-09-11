# REST API Phalcon Application
 

# Run the app
 Docker compose up -d 
 
## REST API
The REST API to the app is described below.
 
## Register a new Cusotmer

### Request

`POST /thing/`

    curl -i -H 'Accept: application/json' -d 'name=Foo&status=new' http://localhost:9000/api/v1/register

### Response

    HTTP/1.1 201 Created
    Date: Thu, 24 Feb 2011 12:36:30 GMT
    Status: 201 Created
    Connection: close
    Content-Type: application/json
    Location: /thing/1
    Content-Length: 36

    {"id":1,"name":"Foo","status":"new"}

 



