<?php

$router->get('/api-v1/getdatauser', 'UserController:getDataUser', [
    'Authorization'
]);
$router->post('/api-v1/signup', 'UserController:signup', [
    'ValidationSignup'
]);
$router->post('/api-v1/login', 'UserController:login', [
    'ValidationLogin'
]);
$router->get('/api-v1/auth-user', 'UserController:authUser', [
    'Authorization'
]);

$router->get('/api-v1/alltransactions', 'TransactionsController:getAllTransactions', [
    'Authorization'
]);
$router->get('/api-v1/revenues-user', 'TransactionsController:getRevenues', [
    'Authorization'
]);
$router->get('/api-v1/expenses-user', 'TransactionsController:getExpenses', [
    'Authorization'
]);
$router->post('/api-v1/transaction-create', 'TransactionsController:createTransaction', [
    'Authorization'
]);
$router->put('/api-v1/transactionupdate/{id}', 'TransactionsController:editTransaction', [
    'Authorization'
]);
$router->delete('/api-v1/transactiondelete/{id}', 'TransactionsController:deleteTransaction', [
    'Authorization'
]);