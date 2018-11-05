<?php

namespace HaoLi\LaravelDbLocale\Traits;

trait DbLocaleTrait
{
    public function getAttribute($key)
    {
        return (in_array($key, $this->getDbLocaleFields()))
            ? $this->getDbLocaleColumnValue($key)
            : parent::getAttribute($key);
    }

    public function getMutatedAttributes()
    {
        $attributes = parent::getMutatedAttributes();

        return array_merge($attributes, $this->getDbLocaleFields());
    }

    protected function mutateAttributeForArray($key, $value)
    {
        return (in_array($key, $this->getDbLocaleFields()))
            ? $this->getDbLocaleColumnValue($key)
            : parent::mutateAttributeForArray($key, $value);
    }

    public function getDbLocaleFields()
    {
        return (property_exists($this, 'dbLocaleFields')) ? $this->dbLocaleFields : [];
    }

    private static function getDbLocaleColumnName($key, $locale)
    {
        return $key.'_'.str_replace('-', '_', strtolower($locale));
    }

    private function getDbLocaleColumnValue($key)
    {
        $defaultColumn = self::getDbLocaleColumnName($key, config('app.locale'));
        $fallbackColumn = self::getDbLocaleColumnName($key, config('app.fallback_locale'));
        if (array_key_exists($defaultColumn, $this->attributes)) {
            $ret = $this->attributes[$defaultColumn];
        } elseif (array_key_exists($fallbackColumn, $this->attributes)) {
            $ret = $this->attributes[$fallbackColumn];
        } else {
            $ret = null;
        }

        return $ret;
    }
}
