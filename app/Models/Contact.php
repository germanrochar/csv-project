<?php declare(strict_types = 1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    /**
     * @return HasMany
     */
    public function customAttributes(): HasMany
    {
        return $this->hasMany(CustomAttribute::class);
    }

    /**
     * Retrieves a list of columns allowed to be imported from a file
     *
     * @return array
    */
    public static function getColumnsAllowedForImport(): array {
        $contactsFields = Schema::getColumnListing('contacts');

        return array_values(
            array_filter($contactsFields, static function ($field) {
                return !in_array($field, self::$columnsNotAllowedForImport, true);
            })
        );
    }


    /**
     * Creates a new custom attribute
     *
     * @param string $key
     * @param string $value
     * @return Model
    */
    public function addCustomAttribute(string $key, string $value): Model
    {
        return $this->customAttributes()->create([
            'key' => $key,
            'value' => $value
        ]);
    }
}
