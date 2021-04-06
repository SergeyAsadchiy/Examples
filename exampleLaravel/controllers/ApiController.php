<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\LoginByAccessToken;
use App\User_counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    use AccessTrait;

    public function __construct()
    {
        $this->middleware('login_by_token');
    }

    public static function refreshCounters() {
        $user_id = Auth::id();

        // messages
        $data = ['user_id' => $user_id, 'type' => 'message'];
        $counter = User_counter::firstOrCreate($data);
        $counter->update(['count' => ApiMessagesController::getUnreadMessageCount($user_id)]);

        $data = ['user_id' => $user_id, 'type' => 'votes'];
        $counter = User_counter::firstOrCreate($data);
        $counter->update(['count' => ApiScoutingController::getNewVotesCount($user_id)]);
    }

    public function getCounters() {
        $this->getLoginUser();
        $result = [];
        $counters = User_counter::where('user_id', $this->user->id)->get();
        foreach($counters as $counter) {
            $types = [
                'message' => 'newMessages',
                'votes' => 'newVotes',
            ];
            $result[$types[$counter->type]] = $counter->count;
        }
        return $result;
    }

    public function getNotifications() {
        $c = new ApiNotificationsController();
        $notifications = $c->getNotifications();
        return $notifications;
    }

    public function json_response($data) {
        $this->getLoginUser();
        $data['appOptions'] = $this->getOptions();
        $data['appCounters'] = $this->getCounters();
        $data['appNotifications'] = $this->getNotifications();
        return response()->json($data);
    }

    public function backgroundGet()
    {
        ApiController::refreshCounters();
        return $this->json_response([]);
    }

    public function test(Request $request) {
        $data = [
            'testData' => 'test',
            //'serverName' => $_SERVER['SERVER_NAME']
        ];
        return response()->json($data);
    }

    public static function notifyTable($type = '') {
        $p_hight = 3;
        $p_mediu = 2;
        $p_lowww = 1;
        $notifications = [
            'no_type'               => ['chat' => 1, 'notify' => 1, 'priority' => $p_lowww, 'hidden' => 0, 'color' => '',     'field1' => $type],

            'message'               => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 1, 'color' => 'indigo', 'field1' => ''],
            'confirmed-vote'        => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 0, 'color' => 'indigo', 'field1' => ''],
    /**/    'search-shared'         => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 1, 'color' => 'indigo', 'field1' => ''],
    /**/    'send-shared'           => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 1, 'color' => 'indigo', 'field1' => ''],
            //
            'option-confirmed'      => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 0, 'color' => 'red',  'field1' => 'Option'],
            'option-canceled'       => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 0, 'color' => 'red',  'field1' => 'Option'],
            'option-add-to-another' => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 0, 'color' => 'red',  'field1' => 'Option'],
            'option-shared'         => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 0, 'color' => 'red',  'field1' => 'Option'],
            'option-created'        => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 0, 'color' => 'red',  'field1' => 'Option'],
            'option-edited'         => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 0, 'color' => 'red',  'field1' => 'Option'],
            'option-deleted'        => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 0, 'color' => 'red',  'field1' => 'Option'],
            //
            'event-canceled'        => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 0, 'color' => 'blue', 'field1' => 'Event'],
            'event-add-to-another'  => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 0, 'color' => 'blue', 'field1' => 'Event'],
            'event-shared'          => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 1, 'color' => 'blue', 'field1' => 'Event'],
            'event-edited'          => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 0, 'color' => 'blue', 'field1' => 'Event'],
            'event-created'         => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 0, 'color' => 'blue', 'field1' => 'Event'],
            'event-deleted'         => ['chat' => 1, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 0, 'color' => 'blue', 'field1' => 'Event'],

            'create-vote'           => ['chat' => 0, 'notify' => 1, 'priority' => $p_hight, 'hidden' => 1, 'color' => 'indigo', 'field1' => ''],
            'user-voted'            => ['chat' => 0, 'notify' => 1, 'priority' => $p_mediu, 'hidden' => 1, 'color' => 'indigo', 'field1' => ''],
        ];
        if ($type) {
            return $notifications[$type] ?? $notifications['no_type'];
        }
        return $notifications[$type];
    }

    public static function notifyText($type = '') {
        $notifications = [
            'no_type'               => ['', ''],

            'message'               => ['', ''],
            'confirmed-vote'        => ['Hello dear, We would like to confirm ', ' to our agency!'],
    /**/    'search-shared'         => [' shared model with you', ''],
    /**/    'send-shared'           => [' shared model with you', ''],
            //
            'option-confirmed'      => [' confirmed option', ''],
            'option-canceled'       => [' canceled option', ''],
            'option-add-to-another' => [' added option to the calendar of the model ', ''],
            'option-shared'         => [' shared option with you', ''],
            'option-created'        => [' created option', ''],
            'option-edited'         => [' changed option', ''],
            'option-deleted'        => [' deleted option', ''],
            //
            'event-canceled'        => [' canceled event', ''],
            'event-add-to-another'  => [' added event to the calendar of the model ', ''],
            'event-shared'          => [' shared event with you', ''],
            'event-created'         => [' created event', ''],
            'event-edited'          => [' changed event', ''],
            'event-deleted'         => [' deleted event', ''],

            'create-vote'           => [' created voting', ''],
            'user-voted'            => [' voted', ''],

        ];
        if ($type) {
            return $notifications[$type] ?? $notifications['no_type'];
        }
        return $notifications[$type];
    }
}
