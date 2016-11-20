<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CommentController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin/post/{postId}/comments")
     * @Template("comments/list.html.twig")
     */
    public function getCommentsAction($postId)
    {
        $comments = $this->em->getRepository(Comment::class)->findBy(['post' => $postId]);

        return ['comments' => $comments];
    }
}