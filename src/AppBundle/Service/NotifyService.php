<?php

namespace AppBundle\Service;

use AppBundle\Entity\Notification;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Cache\Adapter\DoctrineAdapter;

/**
 * @Injectable(lazy=true)
 */
class NotifyService
{
    private $templating;
    private $mailer;
    private $em;
    /**
     * @var DoctrineAdapter
     */
    private $doctrine;

    public function __construct(TwigEngine $templating, \Swift_Mailer $mailer, $doctrine)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getEntityManager();
    }

    public function send($entity) {
        $notifications = $this->em->getRepository(Notification::class)->findBy(['entity' => get_class($entity)]);

        foreach ($notifications as $notification) {
            $message = \Swift_Message::newInstance()
                ->setSubject($notification->getSubject())
                ->setFrom('tomas.prokop01@gmail.com')
                ->setTo($notification->getSendTo())
                ->setBody(
                    $this->templating->render(
                        'email/notify.html.twig',
                        array('text' => $notification->getText())
                    ),
                    'text/html'
                );

            $this->mailer->send($message);
        }
    }
}