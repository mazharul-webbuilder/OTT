<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\LogOptions;

class Admin extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles, Loggable;

    protected $guard = 'admin';
    protected $fillable = [
        'name', 'email', 'password', 'verification_code', 'verification_code_created_at', 'verify_attempt_left'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * this name will be used as log name
    */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return 'Admin';
    }
    /**
     * Those column changes will only recoreded into Activity
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['name', 'email']);
    }
    /**
     * Prevent super admin user delete
    */
    public function delete(): ?bool
    {
        // Assume "Super Admin" is the root user-name
        if ($this->name == "Super Admin") {
            return false;
        }
        return parent::delete();
    }
}
