<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'check_in_time',
        'check_in_latitude',
        'check_in_longitude',
        'check_in_photo',
        'check_in_address',
        'check_out_time',
        'check_out_latitude',
        'check_out_longitude',
        'check_out_photo',
        'check_out_address',
        'work_description',
        'work_duration_minutes',
        'status',
        'attendance_date',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'attendance_date' => 'date',
        'check_in_latitude' => 'decimal:8',
        'check_in_longitude' => 'decimal:8',
        'check_out_latitude' => 'decimal:8',
        'check_out_longitude' => 'decimal:8',
        'work_duration_minutes' => 'integer',
    ];

    /**
     * Get the user that owns the attendance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate work duration in minutes
     */
    public function calculateWorkDuration(): ?int
    {
        if ($this->check_in_time && $this->check_out_time) {
            return $this->check_in_time->diffInMinutes($this->check_out_time);
        }
        return null;
    }

    /**
     * Get formatted work duration
     */
    public function getFormattedWorkDuration(): ?string
    {
        if (!$this->work_duration_minutes) {
            return null;
        }

        $hours = floor($this->work_duration_minutes / 60);
        $minutes = $this->work_duration_minutes % 60;

        return sprintf('%d jam %d menit', $hours, $minutes);
    }

    /**
     * Scope to get today's attendance for a user
     */
    public function scopeTodayForUser($query, $userId)
    {
        return $query->where('user_id', $userId)
                     ->whereDate('attendance_date', today());
    }

    /**
     * Scope to get attendance by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }

    /**
     * Scope to get checked in attendances
     */
    public function scopeCheckedIn($query)
    {
        return $query->where('status', 'checked_in');
    }

    /**
     * Scope to get checked out attendances
     */
    public function scopeCheckedOut($query)
    {
        return $query->where('status', 'checked_out');
    }
}
