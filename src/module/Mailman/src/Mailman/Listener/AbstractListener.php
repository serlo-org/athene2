<?php
/**
 * This file is part of Athene2.
 *
 * Copyright (c) 2013-2019 Serlo Education e.V.
 *
 * Licensed under the Apache License, Version 2.0 (the "License")
 * you may not use this file except in compliance with the License
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @copyright Copyright (c) 2013-2019 Serlo Education e.V.
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Mailman\Listener;

use Common\Listener\AbstractSharedListenerAggregate;
use Mailman\MailmanAwareTrait;
use Mailman\MailmanInterface;
use Mailman\Renderer\MailRendererInterface;
use Zend\I18n\Translator\Translator;
use Zend\I18n\Translator\TranslatorAwareTrait;

abstract class AbstractListener extends AbstractSharedListenerAggregate
{
    use MailmanAwareTrait;
    use TranslatorAwareTrait;

    /**
     * @var MailRendererInterface
     */
    protected $renderer;

    /**
     * @param MailmanInterface  $mailman
     * @param MailRendererInterface $mailRenderer
     * @param Translator        $translator
     */
    public function __construct(MailmanInterface $mailman, MailRendererInterface $mailRenderer, Translator $translator)
    {
        $this->mailman    = $mailman;
        $this->translator = $translator;
        $this->renderer   = $mailRenderer;
    }

    /**
     * @return MailRendererInterface $renderer
     */
    public function getMailRenderer()
    {
        return $this->renderer;
    }
}
