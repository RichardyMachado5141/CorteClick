<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'specialty', 'description', 'phone', 'start_time', 'end_time', 'available_days'])]
class Professional extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the user that owns this professional.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the services offered by this professional.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Get the appointments for this professional.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get available time slots for a specific date.
     */
    public function getAvailableSlots($date, $duration = 30)
    {
        // Logic to get available time slots
        $dayOfWeek = strtolower(date('l', strtotime($date)));
        
        if (!in_array($dayOfWeek, $this->available_days ?? [])) {
            return [];
        }

        $startTime = strtotime($this->start_time);
        $endTime = strtotime($this->end_time);
        $slots = [];

        // Get booked appointments for this date
        $booked = $this->appointments()
            ->whereDate('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('appointment_date')
            ->map(fn($date) => strtotime($date))
            ->toArray();

        for ($time = $startTime; $time < $endTime; $time += ($duration * 60)) {
            $slotEnd = $time + ($duration * 60);
            
            // Check if slot is available
            $isAvailable = true;
            foreach ($booked as $bookedTime) {
                if ($time <= $bookedTime && $bookedTime < $slotEnd) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $slots[] = date('H:i', $time);
            }
        }

        return $slots;
    }
}
