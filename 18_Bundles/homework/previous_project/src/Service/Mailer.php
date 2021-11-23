<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\User;
use Closure;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    
    public function sendWelcomeMail(User $user)
    {
        $this->send('email/welcome.html.twig', 'Добро пожаловать на Spill-Coffee-On-The-Keyboard', $user);
    }
    
    public function sendWeeklyNewsletter(User $user, array $articles)
    {
        $this->send(
            'email/newsletter.html.twig',
            'Еженедельная рассылка Spill-Coffee-On-The-Keyboard',
            $user,
            function (TemplatedEmail $email) use ($articles) {
                $email
                    ->context([
                        'articles' => $articles,
                    ])
                ;
            }
        );
    }
    
    public function sendAdminStatisticsReport(User $user, $filePath)
    {
        $this->send(
            'email/admin_statistics_report.html.twig',
            'Отчет с Spill-Coffee-On-The-Keyboard',
            $user,
            function (TemplatedEmail $email) use ($filePath) {
                $email->attachFromPath($filePath, 'statistics_report.csv');
            }
        );
    }
    
    public function sendAdminAboutNewArticle(User $user, Article $article)
    {
        $this->send(
            'email/admin_new_article.html.twig',
            'Новая статья на Spill-Coffee-On-The-Keyboard',
            $user,
            function (TemplatedEmail $email) use ($article) {
                $email
                    ->context([
                        'article' => $article,
                    ])
                ;
            }
        );
    }
    
    private function send(string $template, string $subject, User $user, Closure $callback = null)
    {
        $email = (new TemplatedEmail())
            ->from(new Address('noreply@symfony.skillbox', 'Spill-Coffee-On-The-Keyboard'))
            ->to(new Address($user->getEmail(), $user->getFirstName()))
            ->htmlTemplate($template)
            ->subject($subject)
        ;
        
        if ($callback) {
            $callback($email);
        }

        $this->mailer->send($email);
    }
}
