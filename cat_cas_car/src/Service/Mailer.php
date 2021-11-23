<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{
    const SETTINGS = [
        'FROM' => 'noreply@symfony.skillbox',
        'NAME' => 'Spill-Coffee-On-The-Keyboard'
    ];
    
    private MailerInterface $mailer;
    
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    
    private function send(string $template, User $user, string $subject, \Closure $callback = null)
    {
        $email = (new TemplatedEmail())
            ->from(new Address(self::SETTINGS['FROM'], 'Cat-Cas-Car'))
            ->to(new Address($user->getEmail(), $user->getFirstName()))
            ->subject($subject)
            ->htmlTemplate($template)
        ;
        
        if ($callback) {
            $callback($email);
        }
        
        $this->mailer->send($email);
    }
    
    public function sendWelcomeMail(User $user): void
    {
        $this->send(
            "email/welcome.html.twig",
            $user,
            'Добро пожаловать на CatCasCar',
        );
    }
    
    public function sendWeeklyNewsletter(User $user, array $articles): void
    {
        $this->send(
            'email/weekly-newsletter.html.twig',
            $user,
            'Еженедельная рассылка статей Cat-Cas-Car',
            function (TemplatedEmail $email) use ($articles) {
                $email
                    ->context([
                        'articles' => $articles
                    ])
                    ->attach("Опубликовано статей на сайте за неделю: " . count($articles), "report_".date('Y-d-m').".txt")
                ;
            }
        );
    }
    
    public function sendReport(string $emailAddress, string $reportFilename)
    {
    
        $email = (new TemplatedEmail())
            ->from(new Address(self::SETTINGS['FROM'], 'Cat-Cas-Car'))
            ->to($emailAddress)
            ->subject('Отчёт за период')
            ->htmlTemplate('email/report.html.twig')
            ->attachFromPath($reportFilename, $reportFilename)
        ;
    
        $this->mailer->send($email);
    }
    
    public function sendNewArticleNotification(User $user, Article $article)
    {
        $this->send(
            'email/new_article.html.twig',
            $user,
            'Была создана новая статья - ' . $article->getTitle(),
            function (TemplatedEmail $email) use ($article) {
                $email
                    ->context([
                        'article' => $article
                    ])
                ;
            }
        );
    }
}