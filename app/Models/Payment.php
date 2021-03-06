<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Payment extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'company_id',
        'farmer_id',
        'purposehead_id',
        'bank_id',
        'user_id',
        'payee_type',
        'payment_amount',
        'payment_type',
        'bank_name',
        'reference',
        'received_by',
        'remarks',
        'payment_date',
        'status'
    ];
    public function bank(){
        return $this->belongsTo(Bank::class);
    }

    public function purposehead(){
        return $this->belongsTo(PurposeHead::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function farmer(){
        return $this->belongsTo(Farmer::class);
    }


    public function user(){
        return $this->belongsTo(User::class);
    }
}
