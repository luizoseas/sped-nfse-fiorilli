<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\NFSeFiorilli\Tools;
use NFePHP\NFSeFiorilli\Common\Soap\SoapFake;
use NFePHP\NFSeFiorilli\Common\FakePretty;

try {
    
    $config = [
        'cnpj' => '01001001000113',
        'cpf' => null,
        'im' => '15000',
        'cmun' => '3504800', //ira determinar as urls e outros dados
        'razao' => 'Empresa Test Ltda',
        'tpamb' => 2, //1-producao, 2-homologacao
        'login' => '01001001000113',
        'senha' => '123456'
    ];
    $configJson = json_encode($config);

    $content = file_get_contents('expired_certificate.pfx');
    $password = 'associacao';
    $cert = Certificate::readPfx($content, $password);

    $soap = new SoapFake();
    $soap->disableCertValidation(true);
    
    $tools = new Tools($configJson, $cert);
    $tools->loadSoapClass($soap);

    /*
     * Tipos de RPS
     * 1 – RPS
     * 2 – Nota Fiscal Conjugada (Mista)
     * 3 – Cupom
     */
    $numero = 973;
    $serie = '1';
    $tipo = 1;
    
    $response = $tools->consultarNfsePorRps($numero, $serie, $tipo);

    echo FakePretty::prettyPrint($response, '');
 
} catch (\Exception $e) {
    echo $e->getMessage();
}