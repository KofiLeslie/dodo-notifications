<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Dodo Notifications API",
 *     description="API documentation for real-time notifications",
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

class NotificationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/notifications",
     *     summary="Send a notification to a user",
     *     tags={"Notifications"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"to_user_id", "message", "type"},
     *             @OA\Property(property="to_user_id", type="integer", example="1"),
     *             @OA\Property(property="message", type="string", example="Hello, this is a notification"),
     *             @OA\Property(property="type", type="string", example="info")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="token", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'message'    => 'required|string|max:500',
            'type'       => 'nullable|string|max:50',
        ]);

        $notification = Notification::create([
            'user_id'       => $validated['to_user_id'],
            'from_user_id'  => $request->user()->id,
            'message'       => $validated['message'],
            'type'          => $validated['type'] ?? null,
        ]);

        // Broadcast notification
        User::find($validated['to_user_id'])
            ->notify(new UserNotification($notification));

        return response()->json([
            'message'      => 'Notification sent',
            'notification' => $notification,
        ], 201);
    }

    /**
     * @OA\Patch(
     *     path="/api/notifications/{id}/read",
     *     summary="Mark a notification as read",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Notification marked as read"
     *     )
     * )
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update([
            'read_at' => now(),
        ]);

        return response()->json([
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/notifications",
     *      operationId="getNotifications",
     *      tags={"Notifications"},
     *      summary="Get all notifications for user",
     *      security={{"sanctum": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful retrieval of notifications",
     *          @OA\JsonContent(type="array", @OA\Items(
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="message", type="string"),
     *              @OA\Property(property="read_at", type="string", format="date-time", nullable=true)
     *          ))
     *      )
     * )
     */
    public function index(Request $request)
    {
        return auth()->user()
            ->notifications()
            ->whereNull('read_at')
            ->latest()
            ->get();
    }

    /**
     * @OA\Post(
     *     path="/api/notifications/read-all",
     *     summary="Mark all notifications as read",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="All notifications marked as read"
     *     )
     * )
     */
    public function markAllAsRead(Request $request)
    {
        auth()->user()
            ->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }
}
