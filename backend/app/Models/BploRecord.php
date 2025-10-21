<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BploRecord extends Model
{
    use HasFactory;

    protected $table = 'bplo_records';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    // ✅ Fields that can be mass-assigned
    protected $fillable = [
        'DATE',
        'TRANSACTION_CODE',
        'FNAME',
        'MNAME',
        'LNAME',
        'EXTNAME',
        'GENDER',
        'STREET',
        'BARANGAY',
        'MUNICIPALITY',
        'PROVINCE',
        'CELLPHONE',
        'CEDULA_NO',          // ✅ Added
        'CEDULA_DATE',        // ✅ Added
        'MCH_NO',
        'FRANCHISE_NO',
        'MAKE',
        'MOTOR_NO',
        'CHASSIS_NO',
        'PLATE',
        'COLOR',
        'LTO_ORIGINAL_RECEIPT',
        'LTO_CERTIFICATE_REGISTRATION',
        'LTO_MV_FILE_NO',
        'ORIGINAL_RECEIPT_PAYMENT',
        'PAYMENT_DATE',
        'AMOUNT',
        'RENEW_FROM',
        'RENEW_TO',
        'MAYORS_PERMIT_NO',
        'LICENSE_NO',
        'LICENSE_VALID_DATE',
        'DRIVER',
        'STATUS',
        'COMMENT'
    ];

    // ✅ Automatically cast date fields as Carbon instances
    protected $casts = [
        'DATE' => 'date',
        'CEDULA_DATE' => 'date',   // ✅ Added
        'PAYMENT_DATE' => 'date',
        'RENEW_FROM' => 'date',
        'RENEW_TO' => 'date',
        'LICENSE_VALID_DATE' => 'date',
    ];

    // ✅ Auto-compute STATUS dynamically
    public function getStatusAttribute($value)
    {
        if (!empty($this->RENEW_TO)) {
            return now()->lte($this->RENEW_TO) ? 'ACTIVE' : 'EXPIRED';
        }
        return $value ?? 'PENDING';
    }
}
