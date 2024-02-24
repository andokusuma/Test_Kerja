<?php

namespace App\Models;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $table = 'detail_invoice';
    protected $fillable = [
        "coil_number",
        "width",
        "length",
        "thickness",
        "weight",
        "price",
        "id_invoice",
        "created_at",
        "updated_at"
    ];

    public function invoices()
    {
        return $this->belongsTo(Invoice::class, 'id_invoice', 'id');        
    }
}
