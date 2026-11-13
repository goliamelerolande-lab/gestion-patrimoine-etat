<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE IF NOT EXISTS patrimoine.notifications (
                id BIGSERIAL PRIMARY KEY,
                bien_id BIGINT NULL REFERENCES patrimoine.biens(id) ON DELETE SET NULL,
                type VARCHAR(40) NOT NULL,
                title VARCHAR(180) NOT NULL,
                message TEXT NOT NULL,
                read_at TIMESTAMPTZ NULL,
                created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
                updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
            )
        SQL);

        DB::statement('CREATE INDEX IF NOT EXISTS notifications_unread_created_idx ON patrimoine.notifications (read_at, created_at DESC)');
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS patrimoine.notifications');
    }
};
