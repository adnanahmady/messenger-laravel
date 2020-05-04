<?php

namespace App\Custom;

use App\Custom\Contracts\Json as JsonInterface;

class Body implements JsonInterface
{
    public $data;
    public $meta;

    public function __construct($data = '')
    {
        $this->data = $data;
    }

    public static function __callStatic($name, $arguments)
    {
        if (!method_exists($name, get_called_class())) {
            return new static(...$arguments);
        }

        return static::$name(...$arguments);
    }

    public function meta($meta)
    {
        $this->meta = $meta;

        return $this;
    }

    public function message($message)
    {
        $this->meta['message'] = $message;

        return $this;
    }

    public function __toString()
    {
        return json_encode(
            [
                'data' => $this->data,
                'meta' => $this->meta
            ],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
