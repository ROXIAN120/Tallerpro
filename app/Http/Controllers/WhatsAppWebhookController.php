<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Guardamos todo el payload en los logs para depurar
        Log::info('Webhook de WhatsApp recibido:', $request->all());

        $event = $request->input('event');
        $data = $request->input('data');

        if ($event === 'messages.upsert' && !empty($data)) {
            $msg = $data;
            
            // Ignoramos mensajes del propio sistema (enviados por el bot)
            if (isset($msg['key']['fromMe']) && $msg['key']['fromMe'] === true) {
                return response()->json(['status' => 'ignored fromMe']);
            }

            // Limpiamos el número de teléfono (le quitamos el @s.whatsapp.net)
            $remoteJid = $msg['key']['remoteJid'] ?? '';
            if (strpos($remoteJid, '@g.us') !== false) {
                // Es un grupo, por ahora los ignoramos
                return response()->json(['status' => 'ignored group']);
            }
            
            $phoneNumber = explode('@', $remoteJid)[0];
            $contactName = $msg['pushName'] ?? null;
            $messageId = $msg['key']['id'] ?? uniqid();

            // Extraemos el texto del mensaje
            $body = $msg['message']['conversation'] 
                    ?? $msg['message']['extendedTextMessage']['text'] 
                    ?? '[Adjunto/Audio/No soportado]';

            // 1. Buscamos la conversación (si no existe, la creamos)
            $conversation = Conversation::firstOrCreate(
                ['phone_number' => $phoneNumber],
                ['contact_name' => $contactName, 'status' => 'open']
            );

            // Actualizamos la fecha de última actividad y aseguramos que esté "abierta"
            $conversation->update([
                'last_message_at' => now(), 
                'status' => 'open'
            ]);

            // 2. Guardamos el mensaje en la base de datos (evitando duplicados)
            $existingMessage = Message::where('message_id', $messageId)->first();
            
            if (!$existingMessage) {
                $conversation->messages()->create([
                    'message_id' => $messageId,
                    'body' => $body,
                    'type' => 'text', 
                    'direction' => 'inbound',
                    'status' => 'delivered'
                ]);

                // 3. Avisar a n8n para que el bot responda
                try {
                    \Illuminate\Support\Facades\Http::timeout(3)->post('http://127.0.0.1:5678/webhook/tallerpro-bot', [
                        'phone_number' => $phoneNumber,
                        'message' => $body,
                        'contact_name' => $contactName
                    ]);
                } catch (\Exception $e) {
                    Log::error("Error avisando a n8n: " . $e->getMessage());
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
