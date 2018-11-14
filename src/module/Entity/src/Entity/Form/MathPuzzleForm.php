<?php
/**
 * This file is part of Athene2.
 *
 * Copyright (c) 2013-2018 Serlo Education e.V.
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
 * @copyright Copyright (c) 2013-2018 Serlo Education e.V.
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Entity\Form;

use Common\Form\Element\CsrfToken;
use License\Entity\LicenseInterface;
use License\Form\AgreementFieldset;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class MathPuzzleForm extends Form
{
    public function __construct(LicenseInterface $license)
    {
        parent::__construct('math-puzzle');
        $this->add(new CsrfToken('csrf'));

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');

        $this->add((new Textarea('content'))->setAttribute('id', 'content')->setLabel('Content:'));
        $this->add(
            (new Textarea('source'))->setAttribute('id', 'source')->setLabel('Source code:')->setAttribute(
                'class',
                'plain'
            )
        );
        $this->add(
            (new Textarea('changes'))->setAttribute('id', 'changes')->setLabel('Changes:')->setAttribute(
                'class',
                'plain control'
            )
        );
        $this->add(new AgreementFieldset($license));
        $this->add(new Controls());

        $inputFilter = new InputFilter('math-puzzle');
        $inputFilter->add(['name' => 'content', 'required' => true]);
        $inputFilter->add(['name' => 'source', 'required' => true]);
        $inputFilter->add(['name' => 'changes', 'required' => false]);
        $this->setInputFilter($inputFilter);
    }
}
