<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Mailman\Listener;

use Zend\EventManager\Event;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\I18n\Translator\TranslatorAwareTrait;
use Zend\View\Model\ViewModel;

class AuthenticationControllerListener extends AbstractListener
{

    public function attachShared(SharedEventManagerInterface $events)
    {
        $class = $this->getMonitoredClass();
        $events->attach($class, 'restore-password', [$this, 'onRestore'], -1);
        $events->attach($class, 'activate', [$this, 'onActivate'], -1);
        $events->attach($class, 'activated', [$this, 'onActivated'], -1);
    }

    protected function getMonitoredClass()
    {
        return 'Authentication\Controller\AuthenticationController';
    }

    public function onActivated(Event $e)
    {
        /* @var $user \User\Entity\UserInterface */
        $user = $e->getParam('user');

        $subject = new ViewModel();
        $body    = new ViewModel([
            'user' => $user
        ]);

        $subject->setTemplate('mailman/messages/welcome/subject');
        $body->setTemplate('mailman/messages/welcome/body');

        $this->getMailman()->send(
            $user->getEmail(),
            $this->getMailman()->getDefaultSender(),
            $this->getRenderer()->render($subject),
            $this->getRenderer()->render($body)
        );
    }

    public function onActivate(Event $e)
    {
        /* @var $user \User\Entity\UserInterface */
        $user = $e->getParam('user');

        $subject = new ViewModel();
        $body    = new ViewModel([
            'user' => $user
        ]);

        $subject->setTemplate('mailman/messages/register/subject');
        $body->setTemplate('mailman/messages/register/body');

        $this->getMailman()->send(
            $user->getEmail(),
            $this->getMailman()->getDefaultSender(),
            $this->getRenderer()->render($subject),
            $this->getRenderer()->render($body)
        );
    }

    public function onRestore(Event $e)
    {
        /* @var $user \User\Entity\UserInterface */
        $user = $e->getParam('user');

        $subject = new ViewModel();
        $body    = new ViewModel([
            'user' => $user
        ]);

        $subject->setTemplate('mailman/messages/restore-password/subject');
        $body->setTemplate('mailman/messages/restore-password/body');

        $this->getMailman()->send(
            $user->getEmail(),
            $this->getMailman()->getDefaultSender(),
            $this->getRenderer()->render($subject),
            $this->getRenderer()->render($body)
        );
    }

}
