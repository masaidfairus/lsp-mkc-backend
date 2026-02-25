<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeerValidation extends Model
{
    protected $fillable = [
        'scheme_id',
        'main_assessor_id',
        'peer_assessor_id',
        'status',
        'notes',
        'validated_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    public function mainAssessor()
    {
        return $this->belongsTo(User::class, 'main_assessor_id', 'id');
    }

    public function peerAssessor()
    {
        return $this->belongsTo(User::class, 'peer_assessor_id', 'id');
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }
}
