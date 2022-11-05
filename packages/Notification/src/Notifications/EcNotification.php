<?php

namespace Encoda\Notification\Notifications;

use Encoda\EDocs\Models\Document;
use Encoda\Notification\Channels\CustomDbChannel;
use Encoda\Notification\Enums\EventNotificationMethodEnum;
use Encoda\Notification\Models\EventNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 *
 */
class EcNotification extends Notification
{
    use Queueable;

    /**
     * @param EventNotification $eventNotification
     * @param array             $attachments
     * @param                   $additionalData
     */
    public function __construct(
        protected EventNotification $eventNotification,
        protected array             $attachments,
        public                      $additionalData
    )
    {
    }

    /**
     * @return array
     */
    public function via()
    {
        $vias = [CustomDbChannel::class];
        foreach ($this->eventNotification->methods as $method) {
            switch ($method) {
                case EventNotificationMethodEnum::EMAIL->value:
                    $vias[] = 'mail';
                    break;
            }
        }
        return $vias;
    }

    /**
     * @param $notifiable
     *
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $replacements = $this->getReplacements($notifiable);
        $data = $this->buildData($this->eventNotification->data, $replacements);
        $title = $this->buildData($this->eventNotification->title, $replacements);
        $mailMessage = (new MailMessage)
            ->subject($title)
            ->markdown(
                'notification::mail.notification', [
                    'data' => $data
                ]
            );
        foreach ($this->attachments as $attachment) {
            $mailMessage->attach($attachment);
        }
        return $mailMessage;
    }

    /**
     * @param $notifiable
     *
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $replacements = $this->getReplacements($notifiable);
        $data = $this->buildData($this->eventNotification->data, $replacements);
        $title = $this->buildData($this->eventNotification->title, $replacements);
        return [
            'title'                 => $title,
            'type'                  => $this->eventNotification->type,
            'data'                  => $data,
            'pinned'                => $this->eventNotification->pinned,
            'event_notification_id' => $this->eventNotification->id,
        ];
    }

    /**
     * @param $text
     * @param $replaces
     *
     * @return array|string|string[]|null
     */
    public function buildData($text, $replaces)
    {
        return preg_replace_callback("/(?<!\{)\{\{(?:([^{}]+)|\{([^{}]+)})}}(?!})/", function ($matches) use ($replaces) {
            return $this->parseData($matches, $replaces);
        }, $text);

    }

    /**
     * @param $notifiable
     *
     * @return array
     */
    public function getReplacements($notifiable)
    {
        $additionalData = $this->additionalData;
        $additionalData = (object)$additionalData;
        $replacements = [
            'user'    => $notifiable,
            'owner'   => $this->eventNotification->owner,
            'object'  => $this->additionalData['object'] ?? null,
            'general' => $additionalData,
        ];
        return $replacements;
    }

    /**
     * @param array $data
     * @param       $replaces
     *
     * @return mixed
     */
    public function parseData(array $data, $replaces)
    {
        try {
            $value =  trim($data[1]);
            $dataObject = explode(".", $value);
            extract($replaces, EXTR_SKIP);
            $model = $dataObject[0];
            $property = $dataObject[1] ?? null;
            if (count($dataObject) === 2) {
                $result = $$model->{$property};
            } else {
                $result = $data[0];
            }
            return is_iterable($result) ? $data[0] : $result;
        } catch (\Throwable $e) {
            return $data[0];
        }
    }
}
