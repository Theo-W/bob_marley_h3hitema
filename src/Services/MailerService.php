<?php


namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailerService implements MailerServiceInterface
{
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function send(string $from, string $to, string $htmlTemplate,string $textTemplate, array $params)
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to(new Address($to))
            ->htmlTemplate($htmlTemplate)
            ->textTemplate($textTemplate)
            ->context($params);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            $this->logger->error("Un problème est survenue lors de l'envoye de mail", [
                'exception' => $exception,
            ]);
        }
    }
}