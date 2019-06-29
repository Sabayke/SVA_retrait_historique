<?php
namespace Messages;

class MessageHelper {
    
    private $message;
    private $typeParam=array();
    private $listParam=array();
    private $param;

    public function __construct($message, $param = null){
        $this->message = $message;
        $this->param = $param;
        $this->checkParam();
    }

    public function hasParam(){
        //$check = false;
        
        $this->listParam = explode($this->getParamPattern(), $this->message);
        return count($this->listParam) > 0;
    }

    public function checkParam(){
        if(!$this->hasParam()) return $this->typeParam=array();
        for($i = 0; $i < count($this->listParam); $i++){
            if($i%2 == 1)
                $this->typeParam [] = $this->getParamPattern().$this->listParam[$i].$this->getParamPattern();
        }
        
    }

    public function getMessagePersonnalized($contact){
        $replace = array();
        foreach($this->typeParam as $key => $value){
            if($value === $this->getParamPattern().$this->getParamNom().$this->getParamPattern())           
                $replace[] = $contact->getNom();
            if($value === $this->getParamPattern().$this->getParamPrenom().$this->getParamPattern())           
                $replace[] = $contact->getPrenom();
            if($value === $this->getParamPattern().$this->getParamMail().$this->getParamPattern())           
                $replace[] = $contact->getEmail();
        }
        return str_replace($this->typeParam, $replace, $this->message);
    }


    /**
     * Get the value of typeParam
     */ 
    public function getTypeParam()
    {
        return $this->typeParam;
    }

    /**
     * Set the value of typeParam
     *
     * @return  self
     */ 
    public function setTypeParam($typeParam)
    {
        $this->typeParam = $typeParam;

        return $this;
    }



    /**
     * Get the value of listParam
     */ 
    public function getListParam()
    {
        return $this->listParam;
    }

    /**
     * Set the value of listParam
     *
     * @return  self
     */ 
    public function setListParam($listParam)
    {
        $this->listParam = $listParam;

        return $this;
    }

    public function getParamPattern(){
        return !is_null($this->param)? $this->param->getParamPattern() : MessageHelperConstant::PATTERN;
    }
    
    public function getParamNom(){
        return !is_null($this->param)? $this->param->getParamNom() : MessageHelperConstant::TYPE_PARAM_NOM;
    }

    public function getParamPrenom(){
        return !is_null($this->param)? $this->param->getParamPrenom() : MessageHelperConstant::TYPE_PARAM_PRENOM;
    }

    public function getParamMail(){
        return !is_null($this->param)? $this->param->getParamMail() : MessageHelperConstant::TYPE_PARAM_MAIL;
    }

}
