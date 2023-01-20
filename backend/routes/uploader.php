<?php
require_once __DIR__ .'/../models/Uploader.php';

function uploadFile(array $vars)
{   
    if(!isset($_FILES) || !$_FILES){
        return [
            "status" => 401,
            "message" => "Ops! O arquivo é obrigatório."
        ];
    }
    
    if($_FILES["file"]["error"] !== 0 || $_FILES["file"]["size"] === 0){
        return [
            "status" => 401,
            "message" => "Ops! O arquivo está vazio."
        ];
    }

    $fileData = $_FILES["file"];

    $uploader = new Uploader($fileData["name"]);

    $isValidFile = $uploader->validateFile($fileData);

    if(!$isValidFile){
        return [
            "status" => 401,
            "message" => "O arquivo XML é inválido! Verifique o tipo do arquivo."
        ];
    }

    $response = $uploader->saveFile($fileData);
    $response["data"] = $uploader->createMapping($fileData);
    
    if($response["error"]){
        return [
            "status" => 401,
            "message" => $response["message"]
        ];
    }

    return [
        "status" => 200,
        "message" => $response["message"],
        "data" => $response["data"]
    ];
}


?>