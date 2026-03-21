<?php
namespace App\Events;
use App\Models\CreditRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreditRequestSubmitted
{
    use Dispatchable, SerializesModels;
    public function __construct(public readonly CreditRequest $creditRequest) {}
}

