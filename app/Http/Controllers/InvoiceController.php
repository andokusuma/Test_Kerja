<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\InvoiceDetailsViewModel;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index(){

        // CARA 1 MANUAL
        $invoices = Invoice::with(['invoiceDetail' => function ($query) {
            $query->select('id_invoice', DB::raw('SUM(price) as total_price'))
                  ->groupBy('id_invoice');
        }])
        ->latest('delivery_date') 
        ->select([
            'invoice.id AS id',
            'invoice.customer_name',
            'invoice.delivery_date',
            'invoice.no_invoice',
        ])
        ->get();

        // CARA 2 MAKE VIEW
        // $invoices = InvoiceDetailsViewModel::all();        
        return view("invoice.invoice", compact('invoices'));
    }

    public function storeInvoice(Request $request){        

        $rules = [
            'customer_name' => 'required|string',
            'tanggal' => 'required|date',                  
        ];

        $rulesMessage = [
            'customer_name.required' => 'Nama pelanggan harus diisi.',            
            'customer_name.string' => 'Nama pelanggan harus dengan kalimat.',     
            'tanggal.required' => 'Tanggal harus diisi',   

        ];
        foreach ($request->all() as $key => $value) {
            $matches = [];
            if (preg_match('/^(coil_number|width|length|thickness|weight|price)-(\d+)$/', $key, $matches)) {
                $dataNumber = $matches[2];

                $rules["coil_number-$dataNumber"] = 'required|regex:/^\d+(\.\d{1,2}){0,3}$/';
                $rules["width-$dataNumber"] = 'required|regex:/^\d+(\.\d{1,3}){0,3}$/';
                $rules["length-$dataNumber"] = 'required|regex:/^\d+(\.\d{1,3}){0,3}$/';
                $rules["thickness-$dataNumber"] = 'required|regex:/^\d+(\.\d{1,3}){0,3}$/';
                $rules["weight-$dataNumber"] = 'required|regex:/^\d+(\.\d{1,3}){0,3}$/';
                $rules["price-$dataNumber"] = 'required|regex:/^\d+(\.\d{1,3}){0,3}$/';
        
                $rulesMessage["coil_number-$dataNumber.required"] = 'Nomor coil harus diisi.';
                $rulesMessage["coil_number-$dataNumber.regex"] = 'Nomor coil harus sesuai dengan format "100.100.10".';
                $rulesMessage["width-$dataNumber.required"] = 'Lebar harus diisi.';
                $rulesMessage["width-$dataNumber.regex"] = 'Lebar harus sesuai dengan format "100.100.10".';
                $rulesMessage["length-$dataNumber.required"] = 'Panjang harus diisi';
                $rulesMessage["length-$dataNumber.regex"] = 'Panjang harus sesuai dengan format "100.100.10".';
                $rulesMessage["thickness-$dataNumber.required"] = 'Ketebalan harus diisi.';
                $rulesMessage["thickness-$dataNumber.regex"] = 'Ketebalan harus sesuai dengan format "100.100.10".';
                $rulesMessage["weight-$dataNumber.required"] = 'Berat harus diisi.';
                $rulesMessage["weight-$dataNumber.regex"] = 'Berat harus sesuai dengan format "100.100.10".';
                $rulesMessage["price-$dataNumber.required"] = 'Harga harus diisi';
                $rulesMessage["price-$dataNumber.regex"] = 'Harga harus sesuai dengan format "100.100.10".';
            }
        }
        $validator = Validator::make($request->all(), $rules, $rulesMessage);
                
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }        

        // Inisialisasi array untuk menyimpan data
        $data = [];           

        // Simpan data ke dalam tabel invoice
        $invoice = Invoice::create([
            'customer_name' => $request->input('customer_name'),
            'delivery_date' => $request->input('tanggal'),
            'no_invoice' => $request->input('no_invoice'),
        ]);
        

        $coilNumberKeys = preg_grep('/^coil_number-\d+$/', array_keys($request->all()));        
        $coilNumberCount = count($coilNumberKeys);

        for ($dataNumber = 1; $dataNumber <= $coilNumberCount; $dataNumber++) {
            InvoiceDetail::create([
                'coil_number' => $request->input("coil_number-$dataNumber"),
                'width' => $request->input("width-$dataNumber"),
                'thickness' => $request->input("thickness-$dataNumber"),
                'length' => $request->input("length-$dataNumber"),
                'weight' => $request->input("weight-$dataNumber"),
                'price' => $request->input("price-$dataNumber"),
                'id_invoice' => $invoice->id,
            ]);
        }        
        // echo "Coil Number: " . $request->input("coil_number-$dataNumber") . "<br>" .
        //         "Width: " . $request->input("width-$dataNumber") . "<br>" .
        //         "Length: " . $request->input("length-$dataNumber") . "<br>" .
        //         "Weight: " . $request->input("weight-$dataNumber") . "<br>" .
        //         "Price: " . $request->input("price-$dataNumber") . "<br><br>" ;

        $notification = array(
            'message' => 'Invoice Created Successfully',
            'alert-type' => 'success',
        );

        return redirect()->route('invoice.all')->with($notification);
    }

    public function detailInvoice($id){                  
        $invoiceDetails = InvoiceDetail::with("invoices")->where("id_invoice", $id)->get();
        $totalPrice = $invoiceDetails->sum('price');  
        $tanggal = $invoiceDetails->pluck('invoices.delivery_date')->first();   
        $no_invoice = $invoiceDetails->pluck('invoices.no_invoice')->first();   
                   
        return view("invoice.detailInvoice", compact('invoiceDetails', 'totalPrice', 'tanggal', 'no_invoice'));
    }

    public function deleteInvoice($id){
        Invoice::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Invoice Deleted Successfully',
            'alert-type' => 'success',
        );
        
        return redirect()->back()->with($notification);
    }

    public function updateDetailInvoice(Request $request, $id){
        $price = str_replace(['Rp. ', ','], ['', ''], $request->input('price'));        
        $price = str_replace('.', '', $price);
        $price = substr($price, 0, -2) . '.' . substr($price, -2);
        $request->merge(['price' => $price]);

        $rules["coil_number"] = 'required|regex:/^\d+(\.\d{1,2}){0,3}$/';
        $rules["width"] = 'required|regex:/^\d+(\.\d{1,3}){0,3}$/';
        $rules["length"] = 'required|regex:/^\d+(\.\d{1,3}){0,3}$/';
        $rules["thickness"] = 'required|regex:/^\d+(\.\d{1,3}){0,3}$/';
        $rules["weight"] = 'required|regex:/^\d+(\.\d{1,3}){0,3}$/';
        $rules["price"] = 'required|regex:/^\d+(\.\d{1,3}){0,3}$/';
        
        $rulesMessage["coil_number.required"] = 'Nomor coil harus diisi.';
        $rulesMessage["coil_number.regex"] = 'Nomor coil harus sesuai dengan format';
        $rulesMessage["width.required"] = 'Lebar harus diisi.';
        $rulesMessage["width.regex"] = 'Lebar harus sesuai dengan format';
        $rulesMessage["length.required"] = 'Panjang harus diisi';
        $rulesMessage["length.regex"] = 'Panjang harus sesuai dengan format';
        $rulesMessage["thickness.required"] = 'Ketebalan harus diisi.';
        $rulesMessage["thickness.regex"] = 'Ketebalan harus sesuai dengan format';
        $rulesMessage["weight.required"] = 'Berat harus diisi.';
        $rulesMessage["weight.regex"] = 'Berat harus sesuai dengan format';
        $rulesMessage["price.required"] = 'Harga harus diisi';
        $rulesMessage["price.regex"] = 'Harga harus sesuai dengan format "100.100.10".';

        $validator = Validator::make($request->all(), $rules, $rulesMessage);
        $notification = array(
            'message' => 'Invoice Detail Gagal Update Karena '. $validator->errors()->first(),
            'alert-type' => 'error',
        );

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->with($notification);
        }
        
        InvoiceDetail::findOrFail($id)->update([
            'coil_number' => $request->input("coil_number"),
            'width' => $request->input("width"),
            'thickness' => $request->input("thickness"),
            'length' => $request->input("length"),
            'weight' => $request->input("weight"),
            'price' => $request->input("price"),
            'updated_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Detail Invoice Updated Successfully',
            'alert-type' => 'success',
        );

        return  redirect()->back()->with($notification);
    }
    
}
