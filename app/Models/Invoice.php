<?php

namespace App\Models;

use App\Models\InvoiceDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'invoice';
    protected $fillable = [
        "customer_name",
        "delivery_date",
        "no_invoice",
        "created_at",
        "updated_at"
    ];

    public function invoiceDetail()
    {
        return $this->hasMany(InvoiceDetail::class, 'id_invoice');  // id_invoice ini mereferensikan kolom id_invoice di InvoiceDetail
    }
}
