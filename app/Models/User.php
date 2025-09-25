<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;

/**
 * Class User
 *
 * Model untuk tabel users.
 *
 * Kolom penting:
 * - id
 * - name
 * - email
 * - role_id
 * - status
 * - photo
 * - password
 * - email_verified_at
 * - created_by
 * - updated_by
 *
 * Relasi:
 * - role() : User belongsTo Role
 * - loginAttempts() : User hasMany LoginAttempt
 * - personalAccessTokens() : morphMany Sanctum tokens
 * - profile() : hasOne UserProfile
 * - creator() : siapa yang membuat
 * - updater() : siapa yang mengupdate
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Kolom yang bisa diisi mass-assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role_id',
        'status',
        'photo',
        'password',
        'created_by',
        'updated_by',
        'email_verified_at',
    ];

    /**
     * Kolom yang harus disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe casting atribut.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Booted untuk otomatis mengisi created_by dan updated_by.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user(); // pakai Facade
            if ($user) {
                $model->created_by = $user->id;
                $model->updated_by = $user->id;
            }
        });

        static::updating(function ($model) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user(); // pakai Facade
            if ($user) {
                $model->updated_by = $user->id;
            }
        });
    }

    /**
     * Relasi ke Role (setiap user punya 1 role).
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Relasi ke LoginAttempt (satu user punya banyak attempt).
     */
    public function loginAttempts()
    {
        return $this->hasMany(LoginAttempt::class, 'user_id', 'id');
    }

    /**
     * Relasi ke personal access tokens (Sanctum).
     */
    public function personalAccessTokens()
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }

    /**
     * Relasi ke UserProfile (opsional).
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    /**
     * Relasi ke user yang membuat record ini.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke user yang terakhir mengupdate record ini.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Mengecek apakah user memiliki permission tertentu.
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission(string $permissionName): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role
            ->permissions()
            ->where('name', $permissionName)
            ->exists();
    }

    /**
     * Mengecek apakah user memiliki salah satu dari beberapa permission.
     *
     * @param array<string> $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role
            ->permissions()
            ->whereIn('name', $permissions)
            ->exists();
    }

    /**
     * Mengecek apakah user memiliki semua permission yang diminta.
     *
     * @param array<string> $permissions
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool
    {
        if (!$this->role) {
            return false;
        }

        $count = $this->role
            ->permissions()
            ->whereIn('name', $permissions)
            ->count();

        return $count === count($permissions);
    }

    /**
     * Shortcut: ambil semua nama permission user ini.
     *
     * @return array<int, string>
     */
    public function getPermissionNames(): array
    {
        if (!$this->role) {
            return [];
        }

        return $this->role->permissions->pluck('name')->toArray();
    }
}
