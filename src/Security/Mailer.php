<?php


namespace App\Security;


use App\Entity\ContactFormDTO;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    private string $appEmail;
    private MailerInterface $mailerInterface;

    function __construct(string $appEmail, MailerInterface $mailerInterface)
    {
        $this->appEmail = $appEmail;
        $this->mailerInterface = $mailerInterface;
    }

    public function sendEmail(string $destination, string $subject, $template, array  $bodyData): bool
    {
        $email = (new TemplatedEmail())
            ->from($this->appEmail)
            ->to($destination)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($bodyData);
        try {
            $this->mailerInterface->send($email);
            return true;
        } catch (TransportExceptionInterface $e) {
            return false;
        }

    }

}