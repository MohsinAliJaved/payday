<?php


namespace App\Helpers\Traits;


use App\Models\Core\Setting\NotificationEvent;

trait AppCustomEmailHelper
{
    public function getOneTemplate($name)
    {
        return NotificationEvent::with('templates')
            ->where('name', $name)
            ->first();
    }

    public function getTemplate($notificationEvent)
    {
        if (count(optional($notificationEvent)->templates) > 0) {
            return optional($notificationEvent)->templates[0]->custom_content
                ? optional($notificationEvent)->templates[0]->custom_content
                : optional($notificationEvent)->templates[0]->default_content;
        }
        return '';
    }

}