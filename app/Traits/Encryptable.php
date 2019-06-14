<?php

namespace App\Traits;

trait Encryptable
{
    // public function getAttribute($key)
    // {
    //     $value = parent::getAttribute($key);

    //     if (in_array($key, $this->encryptable)) {
    //         $value = decrypt($value);
    //     }
    // }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach (static::$encryptable as $key) {
            if (isset($attributes[$key])) {
                $attributes[$key] = decrypt($attributes[$key]);
            }
        }
        return $attributes;
    }
}
