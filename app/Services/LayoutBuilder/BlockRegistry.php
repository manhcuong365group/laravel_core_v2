<?php

namespace App\Services\LayoutBuilder;

use InvalidArgumentException;

/**
 * Registry quản lý tất cả các Block types đã đăng ký.
 * Đảm bảo chỉ các block hợp lệ mới được sử dụng trong hệ thống.
 */
class BlockRegistry
{
    /**
     * Danh sách các block class đã đăng ký.
     *
     * @var array<string, class-string<BlockContract>>
     */
    protected array $blocks = [];

    /**
     * Đăng ký một block type mới.
     */
    public function register(string $blockClass): void
    {
        if (!is_subclass_of($blockClass, BlockContract::class)) {
            throw new InvalidArgumentException(
                "Block [{$blockClass}] phải implement " . BlockContract::class
            );
        }

        $this->blocks[$blockClass::type()] = $blockClass;
    }

    /**
     * Đăng ký nhiều block types cùng lúc.
     *
     * @param array<class-string<BlockContract>> $blockClasses
     */
    public function registerMany(array $blockClasses): void
    {
        foreach ($blockClasses as $blockClass) {
            $this->register($blockClass);
        }
    }

    /**
     * Lấy block class theo type.
     */
    public function get(string $type): ?string
    {
        return $this->blocks[$type] ?? null;
    }

    /**
     * Kiểm tra block type có tồn tại không.
     */
    public function has(string $type): bool
    {
        return isset($this->blocks[$type]);
    }

    /**
     * Lấy tất cả blocks đã đăng ký.
     *
     * @return array<string, class-string<BlockContract>>
     */
    public function all(): array
    {
        return $this->blocks;
    }

    /**
     * Lấy danh sách blocks theo category (để hiển thị Gallery).
     */
    public function getByCategory(): array
    {
        $grouped = [];

        foreach ($this->blocks as $type => $blockClass) {
            $category = $blockClass::category();
            $grouped[$category][] = [
                'type' => $type,
                'label' => $blockClass::label(),
                'icon' => $blockClass::icon(),
                'description' => $blockClass::description(),
                'defaults' => $blockClass::defaultData(),
            ];
        }

        return $grouped;
    }

    /**
     * Validate dữ liệu của một block theo type.
     */
    public function validate(string $type, array $data): array
    {
        $blockClass = $this->get($type);

        if (!$blockClass) {
            throw new InvalidArgumentException("Block type [{$type}] không tồn tại.");
        }

        $rules = $blockClass::validationRules();

        return validator($data, $rules)->validate();
    }

    /**
     * Lấy default data cho một block type.
     */
    public function getDefaults(string $type): array
    {
        $blockClass = $this->get($type);

        if (!$blockClass) {
            return [];
        }

        return $blockClass::defaultData();
    }
}
