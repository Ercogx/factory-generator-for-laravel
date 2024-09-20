<?php

namespace Ercogx\FactoryGeneratorLaravel;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class FakerMapper
{
    /**
     * @param  class-string  $modelNamespace
     * @return string
     */
    public function handle(string $modelNamespace): string
    {
        if (!class_exists($modelNamespace)) {
            return '[]';
        }

        $data = [];

        foreach (Schema::getColumns((new $modelNamespace())->getTable()) as $column) {
            if ($this->isNeedToSkip($column, $modelNamespace)) {
                continue;
            }

            $fakerFunction = null;

            if (str_contains($column['type'], 'varchar')) {
                $fakerFunction = $this->retrieveFromName($column['name']);
            }

            if (!$fakerFunction) {
                $fakerFunction = $this->retrieveFromType($column['type']);
            }

            if ($fakerFunction) {
                $data[] = "\t\t\t'{$column['name']}' => \$this->faker->{$fakerFunction},";
            }
        }

        return sprintf('[%s %s %s]', PHP_EOL, implode(PHP_EOL, $data), PHP_EOL."\t\t");
    }

    private function isNeedToSkip(array $column, string $modelNamespace): bool
    {
        if ($column['auto_increment']) {
            return true;
        }

        if ($column['name'] === $modelNamespace::UPDATED_AT || $column['name'] === $modelNamespace::CREATED_AT) {
            return true;
        }

        return false;
    }

    private function retrieveFromType(string $type): ?string
    {
        if ($type === 'datetime') {
            return 'dateTime';
        }

        if ($type === 'date') {
            return 'date';
        }

        if ($type === 'timestamp') {
            return 'unixTime';
        }

        if ($type === 'time') {
            return 'time';
        }

        if ($type === 'tinyint(1)' || $type === 'bool' || $type === 'boolean') {
            return 'boolean';
        }

        if (str_contains($type, 'varchar')) {
            return 'words(5, true)';
        }

        if (str_contains($type, 'text')) {
            return 'text';
        }

        if (str_contains($type, 'int')) {
            return 'randomNumber()';
        }

        if (str_contains($type, 'float') || str_contains($type, 'double')) {
            return 'randomFloat()';
        }

        return null;
    }

    private function retrieveFromName(string $name): ?string
    {
        $possibleColumnNames = [
            'first_name',
            'last_name',
            'name',
            'state',
            'city',
            'postcode',
            'address',
            'country',
            'company',
            'email',
            'user_name',
            'password',
            'url',
            'timezone',
            'slug',
            'ipv4',
            'ipv6',
        ];

        if (preg_match('#'.implode('|', $possibleColumnNames).'#', $name, $matches)) {
            return Str::camel($matches[0]);
        }

        if ($name === 'mac' || $name === 'macAddress') {
            return 'macAddress';
        }

        if ($name === 'latitude' || $name === 'lat') {
            return 'latitude';
        }

        if ($name === 'longitude' || $name === 'lon') {
            return 'longitude';
        }

        return null;
    }
}
