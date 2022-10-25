<?php declare(strict_types = 1);

namespace App;

use App\Models\Contact;
use Illuminate\Support\Str;
use LogicException;

/**
 * This is a wrapper class for mappings used to import contacts from csv files
 */
class Mappings
{
    private array $data;
    private array $customMappingKeys;

    public function __construct(array $keys, array $values)
    {
        if (count($keys) !== count($values)) {
            info('Mapping arrays must be of the same length', ['keys' => $keys, 'values' => $values]);
            throw new LogicException('Arrays must be of the same length');
        }

        $this->data = [];
        foreach ($keys as $index => $key) {
            $this->data[$key] = Str::snake($values[$index]);
        }

        $this->customMappingKeys = array_values(array_diff(array_keys($this->data), Contact::getColumnsAllowedToImport()));
    }

    /**
     * Retrieves a mapping value given a key
     *
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string
    {
        if (!isset($this->data[$key]))
            return null;

        return $this->data[$key];
    }

    /**
     * Checks if mapping key exists
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Retrieve all mappings as array
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->data;
    }

    public function getCustomMappings(): array
    {
        return array_filter($this->data, fn ($mapping) => in_array($mapping, $this->customMappingKeys), ARRAY_FILTER_USE_KEY);
    }
}
