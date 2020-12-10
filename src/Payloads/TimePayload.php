<?php

namespace Spatie\Ray\Payloads;

use Symfony\Component\Stopwatch\StopwatchEvent;

class TimePayload extends Payload
{
    protected string $name;
    protected bool $isNewTimer = false;

    /** @var float|int */
    protected $totalTime = 0;
    protected int $maxMemoryUsageDuringTotalTime = 0;

    /** @var float|int */
    protected $timeSinceLastCall = 0;
    protected int $maxMemoryUsageSinceLastCall = 0;

    public function __construct(string $name, StopwatchEvent $stopwatchEvent)
    {
        $this->name = $name;

        $this->totalTime = $stopwatchEvent->getDuration();
        $this->maxMemoryUsageDuringTotalTime = $stopwatchEvent->getMemory();

        $periods = $stopwatchEvent->getPeriods();

        if ($lastPeriod = end($periods)) {
            $this->timeSinceLastCall =  $lastPeriod->getDuration() ;
            $this->maxMemoryUsageDuringTotalTime = $lastPeriod->getMemory();
        }
    }

    public function concernsNewTimer(): self
    {
        $this->isNewTimer = true;

        return $this;
    }

    public function getType(): string
    {
        return 'time';
    }

    public function getContent(): array
    {
        return [
            'name' => $this->name,
            'is_new_timer' => $this->isNewTimer,
            'total_time' => $this->totalTime,
            'max_memory_usage_during_total_time' => $this->maxMemoryUsageDuringTotalTime,
            'time_since_last_call' => $this->timeSinceLastCall,
            'max_memory_usage_since_last_call' => $this->maxMemoryUsageSinceLastCall,
        ];
    }
}