<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ["name"];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
    
    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class);
    }

    public function name(): Attribute{
        return Attribute::make(
            get: fn(string $value) => ucwords(str_replace('-', ' ', $value)),
            set: fn(string $value) => str_replace(' ', '-',strtolower($value))
        );
    }
}
