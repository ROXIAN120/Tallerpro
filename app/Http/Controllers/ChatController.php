<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use App\Models\Conversation;

class ChatController extends Controller
{
    protected $waService;

    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    public function index()
    {
        $conversations = Conversation::with(['messages' => function ($query) {
            $query->latest()->limit(1);
        }])->orderBy('last_message_at', 'desc')->get();

        $contactos = \App\Models\Cliente::all()->map(function($c) {
            return [
                'id' => $c->id,
                'nombre' => $c->nombreCompleto,
                'telefono' => $c->telefono,
                'etiquetas' => $c->etiquetas ?? []
            ];
        });

        return inertia('Chat/Index', [
            'initialConversations' => $conversations,
            'contactos' => $contactos
        ]);
    }

    public function status()
    {
        $state = $this->waService->getConnectionState();
        return response()->json($state);
    }

    public function qr()
    {
        $qrData = $this->waService->getConnectQr();
        return response()->json($qrData);
    }

    public function logout()
    {
        $success = $this->waService->logout();
        return response()->json(['success' => $success]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string'
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);
        $texto = $request->message;

        // 1. Guardar en BD localmente como saliente
        $message = $conversation->messages()->create([
            'message_id' => uniqid('out_'),
            'body' => $texto,
            'type' => 'text',
            'direction' => 'outbound',
            'status' => 'sent'
        ]);

        // Actualizar la última fecha de la conversación
        $conversation->update(['last_message_at' => now()]);

        // 2. Enviar por WhatsApp
        $this->waService->sendMessage($conversation->phone_number, $texto);

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function startChat(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id'
        ]);

        $cliente = \App\Models\Cliente::findOrFail($request->cliente_id);
        $telefono = $cliente->telefono;

        if (!$telefono) {
            return response()->json(['error' => 'El cliente no tiene teléfono.'], 400);
        }

        // Buscar si ya hay conversación
        $conversation = Conversation::where('phone_number', $telefono)->first();

        if (!$conversation) {
            // Crear nueva conversación
            $conversation = Conversation::create([
                'phone_number' => $telefono,
                'contact_name' => $cliente->nombreCompleto,
                'last_message_at' => now(),
            ]);
        }

        // Cargar el último mensaje para la UI
        $conversation->load(['messages' => function ($query) {
            $query->latest()->limit(1);
        }]);

        return response()->json(['conversation' => $conversation]);
    }

    public function updateEtiquetas(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'etiquetas' => 'nullable|array'
        ]);

        $cliente = \App\Models\Cliente::findOrFail($request->cliente_id);
        $cliente->etiquetas = $request->etiquetas ?? [];
        $cliente->save();

        return response()->json(['success' => true, 'etiquetas' => $cliente->etiquetas]);
    }

    public function sendCampaign(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'telefono' => 'required|string'
        ]);

        try {
            $this->waService->sendMessage($request->telefono, $request->message);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error enviando campaña progresiva a {$request->telefono}: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
