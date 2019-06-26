<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Webcosmonauts\Alder\Models\Notification;

class NotificationController extends BaseController
{
    /**
     * Get last N notifications
     *
     * @param int $amount
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLast(int $amount = 5) {
        return Notification::latest()->take($amount)->get();
    }
}
