<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AvisClientNotification extends Notification
{
    use Queueable;

    public $partenaire;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($partenaire)
    {
        $this->partenaire = $partenaire;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Bonjour, '.$this->partenaire->name)
                    ->line('veuillez laissez votre avis sur le client svp.')
                    ->line('Bien Cordialement')
                    ->action('laissez votre avis ici!', url('http://localhost:8088/ '))
                    // in this url put the url of the frontend in which you have the page for ( Avis sur un client ) 
                    // example : http://127.0.0.1:3000/avis/client 
                    // the default port of react is 3000 !!
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
