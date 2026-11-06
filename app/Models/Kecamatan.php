<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatans';

    protected $fillable = ['nama'];

    public function sekolahs(): HasMany
    {
        return $this->hasMany(Sekolah::class, 'kecamatan_id');
    }

    public function layananKepegawaian(): HasMany
    {
        return $this->hasMany(LayananKepegawaian::class, 'kecamatan_id');
    }
}
