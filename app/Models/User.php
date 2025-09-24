<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Fortify\TwoFactorAuthenticatable;
use PragmaRX\Google2FA\Google2FA;
use App\Models\Role;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'phone_number',
        'profile',
        'lang',
        'subscription',
        'subscription_expire_date',
        'parent_id',
        'is_active',
        'two_factor_code',
        'two_factor_expires_at',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_expires_at' => 'datetime',
    ];

    public function verifyTwoFactorAuth($code)
    {
        $google2fa = new Google2FA();

        return $google2fa->verifyKey($this->two_factor_secret, $code);
    }

    public function totalUser()
    {
        return User::whereNotIn('type',['customer','agent'])->where('parent_id', $this->id)->count();
    }
    public function totalCustomer()
    {
        return User::where('type','customer')->where('parent_id', $this->id)->count();
    }
    public function totalAgent()
    {
        return User::where('type','agent')->where('parent_id', $this->id)->count();
    }

    public function totalContact()
    {
        return Contact::where('parent_id', '=', parentId())->count();
    }

    public function roleWiseUserCount($role)
    {
        return User::where('type', $role)->where('parent_id',parentId())->count();
    }
    public static function getDevice($user)
    {
        $mobileType = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
        $tabletType = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';
        if(preg_match_all($mobileType, $user))
        {
            return 'mobile';
        }
        else
        {
            if(preg_match_all($tabletType, $user)) {
                return 'tablet';
            } else {
                return 'desktop';
            }

        }
    }



    public function subscriptions()
    {
        return $this->hasOne('App\Models\Subscription','id','subscription');
    }



    public static $systemModules=[
        'user',
        'customer',
        'agent',
        'policy',
        'insurance',
        'insured detail',
        'nominee',
        'claim',
        'document',
        'payment',
        'tax',
        'contact',
        'note',
        'logged history',
        'settings',
    ];

    public function customer()
    {
        return $this->hasOne('App\Models\Customer','user_id','id');
    }
    public function agent()
    {
        return $this->hasOne('App\Models\Agent','user_id','id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
