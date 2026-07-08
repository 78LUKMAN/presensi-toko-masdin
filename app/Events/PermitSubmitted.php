<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermitSubmitted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $employeeName;
    public $permitType;
    public $dateRange;

    public function __construct($employeeName, $permitType, $dateRange)
    {
        $this->employeeName = $employeeName;
        $this->permitType = $permitType;
        $this->dateRange = $dateRange;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('admin-notifications'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'permit.submitted';
    }
}
