#Read Me

1) insert file using postman field name should be `file` 
2) hit `http://127.0.0.1:8000/api/upload-excel` this api with `post` method
3) select file using `form-data` in postman

Note: must configure `QUEUE_CONNECTION`  in `.env` 
i have provided .env files in .env.example you can use that file to configure your project