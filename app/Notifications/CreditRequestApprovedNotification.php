<?php
namespace App\Notifications;
use App\Models\{CreditRequest, Loan};
use Illuminate\Notifications\{Notification, Messages\MailMessage};

class CreditRequestApprovedNotification extends Notification
{
    public function __construct(
        private CreditRequest $req,
        private Loan          $loan,
    ) {}

    public function via(object $notifiable): array
    { return ['mail', 'database']; }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Credit Request Has Been Approved ✅')
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line('Great news! Your credit request has been approved.')
            ->line('Reference: ' . $this->req->reference_code)
            ->line('Amount Approved: KES ' . number_format($this->req->amount_approved, 2))
            ->line('Loan Reference: ' . $this->loan->reference_code)
            ->action('View Loan Details', url('/dashboard/loans/' . $this->loan->id))
            ->line('Please log in to view your repayment schedule.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'             => 'credit_approved',
            'message'          => 'Your credit request ' . $this->req->reference_code . ' was approved.',
            'credit_request_id'=> $this->req->id,
            'loan_id'          => $this->loan->id,
            'amount'           => $this->req->amount_approved,
        ];
    }
}
