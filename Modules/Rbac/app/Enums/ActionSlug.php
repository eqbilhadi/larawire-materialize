<?php

namespace Modules\Rbac\Enums;

enum ActionSlug: string
{
    case CREATE = 'create';
    case EDIT = 'edit';
    case DELETE = 'delete';
    case SORT = 'sort';
    case IMPORT = 'import';
    case EXPORT = 'export';
    case PRINT = 'print';
    case VIEW = 'view';

    public function label(): string
    {
        return match ($this) {
            self::CREATE => 'Create',
            self::EDIT => 'Edit',
            self::DELETE => 'Delete',
            self::SORT => 'Sort',
            self::IMPORT => 'Import',
            self::EXPORT => 'Export',
            self::PRINT => 'Print',
            self::VIEW => 'View',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::CREATE => 'primary',
            self::EDIT => 'info',
            self::DELETE => 'danger',
            self::SORT => 'warning',
            self::IMPORT => 'success',
            self::EXPORT => 'dark',
            self::PRINT => 'light',
            self::VIEW => 'secondary',
        };
    }

    public function isPageAction(): bool
    {
        return in_array($this, [
            self::CREATE,
            self::IMPORT,
            self::EXPORT,
            self::SORT,
        ]);
    }

    public function isRowAction(): bool
    {
        return in_array($this, [
            self::EDIT,
            self::DELETE,
            self::VIEW,
            self::PRINT,
        ]);
    }

    public static function options(): array
    {
        return array_map(
            fn(self $status) => (object) [
                'value' => $status->value,
                'label' => $status->label(),
            ],
            self::cases()
        );
    }
}
