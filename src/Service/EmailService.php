<?php
namespace App\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class EmailService
{
    private $mailer;
    private $emailAdmin;
    private $emailDeveloper;
    private $appEnv;
    private $logger;

    public function __construct(
        MailerInterface $mailer, 
        string $emailAdmin , 
        string $emailDeveloper , 
        string $appEnv,
        LoggerInterface $mailerLogger
    ){
        $this->mailer = $mailer;
        $this->emailAdmin = $emailAdmin;
        $this->emailDeveloper = $emailDeveloper;
        $this->appEnv = $appEnv;
        $this->logger = $mailerLogger;
    }

    // void :elle retounr rien
    /**
     * $data=[
     * 'from' => '', // if empty => admi,Email
     * 'to' => '',
     * 'replayTo' => '',
     * 'subject' => '',
     * 'template' => '',
     * 'context' =>[]
     * ]
     */
    
    public function send(array $data): bool
    {

        if ($this->appEnv === 'dev') {
            if (!isset($data['subject'])) {
                throw new Exception("You should specify a subject");
            }

            $data['to'] =$this->emailDeveloper;
            // $data['from']=$this->emailDeveloper;
        }
        
        


        $email = (new TemplatedEmail())
            ->from($data['from'] ??  $this->emailAdmin)
            ->to($data['to'] ?? $this->emailAdmin)
            ->replyTo( $data['replyTo'] ?? $data['from'] ?? $this->emailAdmin)    
            ->subject($data['subject'] ?? 'Mon site') 

            // path of the Twig template to render
            // ->htmlTemplate('emails/signup.html.twig')
            ->htmlTemplate($data['template'])

            // pass variables (name => value) to the template
            // ->context([
            //     'expiration_date' => new \DateTime('+7 days'),
            //     'username' => 'foo',
            // ]);
            ->context( $data['context'] ?? [] );

        try {
            $this->mailer->send($email);
            return true;
        } catch (Exception $e) {
            $this->logger->alert(sprintf("%s in %s at %s : %s", __FUNCTION__, __FILE__, __LINE__, $e->getMessage()));
        }

        return false ;
        
    }

    
}
?>