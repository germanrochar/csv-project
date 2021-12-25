<?php declare(strict_types = 1);

namespace App;

class Mappings
{
    /**
    * @var array
    */
    private $data;

    public function __construct(array $keys, array $values)
    {
        if (count($keys) !== count($values)) {
            info('Mapping arrays must be of the same length', ['keys' => $keys, 'values' => $values]);
            throw new \LogicException('Arrays must be of the same length');
        }

        foreach ($keys as $index => $key) {
            $this->data[$key] = $values[$index];
        }
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
}
