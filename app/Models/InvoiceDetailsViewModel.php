<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetailsViewModel extends Model
{
    use HasFactory;
    protected $table = 'invoice_with_price_view';
}
