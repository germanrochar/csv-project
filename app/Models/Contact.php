<?php declare(strict_types = 1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * @property int id
 * @property int team_id
 * @property string name
 * @property string phone
 * @property string email
 * @property int sticky_phone_number_id
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Contact extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
    * @var array
    */
    private static $columnsNotAllowedForImport = [
        'id',
        'created_at',
        'updated_at'
    ];

    public static function getColumnsAllowedForImport(): array {
        $contactsFields = Schema::getColumnListing('contacts');

        return array_values(
            array_filter($contactsFields, static function ($field) {
                return !in_array($field, self::$columnsNotAllowedForImport, true);
            })
        );
    }

}
