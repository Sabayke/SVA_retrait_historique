<?php
namespace Messages;

class MessageHelperConstant
{
    const PATTERN = "#";
    const TYPE_PARAM_NOM = "NOM";
    const TYPE_PARAM_PRENOM = "PRENOM";
    const TYPE_PARAM_EMAIL = "MAIL";
    const TYPE_PARAM_GROUPE = "GROUPE";
    const MAIL_USER = "messagerbroadcaster@gmail.com";
    const MAIL_PASSWORD = "passerSMS";
    const MAIL_HOST = "smtp.gmail.com"; 

    private $nom;
    private $prenom;
    private $mail;
    
    public function __construct($param){
        $this->nom = !is_null($param->getParamPattern())? $param->getParamPattern().$param->getParamNom().$param->getParamPattern(): MessageHelperConstant::PATTERN.MessageHelperConstant::TYPE_PARAM_NOM.MessageHelperConstant::PATTERN;
        $this->prenom = !is_null($param->getParamPattern())? $param->getParamPattern().$param->getParamPrenom().$param->getParamPattern(): MessageHelperConstant::PATTERN.MessageHelperConstant::TYPE_PARAM_PRENOM.MessageHelperConstant::PATTERN;
        $this->mail = !is_null($param->getParamPattern())? $param->getParamPattern().$param->getParamMail().$param->getParamPattern(): MessageHelperConstant::PATTERN.MessageHelperConstant::TYPE_PARAM_EMAIL.MessageHelperConstant::PATTERN;
       
    }
    

    /**
     * Get the value of nom
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */ 
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of prenom
     */ 
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     *
     * @return  self
     */ 
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get the value of mail
     */ 
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set the value of mail
     *
     * @return  self
     */ 
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }
}