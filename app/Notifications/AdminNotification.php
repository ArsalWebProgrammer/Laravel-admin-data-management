<?php
namespace App\Notifications;

use App\Models\Order;  // Make sure you're using the Order model
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
    use Queueable;

    private $message;
    private $order;

    // Constructor to accept the message and the order object
    public function __construct($message, Order $order)
    {
        $this->message = $message;
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Use the database channel for storing notifications
    }

    /**
     * Store the notification data in the database.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message, // Notification message
            'order_id' => $this->order->id,  // Store the order's ID from the 'allorders' table
            'company_name' => $this->order->company_name,  // Add relevant order data
            'location_from' => $this->order->from_location,
            'location_where' => $this->order->destination,
            'total_rate' => $this->order->total_rate,
            'payment' => $this->order->payment_statu,
            'amount_paid' => $this->order->amount_paid,
            'amount_remaining' => $this->order->amount_remaining,
            'driver' => $this->order->driver,
            'driver_trip' => $this->order->driver_trip,
        ];
    }
}