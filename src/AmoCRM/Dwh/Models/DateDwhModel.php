<?php

namespace AmoCRM\Dwh\Models;

class DateDwhModel extends BaseDwhModel
{
    protected ?int $id = null;
    protected int $accountId;
    protected string $Date;
    protected int $Year;
    protected int $Quarter;
    protected int $Month;
    protected int $Week;
    protected int $Day;
    protected int $DayOfWeek;
    protected int $IsWeekend;
    protected int $IsHoliday;

    public static function getTableName(): string { return 'general_dates'; }

    public function toArray(): array
    {
        return [
            'id' => \$this->id,
            'account_id' => \$this->accountId,
            'date' => \$this->Date,
            'year' => \$this->Year,
            'quarter' => \$this->Quarter,
            'month' => \$this->Month,
            'week' => \$this->Week,
            'day' => \$this->Day,
            'day_of_week' => \$this->DayOfWeek,
            'is_weekend' => \$this->IsWeekend,
            'is_holiday' => \$this->IsHoliday,
        ];
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }
    public function getAccountId(): int { return $this->accountId; }
    public function setAccountId(int $v): self { $this->accountId = $v; return $this; }
    public function getDate(): string { return $this->Date; }
    public function setDate(string $v): self { $this->Date = $v; return $this; }
    public function getYear(): int { return $this->Year; }
    public function setYear(int $v): self { $this->Year = $v; return $this; }
    public function getQuarter(): int { return $this->Quarter; }
    public function setQuarter(int $v): self { $this->Quarter = $v; return $this; }
    public function getMonth(): int { return $this->Month; }
    public function setMonth(int $v): self { $this->Month = $v; return $this; }
    public function getWeek(): int { return $this->Week; }
    public function setWeek(int $v): self { $this->Week = $v; return $this; }
    public function getDay(): int { return $this->Day; }
    public function setDay(int $v): self { $this->Day = $v; return $this; }
    public function getDayOfWeek(): int { return $this->DayOfWeek; }
    public function setDayOfWeek(int $v): self { $this->DayOfWeek = $v; return $this; }
    public function getIsWeekend(): int { return $this->IsWeekend; }
    public function setIsWeekend(int $v): self { $this->IsWeekend = $v; return $this; }
    public function getIsHoliday(): int { return $this->IsHoliday; }
    public function setIsHoliday(int $v): self { $this->IsHoliday = $v; return $this; }
}
