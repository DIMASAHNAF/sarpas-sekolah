<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'jurusan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Cek apakah user memiliki peran admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user memiliki peran kaprodi.
     */
    public function isKaprodi(): bool
    {
        return $this->role === 'kaprodi';
    }

    /**
     * Cek apakah user memiliki peran staff/user biasa atau kaprodi.
     */
    public function isUser(): bool
    {
        return $this->role === 'user' || $this->role === 'kaprodi' || $this->role === 'staff';
    }

    /**
     * Label representasi peran dan jurusan user.
     */
    public function getRoleLabelAttribute(): string
    {
        if ($this->isAdmin()) {
            return 'Administrator Sarpras';
        }

        if ($this->role === 'kaprodi') {
            return 'Kaprodi ' . ($this->jurusan ?: '');
        }

        if ($this->jurusan) {
            return 'Staf / Unit ' . $this->jurusan;
        }

        return 'Staf Pengguna';
    }

    /**
     * Cek apakah pengguna berwenang mengakses data suatu jurusan.
     */
    public function canAccessJurusan(?string $jurusan): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (!$this->jurusan || !$jurusan) {
            return false;
        }

        $aliases = PengajuanBarang::getAliases($this->jurusan);
        foreach ($aliases as $alias) {
            if (strcasecmp($alias, $jurusan) === 0 || stripos($jurusan, $alias) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Relasi ke pengajuan barang yang dibuat oleh pengguna.
     */
    public function pengajuanBarangs()
    {
        return $this->hasMany(PengajuanBarang::class);
    }
}
