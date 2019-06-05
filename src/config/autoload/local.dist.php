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
$dbParams = array(
    'host' => 'mysqld',
    'port' => '3306',
    'user' => 'root',
    'password' => 'secret',
    'database' => 'serlo',
);

return [
    'sentry_options' => [
    ],
    'recaptcha_options' => [
        'api_key' => '6LfwJFwUAAAAAKHhl-kjPbA6mCPjt_CrkCbn3okr',
        'secret'  => '6LfwJFwUAAAAAPVsTPLe00oAb9oUTewOUe31pXSv',
    ],
    'markdown' => [
        'host' => 'markdown',
    ],
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => [
                    'host' => $dbParams['host'],
                    'port' => $dbParams['port'],
                    'user' => $dbParams['user'],
                    'password' => $dbParams['password'],
                    'dbname' => $dbParams['database'],
                    // Yes..this is currently the only way i know of fixing database encoding issues
                    'charset' => 'latin1',
                ],
            ],
        ],
    ],
    'solarium' => [
        'endpoint' => [
            'default' => [
                'host' => 'localhost',
                'port' => 8080,
                'path' => '/solr',
                'core' => '',
                'timeout' => 5,
            ],
        ],
    ],
    'instance' => [
        'strategy' => 'Instance\Strategy\DomainStrategy',
    ],
    'dbParams' => array(
        'host' => $dbParams['host'],
        'port' => $dbParams['port'],
        'username' => $dbParams['user'],
        'password' => $dbParams['password'],
        'database' => $dbParams['database'],
    ),
    'smtp_options' => [
        'name' => 'localhost.localdomain',
        'host' => '127.0.0.1',
        'connection_class' => 'login',
        'connection_config' => [
            'username' => 'user',
            'password' => 'pass',
        ],
    ],
];
