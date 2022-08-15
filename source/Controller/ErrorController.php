<?php

namespace source\Controller;
use core\Response;

class ErrorController {
    public function error($msgError, $code = 400){
        Response::amountResponse(['error' => $msgError, 'code' => $code],$code);
    }
}