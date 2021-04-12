<?php

namespace App\Services\Vendor\Classes;

use App\Enums\Trigger\UptimeRobot\AlertContactTypeEnum;
use App\Exceptions\MessageException;
use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Vendor\Monitor as MonitorModel;
use App\Services\Vendor\UptimeRobotService;
use Exception;
use Illuminate\Support\Facades\Log;

class UptimeRobot extends BaseVendor
{
    /**
     * @param ConditionModel    $condition
     * @param MonitorModel|null $current
     * @return bool
     */
    public function check(ConditionModel $condition, MonitorModel $current = null): bool
    {
        if (!$current) {
            return false;
        }

        return true;
    }

    public function getValidateRulesCreating(string $type): array
    {
        return parent::getValidateRulesCreating($type);
    }

    public function getValidateRulesUpdating(string $type): array
    {
        switch ($type) {
            case 'http':
                return [
                    'parameters.settings'                   => 'required',
                    'parameters.settings.friendly_name'     => 'required|string',
                    'parameters.settings.url'               => 'required|string|url',
                    'parameters.settings.http_username'     => 'required_with:parameters.settings.http_password',//,parameters.settings.http_auth_type',
                    'parameters.settings.http_password'     => 'required_with:parameters.settings.http_username',//,parameters.settings.http_auth_type',
                    'parameters.settings.http_auth_type'    => 'required_with:parameters.settings.http_username,parameters.settings.http_password',
                    'parameters.settings.http_method'       => 'required',
                    'parameters.settings.post_type'         => '',
                    'parameters.settings.post_value'        => '',
                    'parameters.settings.post_content_type' => '',
                ];
            case 'keyword':
                return [
                    'parameters.settings'               => 'required',
                    'parameters.settings.friendly_name' => 'required|string',
                    'parameters.settings.url'           => 'required|string|url',
                    // TODO... add rules
                ];
            case 'ping':
                return [
                    'parameters.settings'               => 'required',
                    'parameters.settings.friendly_name' => 'required|string',
                    'parameters.settings.url'           => 'required|string|url',
                    // TODO... add rules
                ];
            case 'port':
                return [
                    'parameters.settings'               => 'required',
                    'parameters.settings.friendly_name' => 'required|string',
                    'parameters.settings.url'           => 'required|string|url',
                    // TODO... add rules
                ];
            case 'heartBeat':
                return [
                    'parameters.settings'               => 'required',
                    'parameters.settings.friendly_name' => 'required|string',
                    'parameters.settings.url'           => 'required|string|url',
                    // TODO... add rules
                ];
            default:
                throw new MessageException('Unknown type of monitor');
        }
    }

    public function updating(ConditionModel &$condition)
    {
        /**
         * Create monitor
         */

        $settings = $condition->parameters->settings;

        $alertContactId = $condition->parameters->alert_contact_id;
        $monitorId = $condition->parameters->monitor_id;

        $isInitialScript = !$monitorId;

        /** @var UptimeRobotService $service */
        $service = app(UptimeRobotService::class);

        $webhookAddress = route('api.webhook.uptime-robot', [
            'condition' => $condition->id,
        ]);

        $alertContactData = [
            'type'          => AlertContactTypeEnum::webhook,
            'value'         => $webhookAddress . '?',
            'friendly_name' => $settings->friendly_name,
        ];

        if (empty($alertContactId)) {
            $alertContactResponse = $this->createAlertContact($service, $alertContactData);
        } else {
            $alertContactResponse = $this->updateAlertContact($service, $alertContactId, $alertContactData);
        }

        if (isset($alertContactResponse->alertcontact)) {
            // TODO: Ошибка в документации UptimeRobot, напутали ключи
            $alertContactId = $alertContactResponse->alertcontact->id;
        } elseif (isset($alertContactResponse->alert_contact)) {
            // TODO: Ошибка в документации UptimeRobot, напутали ключи
            $alertContactId = $alertContactResponse->alert_contact->id;
        } else {
            Log::error('UptimeRobot error - Property not found', [
                'response' => $alertContactData,
            ]);

            throw new MessageException('UptimeRobot error', 'Property not found');
        }

        $monitorData = (array)$settings;
        $monitorData['alert_contacts'] = $alertContactId . '_0_0';

        if (empty($monitorId)) {
            $monitorResponse = $this->createMonitor($service, $monitorData);
        } else {
            $monitorResponse = $this->updateMonitor($service, $monitorId, $monitorData);
        }

        $monitorId = $monitorResponse->monitor->id;

        $parameters = $condition->parameters;
        $parameters->monitor_id = $monitorId;
        $parameters->alert_contact_id = $alertContactId;

        $condition->setAttribute('parameters', $parameters);

        // UptimeRobot не шлёт первый вебхук, по этому включаем сайт сразу
        if ($isInitialScript && $monitorId != null) {
            MonitorModel::query()
                ->create([
                    'vendor_type_id' => $condition->vendor_type_id,
                    'value'          => $condition->parameters->monitor_id,
                ]);
        }
    }

