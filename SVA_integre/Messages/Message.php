<?php

namespace Messages;


/**
 * @author BNG
 * 31/05/2018
 */ 

class Message{

    public static $TYPE_MESSAGE_MAIL="MAIL";
    public static $TYPE_MESSAGE_SMS="SMS";
    const HEADER_DEFAULT = "AFNETIC";
    private $src;
    private $dst;
    private $message;
    private $header;
    private $type;
    private $mailler;
    private $config;

    public function __construct($config = array()){
        $this->header = "";
        if (array_key_exists('src', $config)) {
            $this->src = $config['src'];
        }
        if (array_key_exists('message', $config)) {
            $this->message = $config['message'];
        }
        if (array_key_exists('type', $config)) {
            $this->type = $config['type'];
        }
        if (array_key_exists('dst', $config)) {
            $this->dst = $config['dst'];
        }
        if (array_key_exists('mailler', $config)) {
            $this->mailler = $config['mailler'];
        }
        if (array_key_exists('config', $config)) {
            $this->config = $config['config'];
        }
        if (array_key_exists('header', $config)) {
            $this->header = $config['header'];
        }else $this->header = HEADER_DEFAULT;
    }


    /**
     * Function use to send message with parameters
     * @return void
     */
    public function sendMessageWithParameter($msgHelper){
      //envoi de msg a toute les destinations
      if($this->type==static::$TYPE_MESSAGE_SMS){
        $sms = new SMSApi($this->getConfig());
        $senderAddress = "tel:+221".$this->getSrc();
          
         foreach ($this->getDst() as $key => $contact) {
            $sms->sendSMS( 
                $senderAddress,
                "tel:+221".$contact->getNumeroTel(),
                $msgHelper->getMessagePersonnalized($contact),
                $this->getHeader()
            );
         }
         return $sms->getSMSBalance(); 
      }
      elseif($this->type==static::$TYPE_MESSAGE_MAIL){
        foreach ($this->getDst() as $key => $contact) {
            $mail = (new \Swift_Message($this->getHeader()))
                ->setFrom($this->getSrc())
                ->setTo([$contact->getEmail() => $contact->getNom().$contact->getPrenom()])
                ->setBody($msgHelper->getMessagePersonnalized($contact))
                ->setContentType("text/html");
            $this->mailler->send($mail);
        }
        
      }
    }

    /**
     * Fonction sendMessage utilisee pour envoyer un msg a un groupe de contact ou a plusieurs personnes
     * la liste de contact est donnee lors de l'initialisation sur un tableau array dst
     * 
     */

    public function sendMessage(){
         if($this->type==static::$TYPE_MESSAGE_SMS){
             //envoi de msg a toute les destinations
            //$sms = SMSApi::getInstance();
            $sms = new SMSApi($this->getConfig());
            $senderAddress = "tel:+221".$this->getSrc();
            foreach ($this->getDst() as $key => $contact) {
                $sms->sendSMS(
                    $senderAddress,
                    "tel:+221".$contact->getNumeroTel(),
                    $this->getMessage(),
                    $this->getHeader()
                );
             }
             return $sms->getSMSBalance();
         }elseif($this->type==static::$TYPE_MESSAGE_MAIL){
            $mail = (new \Swift_Message($this->getHeader()))
                    ->setFrom($this->getSrc())
                    ->setTo($this->getListMail())
                    ->setBody($this->getMessage())
                    ->setContentType("text/html");
            $this->mailler->send($mail);
         }
    }

    public function getListMail(){
        $list = array();
        foreach ($this->getDst() as $key => $contact) {
            $list[] = $contact->getEmail();
         }
         return $list;
    }

    /***%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
     **************************************************
     *          GETTERs AND SETTERs                   *
     **************************************************
     %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%*/

    /**
     * Get the value of src
     */ 
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * Set the value of src
     *
     * @return  self
     */ 
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get the value of dst
     */ 
    public function getDst()
    {
        return $this->dst;
    }

    /**
     * Set the value of dst
     *
     * @return  self
     */ 
    public function setDst($dst)
    {
        $this->dst = $dst;

        return $this;
    }

    /**
     * Get the value of message
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */ 
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of header
     */ 
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set the value of header
     *
     * @return  self
     */ 
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }


    /**
     * Get the value of config
     */ 
    public function getConfig()
    {
        return $this->config;
    }
}