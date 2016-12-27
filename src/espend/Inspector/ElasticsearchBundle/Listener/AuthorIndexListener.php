<?php
declare(strict_types = 1);

namespace espend\Inspector\ElasticsearchBundle\Listener;

use espend\Inspector\ImportBundle\Utils\DocCommentUtil;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class AuthorIndexListener implements EventSubscriberInterface
{
    public function onVisitClass(GenericEvent $event)
    {
        if (!$event->hasArgument('doc_comment')) {
            return;
        }

        $authors = DocCommentUtil::extractAuthor($event->getArgument('doc_comment'));
        $event->setArgument('author', $authors);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'es.visit.class' => 'onVisitClass',
        ];
    }
}