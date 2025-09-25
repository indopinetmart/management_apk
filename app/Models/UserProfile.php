<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'nik_karyawan',
        'nama_lengkap',
        'no_tlp',
        'nik_ktp',
        'foto_ktp',
        'alamat_rumah',
        'lokasi_rumah',
        'province_id',
        'province_name',
        'city_id',
        'city_name',
        'district_id',
        'district_name',
        'village_id',
        'village_name',
        'kodepos',
        'norek',
        'bank',
        'foto',
        'verifikasi_muka',
        'kontak_darurat',
        'accepted_terms',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
