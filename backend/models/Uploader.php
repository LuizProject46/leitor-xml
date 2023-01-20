<?php
class Uploader {

    public $name;
    public $destinationPath = __DIR__ . "/../uploads/";
    public $allowedFiles = [
        "text/xml"
    ];


    public function __construct(string $name){
        $this->name = $name;
    }


    public function validateFile(array $file) {
        try {

            $fileTmpPath = $file["tmp_name"];
            $fileType    = $file["type"];
          
            if(!in_array($fileType, $this->allowedFiles)){
                return false;
            }
            
            if(!is_object(simplexml_load_file($fileTmpPath))){
                return false;
            }

            return true;
               
        }catch(Exception $err){

            return false;
        }


    }

    public function recursionParent($node, $path){
        if($node->localName){
            $path[]= $node->localName;
            $path = $this->recursionParent($node->parentNode, $path);
        }
        
        return $path;
    }

    public function createMapping($file) {
        $xmlData =  file_get_contents("{$this->destinationPath}{$this->name}.xml");
        
        $doc = new DOMDocument();

        $doc->loadXML($xmlData);
        
        $xpath = new DOMXpath( $doc );
        $nodes = $xpath->query( '//*' );

        $nodeNames = [];
        
        foreach( $nodes as $node ){   
            if($node->childElementCount > 0){
                foreach($node->childNodes as $c){
                    $childValues  = explode("\n", ($c->nodeValue));
                    if(!in_array("", $childValues)){
                        $nodeNames[] =  [
                           "value" => $childValues[0],
                           "node" => $c
                        ];
                    }
                }
            }  
        }
        
        
        foreach($nodeNames as $key => $nodeItem){
            $path = [];
            $pathNode = $this->recursionParent($nodeItem["node"], $path);
            $nodeNames[$key] = [
                "value" => $nodeItem["value"],
                "path" => implode("/", array_reverse($pathNode))
            ];
        }

        return $nodeNames;
       
    }


    public function saveFile($file){
        
        $fileTmpPath = $file["tmp_name"];
        $destinationPath = "{$this->destinationPath}{$this->name}.xml";

        if(file_exists($destinationPath)) unlink($destinationPath);

        if(move_uploaded_file($fileTmpPath, $destinationPath)) {
            return [
                "error" => false,
                "message" => "Upload realizado com sucesso!",
            ];
        }

        return [
            "error" => true,
            "message" => "Houve um problema ao realizar o upload do arquivo"
        ];
    }

}


?>