<?php

namespace App\Services\Vendor;

use App\Enums\Trigger\UptimeRobot\HttpMethodEnum;
use App\Exceptions\MessageException;
use App\Services\Logs\VendorLogService;
use Illuminate\Support\Facades\Log;

/**
 * Class UptimeRobotService
 *
 * @package App\Services\Vendor
 * @docs https://uptimerobot.com/api/
 */
class UptimeRobotService
{
    /**
     * @param array $data
     * @return object
     * @throws MessageException
     */
    public function getMonitors(array $data = []): object
    {
        return $this->sendRequest('https://api.uptimerobot.com/v2/getMonitors', $data);
    }

    /**
     * @param array $data
     * @return object
     * @throws MessageException
     */
    public function newMonitor(array $data = []): object
    {
        $data = $this->prepareMonitorRequest($data);

        return $this->sendRequest('https://api.uptimerobot.com/v2/newMonitor', $data);
    }

    /**
     * @param int   $id
     * @param array $data
     * @return object
     * @throws MessageException
     */
    public function editMonitor(int $id, array $data = []): object
    {
        $data = $this->prepareMonitorRequest($data);

        $data['id'] = $id;

        return $this->sendRequest('https://api.uptimerobot.com/v2/editMonitor', $data);
    }

    /**
     * @param int $id
     * @return object
     * @throws MessageException
     */
    public function deleteMonitor(int $id): object
    {
        $data = [
            'id' => $id,
        ];

        return $this->sendRequest('https://api.uptimerobot.com/v2/deleteMonitor', $data);
    }

    private function prepareMonitorRequest(array $data = []): array
    {
        // Remove values if selected special types
        if ($data['type'] == HttpMethodEnum::head || $data['type'] == HttpMethodEnum::get) {
            unset($data['post_type']);
            unset($data['post_value']);
            unset($data['post_content_type']);
        }

        return $data;
    }

    /**
     * @param array $data
     * @return object
     * @throws MessageException
     */
    public function getAlertContacts(array $data = []): object
    {
        return $this->sendRequest('https://api.uptimerobot.com/v2/getAlertContacts', $data);
    }

    /**
     * @param array $data
     * @return object
     * @throws MessageException
     */
    public function newAlertContact(array $data = []): object
    {
        return $this->sendRequest('https://api.uptimerobot.com/v2/newAlertContact', $data);
    }

    /**
     * @param int   $id
     * @param array $data
     * @return object
     * @throws MessageException
     */
    public function editAlertContact(int $id, array $data): object
    {
        $data['id'] = $id;

        return $this->sendRequest('https://api.uptimerobot.com/v2/editAlertContact', $data);
    }

    /**
     * @param int $id
     * @return object
     * @throws MessageException
     */
    public function deleteAlertContact(int $id): object
    {
        $data['id'] = $id;

        return $this->sendRequest('https://api.uptimerobot.com/v2/deleteAlertContact', $data);
    }

    /**
     * @param string $url
     * @param array  $data
     * @return object
     * @throws MessageException
     */
    private function sendRequest(string $url, array $data = []): object
    {
        $data['api_key'] = config('services.uptime_robot.secret');
        $data['format'] = 'json';

        // Convert string (string)'numeric' to (int)numeric
        foreach ($data as $key => $value) {
            if (is_numeric($value)) {
                $data[$key] = (int)$value;
            }
        }

        $data = array_filter($data, static function ($var) {
            return $var !== null;
        });

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_HTTPHEADER     => [
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_errno($curl);

        curl_close($curl);

        if ($err) {
            VendorLogService::writeError($response, 'UptimeRobot',  $code, $data);
            throw new MessageException('Uptime Robot returned error');
        }

        $response = json_decode($response);

        Log::debug($url, [
            'url'      => $url,
            'data'     => $data,
            'response' => $response,
        ]);

        return $response;
    }
}