    public function deleted(ConditionModel &$condition)
    {
        $alertContactId = $condition->parameters->alert_contact_id;
        $monitorId = $condition->parameters->monitor_id;

        if (!empty($alertContactId)) {
            /** @var UptimeRobotService $service */
            $service = app(UptimeRobotService::class);

            try {
                $service->deleteAlertContact($alertContactId);
            } catch (MessageException $e) {
            }
        }

        if (!empty($monitorId)) {
            /** @var UptimeRobotService $service */
            $service = app(UptimeRobotService::class);

            try {
                $service->deleteMonitor($monitorId);
            } catch (MessageException $e) {
            }
        }
    }

    protected function getValue(ConditionModel $condition)
    {
        try {
            return MonitorModel::query()
                ->where('vendor_type_id', $condition->vendor_type_id)
                ->where('value', $condition->parameters->monitor_id)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $exception) {
            return null;
        }
    }

    /**
     * @param ConditionModel $condition
     * @return null
     */
    public function currentHttp(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return null
     */
    public function currentKeyword(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return null
     */
    public function currentPing(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return null
     */
    public function currentPort(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return null
     */
    public function currentHeartBeat(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param UptimeRobotService $service
     * @param array              $alertContactData
     * @return object
     * @throws MessageException
     */
    private function createAlertContact(UptimeRobotService $service, array $alertContactData)
    {
        $alertContactResponse = $service->newAlertContact($alertContactData);

        if ($alertContactResponse->stat == 'ok') {
            return $alertContactResponse;
        }

        if ($alertContactResponse->error->type == 'already_exists') {
            // Find exist contact
            $exists = false;

            $limit = 50;
            $loop = 0;

            do {
                $offset = $limit * $loop;

                $alertContactsResponse = $service->getAlertContacts([
                    'offset' => $offset,
                    'limit'  => $limit,
                ]);

                foreach ($alertContactsResponse->alert_contacts as $alertContact) {
                    if ($alertContact->value === $alertContactData['value']) {
                        $exists = true;

                        $alertContactId = $alertContact->id;
                    }
                }

                $loop++;
            } while ($exists === false);

            return $service->editAlertContact($alertContactId, $alertContactData);
        }

        throw new MessageException('Uptime Robot returned error', $alertContactResponse->error->message);
    }

    /**
     * @param UptimeRobotService $service
     * @param                    $alertContactId
     * @param                    $alertContactData
     * @return object
     * @throws MessageException
     */
    private function updateAlertContact(UptimeRobotService $service, $alertContactId, $alertContactData)
    {
        $alertContactResponse = $service->editAlertContact($alertContactId, $alertContactData);

        if ($alertContactResponse->stat == 'ok') {
            return $alertContactResponse;
        }

        if ($alertContactResponse->error->type == 'not_found') {
            return $this->createAlertContact($service, $alertContactData);
        }

        throw new MessageException('Uptime Robot returned error', $alertContactResponse->error->message);
    }

    /**
     * @param UptimeRobotService $service
     * @param array              $monitorData
     * @return array|object
     * @throws MessageException
     */
    private function createMonitor(UptimeRobotService $service, array $monitorData)
    {
        $monitorResponse = $service->newMonitor($monitorData);

        if ($monitorResponse->stat == 'ok') {
            return $monitorResponse;
        }

        if ($monitorResponse->error->type == 'already_exists') {
            // Find exist monitor
            $exists = false;

            $limit = 50;
            $loop = 0;

            do {
                $offset = $limit * $loop;

                $alertMonitorsResponse = $service->getMonitors([
                    'offset' => $offset,
                    'limit'  => $limit,
                ]);

                foreach ($alertMonitorsResponse->monitors as $monitor) {
                    if ($monitor->url === $monitorData['url']) {
                        $exists = true;

                        $monitorId = $monitor->id;
                    }
                }

                $loop++;
            } while ($exists === false);

            return $service->editMonitor($monitorId, $monitorData);
        }

        throw new MessageException('Uptime Robot returned error', $monitorResponse->error->message);
    }

    /**
     * @param UptimeRobotService $service
     * @param                    $monitorId
     * @param array              $monitorData
     * @return array|object
     * @throws MessageException
     */
    private function updateMonitor(UptimeRobotService $service, $monitorId, array $monitorData)
    {
        $monitorResponse = $service->editMonitor($monitorId, $monitorData);

        if ($monitorResponse->stat == 'ok') {
            return $monitorResponse;
        }

        if ($monitorResponse->error->type == 'not_found') {
            return $this->createMonitor($service, $monitorData);
        }

        throw new MessageException('Uptime Robot returned error', $monitorResponse->error->message);
    }
}
