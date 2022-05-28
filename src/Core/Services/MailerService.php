<?php

namespace App\Core\Services;

class MailerService
{
   
    public function __construct()
    {
        /* $this->menuRepo = $menuRepo; */
    }

    /**
     * Constuct Mail
     * 
     * @param array $mailData
     * @return Array $mail
     */
    public function constructMail($mailData) {
        $mail = $mailData;
        return $mail;
    }


}
