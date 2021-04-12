<?php

namespace App\Http\Controllers\Trigger;

use App\Http\Controllers\Controller;
use App\Models\Trigger\Condition;
use App\Models\Vendor\Monitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * @param Request   $request
     * @param Condition $condition
     * @return \Illuminate\Http\JsonResponse
     */
    public function uptimeRobot(Request $request, Condition $condition)
    {
        Log::info('Uptime robot webhook', [
            'request'   => $request->all(),
            'condition' => $condition->toArray(),
        ]);

        switch ($request->get('alertTypeFriendlyName')) {
            case 'Up':
                Monitor::query()
                    ->create([
                        'vendor_type_id' => $condition->vendor_type_id,
                        'value'          => $condition->parameters->monitor_id,
                    ]);
                break;
            case 'Down':
            default:
                Monitor::query()
                    ->where([
                        'vendor_type_id' => $condition->vendor_type_id,
                        'value'          => $condition->parameters->monitor_id,
                    ])
                    ->delete();
                break;
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }

    /**
     * @param Request   $request
     * @param Condition $condition
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediaSync(Request $request, Condition $condition)
    {
        Log::info('Media sync webhook', [
            'request'   => $request->all(),
            'condition' => $condition->toArray(),
        ]);

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
