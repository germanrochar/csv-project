<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $uuid
 * @property string $status
 * @property string|null $error_message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ImportJob extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const STATUS_STARTED = 'started';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    /**
    * @var list<string>
    */
    public const STATUSES = [
        self::STATUS_STARTED,
        self::STATUS_COMPLETED,
        self::STATUS_FAILED
    ];

    /**
     * @param  Builder  $query
     * @param  string  $tz
     * @return Builder
    */
    public function scopeImportedToday(Builder $query, string $tz): Builder
    {
        $startOfDay = Carbon::now()
            ->tz($tz)
            ->startOfDay()
            ->utc()
            ->toDateTimeString()
        ;

        $endOfDay = Carbon::now()
            ->tz($tz)
            ->endOfDay()
            ->utc()
            ->toDateTimeString()
        ;

        return $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
    }
}
