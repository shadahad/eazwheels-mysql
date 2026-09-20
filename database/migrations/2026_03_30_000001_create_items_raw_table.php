<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations using pure DDL with strict CHECK constraints
     * ensuring actors bypassing the API cannot corrupt state.
     */
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS items (
                id CHAR(36) NOT NULL,
                item_name VARCHAR(255) NOT NULL,
                item_image VARCHAR(1024) NOT NULL,
                cost DECIMAL(10, 2) NOT NULL,
                size DECIMAL(5, 2) NOT NULL,
                units_in_stock INT UNSIGNED NOT NULL DEFAULT 0,
                description TEXT NOT NULL,
                created DATETIME NOT NULL,
                modified DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (id),
                CONSTRAINT chk_items_cost_positive CHECK (cost >= 0.00),
                CONSTRAINT chk_items_size_positive CHECK (size > 0.00),
                CONSTRAINT chk_items_stock_non_negative CHECK (units_in_stock >= 0)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS items;");
    }
};