# REST API Phalcon Application

# Run the app
 Docker compose up -d 
 
## REST API

 
|   API         | Crud          | Description |
| ------------- | ------------- |-------------| 
| POST /api/v1/register | CREATE | Create new customer| 
| POST /api/v1/auth |  READ  | Obtain Customer access token|
| POST /api/v1/orders/create_order |  CREATE  | Create new order|
| GET  /api/v1/orders/order/{orderId} |  READ  | Get a single Order with products|
| GET  /api/v1/orders |  READ  | List all orders belonging to the customer|
| POST /api/v1/orders/update |  UPDATE  | Update the Order|


Test the API endpoints using [Postman](https://www.postman.com/).
