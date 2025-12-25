<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Van extends Model
{
    use HasFactory;

    public const CAMPUS_LABELS = [
        'huntra' => 'ศูนย์พระนครศรีอยุธยา หันตรา',
        'wasukri' => 'ศูนย์พระนครศรีอยุธยา วาสุกรี',
        'nonthaburi' => 'ศูนย์นนทบุรี',
        'suphanburi' => 'ศูนย์สุพรรณบุรี',
    ];

    public const DEPARTMENT_LABELS = [
        'gad' => 'กองกลาง',
        'subnon' => 'กองบริหารทรัพยากรนนทบุรี',
        'subwa' => 'กองกลาง งานบริการ ศูนย์พระนครศรีอยุธยา วาสุกรี',
        'subsu' => 'กองบริหารทรัพยากรสุพรรณบุรี',
    ];

    protected $fillable = [
        'name',
        'license_plate',
        'campus',
        'owner_department',
        'capacity',
        'status',
        'description',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getAvailableSeats($date)
    {
        $bookedSeats = $this->bookings()
            ->where('start_date', $date)
            ->where('status', 'approved')
            ->sum('seats_requested');
        
        return $this->capacity - $bookedSeats;
    }

    public function isAvailable($date)
    {
        return $this->status === 'active' && $this->getAvailableSeats($date) > 0;
    }
}
