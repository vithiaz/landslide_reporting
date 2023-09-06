<?php

namespace App\Http\Controllers;

use App\Models\Report;
use GuzzleHttp\Client;
use App\Models\SendedReport;
use App\Models\TelegramUser;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\ClientException;

class TelegramController extends Controller
{
    public function send_message(Request $request) {
        $bot_key = config('telegram.bot_key');
        $messages = $request->messages;
        $client = new Client();

        if (!in_array($request->type, [
            'siaga',
            'bahaya',
            'laporan',
        ])) {
            return false;
        }

        $telegram_users = TelegramUser::where([
            ['is_bot', '=', false],
            ['status', '=', 'active'],
        ])->get();
        if ($telegram_users->count() == 0) {
            return false;
        }

        $Report = new Report;
        $Report->messages = $messages;
        $Report->type = $request->type;
        $Report->save();

        $responses = [];
        foreach($telegram_users as $user) {
            try {
                $response = $client->post("https://api.telegram.org/bot$bot_key/sendMessage", [
                    'form_params' => [
                        'chat_id' => $user->id,
                        'text' => $messages,
                    ],
                ]);
                $response_state = 'sended';
            }
            catch(ClientException  $e) {
                $response_state = 'failure';
            }

            $SendedReport = new SendedReport;
            $SendedReport->report = $Report->id;
            $SendedReport->telegram_user = $user->id;
            $SendedReport->status = $response_state;
            $SendedReport->save();
            

            $response_body = [
                'user_id' => $user->id,
                'send_status' => $response_state,
            ];

            array_push($responses, $response_body);
        }
        
        return json_encode($responses);
    }
}
