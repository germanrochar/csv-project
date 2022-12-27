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

        var_dump(Carbon::now()->toDateTimeString());
        // NOW
        // UTC +6: 2022-12-27 08:02:40
        // UTC:    2022-12-27 02:02:40
        // UTC -6: 2022-12-26 20:02:4
        //
        // FILTER for Dec 26th
        // UTC: 2022-12-26 06:00:00 - 2022-12-27 05:59:59
        // UTC: 2022-12-25 18:00:00 - 2022-12-26 17:59:59

        // Take the timezone and calculate the offset
        var_dump('offset');
        var_dump(Carbon::createFromTimestamp(0, $tz)->offsetHours);
        $offsetHours = Carbon::createFromTimestamp(0, $tz)->offsetHours;

        $startOfDay = Carbon::now()->startOfDay()->addHours($offsetHours);
        $endOfDay = Carbon::now()->endOfDay()->addHours($offsetHours);

        var_dump('start_of_day', $startOfDay->toDateTimeString());
        var_dump('start_of_day_test', Carbon::now()->tz($tz)->startOfDay()->utc()->toDateTimeString());
        var_dump('end_of_day', $endOfDay->toDateTimeString());
        var_dump('end_of_day_test', Carbon::now()->tz($tz)->endOfDay()->utc()->toDateTimeString());

        return $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
    }
}
