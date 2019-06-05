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

namespace Notification\Controller;

use DateTime;
use FeatureFlags\Service;
use Notification\NotificationWorker;
use Zend\Mvc\Controller\AbstractConsoleController;

class WorkerController extends AbstractConsoleController
{

    /**
     * @var NotificationWorker
     */
    protected $notificationWorker;

    /**
     * @return NotificationWorker $notificationWorker
     */
    public function getNotificationWorker()
    {
        return $this->notificationWorker;
    }

    /**
     * @param NotificationWorker $notificationWorker
     * @return self
     */
    public function setNotificationWorker(NotificationWorker $notificationWorker)
    {
        $this->notificationWorker = $notificationWorker;
    }

    public function runAction()
    {
        /**
         * @var $service \FeatureFlags\Service
         */
        $service = $this->serviceLocator->get(Service::class);

        if ($service->isEnabled('separate-mails-from-notifications')) {
            // Deactivate worker
            return 'success';
        }

        $output = "";
        $output .= $this->now() . "Started\n";

        try {
            $this->getNotificationWorker()->run();
            $this->getNotificationWorker()->getObjectManager()->flush();
            $output .= $this->now() . "Successfully finished\n";
        } catch (\Exception $e) {
            $output .= $this->now() . "Failed with message: " . $e->getMessage() . "\n";
        }

        return $output;
    }

    private function now()
    {
        return date(DateTime::ISO8601) . ': ';
    }
}
