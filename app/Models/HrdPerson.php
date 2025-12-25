<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrdPerson extends Model
{
    /**
     * The connection name for the model.
     */
    protected $connection = 'hrd';

    /**
     * The table associated with the model.
     */
    protected $table = 'arit_vw_his_person';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id_card';

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id_card',
        'title_name_th',
        'fname_th',
        'lname_th',
        'rate_id',
        'pos_name_th',
        'unit_name_th',
        'faculty_name_th',
        'position_name',
        'statuslist_name',
        'campus_id',
        'campus_name_th'
    ];

    /**
     * Get full name in Thai
     */
    public function getFullNameThAttribute(): string
    {
        return "{$this->title_name_th}{$this->fname_th} {$this->lname_th}";
    }

    /**
     * Find person by ID card
     */
    public static function findByIdCard(?string $idcard)
    {
        if (!$idcard) {
            return null;
        }
        
        return static::where('id_card', $idcard)->first();
    }
}
