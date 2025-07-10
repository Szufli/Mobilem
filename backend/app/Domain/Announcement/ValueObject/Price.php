<?php

namespace App\Domain\Announcement\ValueObject;

final class Price
{
    private float $amount;

    public function __construct(float $amount)
    {
        if (!is_numeric($amount)) {
            throw new \InvalidArgumentException('Cena musi być liczbą.');
        }

        if ($amount < 0) {
            throw new \InvalidArgumentException('Cena nie może być ujemna.');
        }
        if ($amount < 0.01) {
            throw new \InvalidArgumentException('Cena nie może być mniejsza niż 0.01.');
        }
        if ($amount >= 1e12) {
            throw new \InvalidArgumentException('Cena nie może przekraczać 9999999999.99.');
        }

        // Zaokrąglamy do 2 miejsc po przecinku
        $this->amount = round($amount, 2);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function __toString(): string
    {
        return number_format($this->amount, 2, '.', '');
    }
}
