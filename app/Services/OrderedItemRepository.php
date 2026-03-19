<?php

declare(strict_types=1);

namespace ChinmoyBiswas\CBPortfolio\Services;

if (! defined('ABSPATH')) {
    exit;
}

class OrderedItemRepository
{
    private string $table;
    private array $intColumns;

    public function __construct(string $tableSuffix, array $intColumns = ['id', 'order_index'])
    {
        global $wpdb;
        $this->table = $wpdb->prefix . $tableSuffix;
        $this->intColumns = $intColumns;
    }

    public function getAll(string $selectColumns = '*'): array
    {
        global $wpdb;

        $results = $wpdb->get_results(
            "SELECT {$selectColumns} FROM {$this->table} ORDER BY order_index ASC, created_at ASC",
            OBJECT
        );

        if ($results) {
            foreach ($results as $row) {
                foreach ($this->intColumns as $col) {
                    if (isset($row->{$col})) {
                        $row->{$col} = (int) $row->{$col};
                    }
                }
            }
        }

        return $results ?: [];
    }

    public function save(array $data, ?int $id = null): bool
    {
        global $wpdb;

        if ($id) {
            $result = $wpdb->update(
                $this->table,
                $data,
                ['id' => $id],
                $this->buildFormats($data),
                ['%d']
            );
        } else {
            $maxOrder = (int) $wpdb->get_var(
                $wpdb->prepare("SELECT MAX(order_index) FROM {$this->table} WHERE %d = %d", 1, 1)
            );
            $data['order_index'] = $maxOrder + 1;
            $result = $wpdb->insert(
                $this->table,
                $data,
                $this->buildFormats($data)
            );
        }

        return $result !== false;
    }

    public function delete(int $id): bool
    {
        global $wpdb;

        $result = $wpdb->delete(
            $this->table,
            ['id' => $id],
            ['%d']
        );

        return $result !== false;
    }

    public function reorder(array $items): void
    {
        global $wpdb;

        foreach ($items as $item) {
            if (!isset($item['id'], $item['order_index'])) {
                continue;
            }

            $wpdb->update(
                $this->table,
                ['order_index' => (int) $item['order_index']],
                ['id' => (int) $item['id']],
                ['%d'],
                ['%d']
            );
        }
    }

    public function deleteAll(): void
    {
        global $wpdb;
        $wpdb->query($wpdb->prepare("DELETE FROM {$this->table} WHERE %d = %d", 1, 1));
    }

    public function insertRaw(array $data): bool
    {
        global $wpdb;
        return $wpdb->insert($this->table, $data, $this->buildFormats($data)) !== false;
    }

    public function getAllRaw(): ?array
    {
        global $wpdb;
        return $wpdb->get_results(
            "SELECT * FROM {$this->table} ORDER BY order_index ASC",
            ARRAY_A
        );
    }

    private function buildFormats(array $data): array
    {
        $formats = [];
        foreach ($data as $key => $value) {
            if (is_int($value)) {
                $formats[] = '%d';
            } elseif (is_float($value)) {
                $formats[] = '%f';
            } else {
                $formats[] = '%s';
            }
        }
        return $formats;
    }
}
