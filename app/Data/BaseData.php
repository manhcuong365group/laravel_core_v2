<?php

namespace App\Data;

use ReflectionClass;
use ReflectionNamedType;

abstract class BaseData
{
    /**
     * Tạo instance từ mảng dữ liệu.
     * Tự động map các key trong array sang constructor parameters.
     */
    public static function fromArray(array $data): static
    {
        $reflection = new ReflectionClass(static::class);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new static();
        }

        $args = [];

        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();
            $typeName = $type instanceof ReflectionNamedType ? $type->getName() : null;

            if (!array_key_exists($name, $data)) {
                $args[$name] = $param->isDefaultValueAvailable()
                    ? $param->getDefaultValue()
                    : null;
                continue;
            }

            $value = $data[$name];

            // Cast giá trị sang đúng type khai báo trong constructor
            $args[$name] = match ($typeName) {
                'int' => $value !== null && $value !== '' ? (int) $value : null,
                'float' => $value !== null && $value !== '' ? (float) $value : null,
                'bool' => (bool) $value,
                'string' => (string) ($value ?? ''),
                'array' => is_array($value) ? $value : [],
                default => $value,
            };

            // Xử lý nullable: nếu type cho phép null và value rỗng
            if ($type?->allowsNull() && ($value === null || $value === '')) {
                $args[$name] = null;
            }
        }

        return new static(...$args);
    }

    /**
     * Chuyển đổi sang mảng (chỉ các public properties).
     * Các subclass có thể override để loại bỏ field không cần lưu DB.
     */
    public function toArray(): array
    {
        $reflection = new ReflectionClass($this);
        $result = [];

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            $result[$prop->getName()] = $prop->getValue($this);
        }

        return $result;
    }
}
