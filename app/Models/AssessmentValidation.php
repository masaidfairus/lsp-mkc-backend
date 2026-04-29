<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentValidation extends Model
{
    protected $fillable = [
        'registration_id',
        'document_type',
        'main_data',
        'validation_data',
    ];

    protected $casts = [
        'main_data' => 'array',
        'validation_data' => 'array',
    ];

    public function registration()
    {
        return $this->belongsTo(AssessmentRegistration::class, 'registration_id');
    }

    public function scheme()
    {
        // Many AssessmentValidations might not have a direct scheme_id, 
        // so we access it through registration
        return $this->hasOneThrough(
            Scheme::class,
            AssessmentRegistration::class,
            'id', // Foreign key on AssessmentRegistration table
            'id', // Foreign key on Scheme table
            'registration_id', // Local key on AssessmentValidation table
            'scheme_id' // Local key on AssessmentRegistration table
        );
    }

    public function mainAssessor()
    {
        return $this->hasOneThrough(
            User::class,
            AssessmentRegistration::class,
            'id',
            'id',
            'registration_id',
            'assessor_id'
        );
    }

    public function peerAssessor()
    {
        // Peer assessor might be stored in validation_data.header.peer_assessor_id
        // or just assume it's a specific user for now. 
        // Looking at the Resource, it tries to access mainAssessor and peerAssessor
        // I'll add a placeholder or check where it's stored.
        return $this->belongsTo(User::class, 'peer_assessor_id'); // Placeholder if we add this column later
    }
}
