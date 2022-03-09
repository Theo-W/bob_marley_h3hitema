<?php

namespace App\Services;

interface MailerServiceInterface
{
    public function send(string $from, string $to, string $htmlTemplate,string $textTemplate, array $params);
}