<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\TelegramUser;

class TelegramUserPage extends Component
{
    public function render()
    {
        return view('livewire.telegram-user-page');
    }

    private function getTelegramUpdates() {
        $bot_key = config('telegram.bot_key');
        $apiUrl = "https://api.telegram.org/bot$bot_key/getUpdates";
    
        // Make a GET request to the Telegram API
        $response = file_get_contents($apiUrl);
    
        if ($response === false) {
            // Handle error
            return false;
        }
    
        // Decode the JSON response
        $data = json_decode($response, true);
    
        if ($data === null || !isset($data['result'])) {
            // Handle error
            return false;
        }
    
        // Return the updates
        return $data['result'];
    }

    public function update_user() {
        // Get Telegram updates
        $updates = $this->getTelegramUpdates();
        
        if ($updates !== false) {
            foreach ($updates as $update) {
                $userId = $update['message']['from']['id'];
                $isBot = $update['message']['from']['is_bot'];
                $firstName = $update['message']['from']['first_name'];
                $username = $update['message']['from']['username'];
                $messages = $update['message']['text'];

                if ($isBot == false) {
                    
                    $telegramUser = TelegramUser::find($userId);

                    if ($messages == "/stop") {
                        if ($telegramUser) {
                            $telegramUser->status = 'inactive';
                            $telegramUser->save();
                        }
                    }

                    if ($messages == '/subs' || $messages == '/subsribe') {
                        if ($telegramUser) {
                            $telegramUser->status = 'active';
                            $telegramUser->save();
                        } else {
                            $newTelegramUser = new TelegramUser;
                            $newTelegramUser->id = $userId;
                            $newTelegramUser->username = $username;
                            $newTelegramUser->first_name = $firstName;
                            $newTelegramUser->is_bot = $isBot;
                            $newTelegramUser->status = 'active';
                            $newTelegramUser->save();
                        }
                    }
                }
            }

            $this->emitTo('telegram-user-table', 'reloadTable');

            $msg = ['success' => 'data user berhasil diupdate'];
        } else {
            $msg = ['error' => 'gagal melakukan update'];
        }
        
        $this->dispatchBrowserEvent('display-message', $msg);
    }
    
    



}
