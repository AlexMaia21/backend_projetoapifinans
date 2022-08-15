<?php

namespace source\Controller;

use core\Response;
use Exception;
use source\Model\TransactionsDao;
use source\Utils\JwtDecode;
use core\Request;

class TransactionsController
{
    /**
     * trait JwtDecode
     */
    use JwtDecode;
    /**
     * @param Request
     */
    public function getAllTransactions(Request $request)
    {
        $idUser = $this->JwtDecode($request)['sub'];

        $transactionsDao = new TransactionsDao;
        $data = $transactionsDao->readAllTransactions($idUser);

        Response::amountResponse($data);
    }
    public function getRevenues(Request $request)
    {
        $idUser = $this->JwtDecode($request)['sub'];

        $transactionsDao = new TransactionsDao;
        $typeTransaction = 'revenue';
        $data = $transactionsDao->readRevenuesOrExpenses($idUser, $typeTransaction);

        Response::amountResponse($data);
    }
    public function getExpenses(Request $request)
    {
        $idUser = $this->JwtDecode($request)['sub'];

        $transactionsDao = new TransactionsDao;
        $typeTransaction = 'expense';
        $data = $transactionsDao->readRevenuesOrExpenses($idUser, $typeTransaction);

        Response::amountResponse($data);
    }
    public function createTransaction(Request $request){
        $idUser = $this->JwtDecode($request)['sub'];

        $value = (float) $request->postVars['value'];
        $description = filter_var($request->postVars['description'], FILTER_SANITIZE_SPECIAL_CHARS);
        $category = filter_var($request->postVars['category'], FILTER_SANITIZE_SPECIAL_CHARS);
        $typeTransaction = filter_var($request->postVars['type_transaction'], FILTER_SANITIZE_SPECIAL_CHARS);
        $currentTime = time();

        $transactionsDao = new TransactionsDao;
        $transactionsDao->addTransaction($idUser, $typeTransaction, $category, $value, $description, $currentTime);

        Response::amountResponse('created', 201);
    }
    /**
     * @param Request $request
     * @param string|int $vars => transactiondelete/{id}
     */
    public function editTransaction(Request $request, $vars)
    {
        $idUser = $this->JwtDecode($request)['sub'];
        $idTransaction = (int) $vars['id'];

        if ($idTransaction > 0) {
            $category = filter_var($request->putVars['category'], FILTER_SANITIZE_SPECIAL_CHARS);
            $description = filter_var($request->putVars['description'], FILTER_SANITIZE_SPECIAL_CHARS);
            $value = (float) $request->putVars['value'];

            $transactionsDao = new TransactionsDao;
            $transactionsDao->editTransaction($idUser, $idTransaction, $category, $description, $value);

            Response::amountResponse('updated transaction');
        } else {
            throw new Exception('incorrect data', 400);
        }
    }
    public function deleteTransaction(Request $request, $vars)
    {
        $idUser = $this->JwtDecode($request)['sub'];
        $idTransaction = (int) $vars['id'];
        if ($idTransaction) {
            $transactionsDao = new TransactionsDao;
            $transactionsDao->deleteTransaction($idUser, $idTransaction);

            Response::amountResponse('transaction deleted');
        } else {
            throw new Exception('incorrect data', 400);
        }
    }
}
