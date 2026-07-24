<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /**
     * Notificaciones del usuario autenticado (panel admin), para el poller.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $items = $user->notifications()
            ->latest()
            ->limit(15)
            ->get()
            ->map(fn ($n) => [
                'id'       => $n->id,
                'leida'    => $n->read_at !== null,
                'fecha'    => $n->created_at->diffForHumans(),
                'data'     => $n->data,
            ]);

        return response()->json([
            'no_leidas' => $user->unreadNotifications()->count(),
            'ultima_id' => $items->first()['id'] ?? null,
            'items'     => $items,
        ]);
    }

    /**
     * Marca como leídas: una (si llega id) o todas.
     */
    public function leer(Request $request)
    {
        $user = $request->user();

        if ($request->filled('id')) {
            $user->notifications()->where('id', $request->id)->update(['read_at' => now()]);
        } else {
            $user->unreadNotifications->markAsRead();
        }

        return response()->json(['ok' => true, 'no_leidas' => $user->unreadNotifications()->count()]);
    }
}
