<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $query = "
            CREATE VIEW invoice_with_price_view AS
            SELECT
                invoice.id,
                invoice.customer_name,
                invoice.delivery_date,
                invoice.no_invoice,
                SUM(detail_invoice.price) as total_price
            FROM
                invoice
            JOIN
                detail_invoice ON invoice.id = detail_invoice.id_invoice
            GROUP BY
                invoice.id, invoice.customer_name, invoice.delivery_date, invoice.no_invoice;
        ";

        DB::statement($query);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS invoice_with_price_view;');
    }
};
