<?php

namespace Webcosmonauts\Alder\Notifications;

use Illuminate\Support\Facades\Auth;
use Webcosmonauts\Alder\Exceptions\UnknownNotificationActionException;
use Webcosmonauts\Alder\Models\Leaf;

class LeafNotification extends BaseNotification
{
    /**
     * Construct a new notification instance with translated title and message
     * and optionally save it to database and push via pusher.
     *
     * @throws UnknownNotificationActionException
     *
     * @param Leaf $leaf
     * @param string $action 'created', 'updated' or 'deleted'
     * @param array $options
     * @param bool $save
     * @param bool $push
     */
    public function __construct(Leaf $leaf, string $action, array $options = [], bool $save = true, bool $push = true)
    {
        $user = $options['user'] ?? Auth::user();
        
        switch ($action) {
            case 'created':
                $alert_type = 'info';
                $icon = 'info-circle';
                $translation_key = 'added_new';
                break;
            case 'updated':
                $alert_type = 'warning';
                $icon = 'exclamation';
                $translation_key = 'updated';
                break;
            case 'deleted':
                $alert_type = 'danger';
                $icon = 'exclamation-triangle';
                $translation_key = 'deleted';
                break;
            default:
                throw new UnknownNotificationActionException();
        }
        
        $title = [];
        $message = [];
        
        foreach (config('translatable.locales') as $locale) {
            $title[$locale] = $options['title'][$locale] ?? __("alder::leaf_types.".$leaf->leaf_type->slug.".$translation_key", [], $locale);
            $message[$locale] = $options['message'][$locale] ?? (
                __("alder::leaf_types.users.singular", [], $locale) . " <a href=\"#\">$user->full_name</a> "
                . lcfirst(__("alder::leaf_types.".$leaf->leaf_type->slug.".user_$translation_key", ['href' => Alder::getLeafUrl($leaf)], $locale))
            );
        }
    
        $this->alert_type  =  $options['alert_type']  ??  $alert_type;
        $this->icon        =  $options['icon']        ??  $icon;
        $this->title       =  $title;
        $this->message     =  $message;
        $this->user_id     =  $options['user']->id    ??  $user->id;
        
        if ($save && $push)
            return $this->saveAndPush();
        else {
            if ($save)
                return $this->save();
            if ($push)
                return $this->push();
        }
        
        return true;
    }
}
