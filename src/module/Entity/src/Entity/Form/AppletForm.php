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
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Url;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Regex;

class AppletForm extends Form
{
    public function __construct(LicenseInterface $license)
    {
        parent::__construct('applet');
        $this->add(new CsrfToken('csrf'));

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'clearfix');

        $this->add((new Text('title'))->setAttribute('id', 'title')->setLabel('Title:'));
        $this->add((new Url('url'))->setAttribute('id', 'url')->setLabel('Applet Url:'));
        $this->add((new Textarea('content'))->setAttribute('id', 'content')->setLabel('Description:'));
        $this->add(
            (new Textarea('reasoning'))->setAttribute('id', 'reasoning')->setLabel('Reasoning:')
        );
        $this->add(
            (new Textarea('changes'))->setAttribute('id', 'changes')->setLabel('Changes:')->setAttribute(
                'class',
                'plain'
            )
        );
        $this->add((new Text('meta_title'))->setAttribute('id', 'meta_title')->setLabel('Search Engine Title:'));
        $this->add((new Text('meta_description'))->setAttribute('id', 'meta_description')->setLabel('Search Engine Description:'));
        $this->add(new AgreementFieldset($license));
        $this->add(new Controls());

        $inputFilter = new InputFilter('applet');
        $inputFilter->add(['name' => 'title', 'required' => true]);
        $inputFilter->add(
            [
                'name'       => 'url',
                'required'   => true,
                'filters'    => [
                    [
                        'name' => 'StripTags',
                    ],
                ],
                'validators' => [
                    [
                        'name'    => 'Regex',
                        'options' => [
                            'pattern'  => '~^(https?:\/\/)?(.*?(geogebra\.org\/m\/.+|ggbm\.at\/.+))~',
                            'messages' => [
                                Regex::NOT_MATCH => 'Applet-URL invalid. Use one of the form geogebra.org/m/id or ggbm.at/id',
                            ],
                        ],
                    ],
                ],
            ]
        );
        $inputFilter->add(['name' => 'content', 'required' => false]);
        $inputFilter->add(['name' => 'meta_title', 'required' => false]);
        $inputFilter->add(['name' => 'meta_description', 'required' => false]);
        $inputFilter->add(['name' => 'reasoning', 'required' => false]);
        $inputFilter->add(['name' => 'changes', 'required' => false]);
        $this->setInputFilter($inputFilter);
    }
}
