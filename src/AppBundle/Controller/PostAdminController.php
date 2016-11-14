<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController;

class PostAdminController extends AdminController
{
    protected function prePersistEntity($entity)
    {
        $this->setComments($entity);
    }

    protected function preUpdateEntity($entity)
    {
        $this->setComments($entity);
    }

    private function setComments ($entity) {

        $comments = $entity->getComments();
        $comment_ids = [];

        foreach ($comments as $comment) {
            $comment->setPost($entity);
            $comment_ids[] = $entity->getId();
        }

        $query = $this->em->createQueryBuilder();
        $query->update(Comment::class, 'c')
              ->set('c.post', 'null')
              ->where('c.post=:e')->setParameter('e', $entity);
        if (count($comment_ids) > 0) {
            $query->andWhere('c.id not in(:id)')->setParameter('id', array_values($comment_ids));
        }
        $query->getQuery()->execute();
    }
}