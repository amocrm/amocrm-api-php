<?php

namespace AmoCRM\Models\AccountSettings;

use AmoCRM\Contracts\Support\Arrayable;

class DateTimeSettings implements Arrayable
{
    /**
     * @var string
     */
    protected $datePattern;

    /**
     * @var string
     */
    protected $shortDatePattern;

    /**
     * @var string
     */
    protected $shortTimePattern;

    /**
     * @var string
     */
    protected $dateFormat;

    /**
     * @var string
     */
    protected $timeFormat;

    /**
     * @var string
     */
    protected $timezone;

    /**
     * @var string
     */
    protected $timezoneOffset;

    public function __construct(
        string $datePattern,
        string $shortDatePattern,
        string $shortTimePattern,
        string $dateFormat,
        string $timeFormat,
        string $timezone,
        string $timezoneOffset
    ) {
        $this->datePattern = $datePattern;
        $this->shortDatePattern = $shortDatePattern;
        $this->shortTimePattern = $shortTimePattern;
        $this->dateFormat = $dateFormat;
        $this->timeFormat = $timeFormat;
        $this->timezone = $timezone;
        $this->timezoneOffset = $timezoneOffset;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'date_pattern' => $this->datePattern,
            'short_date_pattern' => $this->shortDatePattern,
            'short_time_pattern' => $this->shortTimePattern,
            'date_format' => $this->dateFormat,
            'time_format' => $this->timeFormat,
            'timezone' => $this->timezone,
            'timezone_offset' => $this->timezoneOffset,
        ];
    }

    /**
     * @return string
     */
    public function getDatePattern(): string
    {
        return $this->datePattern;
    }

    /**
     * @return string
     */
    public function getShortDatePattern(): string
    {
        return $this->shortDatePattern;
    }

    /**
     * @return string
     */
    public function getShortTimePattern(): string
    {
        return $this->shortTimePattern;
    }

    /**
     * @return string
     */
    public function getDateFormat(): string
    {
        return $this->dateFormat;
    }

    /**
     * @return string
     */
    public function getTimeFormat(): string
    {
        return $this->timeFormat;
    }

    /**
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * @return string
     */
    public function getTimezoneOffset(): string
    {
        return $this->timezoneOffset;
    }
}
