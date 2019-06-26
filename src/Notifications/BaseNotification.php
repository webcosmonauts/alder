<?php

namespace Webcosmonauts\Alder\Notifications;

use Illuminate\Support\Facades\Auth;
use Webcosmonauts\Alder\Models\Notification;

class BaseNotification
{
    protected $type;
    protected $icon;
    protected $alert_type;
    protected $title;
    protected $message;
    protected $user_id;
    
    public function __get(string $name) {
        return $this->$name ?? null;
    }
    
    public function __set(string $name, $value) {
        return $this->$name = $value;
    }
    
    /**
     * Saves new notification to database
     *
     * @return bool
     */
    public function save() {
        $new = new Notification();
        $new->alert_type = $this->alert_type;
        $new->icon = $this->icon;
        $new->user_id = $this->user_id;
        
        foreach (config('translatable.locales') as $locale) {
            $new->translateOrNew($locale)->title = $this->title[$locale];
            $new->translateOrNew($locale)->message = $this->message[$locale];
        }
        
        return $new->save();
    }
    
    public function push() {
        return true;
    }
    
    public function saveAndPush() {
        return $this->save() ? $this->push() : false;
    }
}
