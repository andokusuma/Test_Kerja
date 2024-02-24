@extends('master')
@section('content')
    @php
    @endphp
<div class="row">
    <di class="mt-5 p-5">
      <div class="border border-2 p-3 d-flex flex-column">
        <h3 class="d-flex justify-content-center">List Invoice</h3>
        <div class="d-flex flex-row-reverse mb-1">
          {{-- <a href="#" class="btn btn-primary">Add Invoice</a> --}}
          <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">Add Invoice</button>
          
        </div>

        <div class="card">
          <div class="card-body">            

            <div class="table-responsive">
              <table
                class="table"
              >
                <thead>
                  <tr style="cursor: pointer">
                    <th>ID_Invoice</th>
                    <th>Customer Name</th>
                    <th>Delivery Date</th>                    
                    <th>price</th>                    
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($invoices as $invoice)
                    <tr data-id="1" style="cursor: pointer">
                      <td>{{ $invoice->no_invoice }}</td>
                      <td data-field="customer_name">{{ $invoice->customer_name }}</td>                    
                      <td data-field="delivery_date">{{ $invoice->delivery_date }}</td>    
                      @if ($invoice->invoiceDetail->isNotEmpty())                        
                          {{-- <td data-field="price">{{ $invoice->invoiceDetail->first()->total_price }}</td> --}}
                          <td data-field="price">Rp. {{ number_format($invoice->invoiceDetail->first()->total_price, 2, ',', '.') }}</td>

                      @endif                
                      <td>
                        <a
                          class="btn btn-outline-secondary btn-sm "
                          title="Details"
                          href="{{ route('invoice.show.detail', $invoice->id) }}"
                        >
                        <i class="fa-solid fa-list"></i>
                        </a>
                        <a
                          class="btn btn-outline-secondary btn-sm"
                          title="Delete"
                          href="{{ route('invoice.delete', $invoice->id) }}"
                        >
                        <i class="fa-solid fa-trash-can"></i>
                        </a>
                        
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </di>
  </div>

  <div class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">ADD INVOICE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header bg-white border-0 text-center">
                      <h4>Make Invoice</h4>
                    </div>
                    <div class="card-body">
                      {{-- <form action="#" class="form"> --}}
                      <form action="{{ route('invoice.store') }}" class="form" method="POST">
                        @csrf
                        
                        @php
                            $no = rand(1, 9999);
                        @endphp

                        <div class="row mb-3 align-items-center">
                          <label for="" class="col-3">Invoice No </label>
                          <div class="col-4">
                            <input
                                type="hidden"
                                id="no_invoice"
                                class="form-control"
                                value="JFE-INV-{{$no }}"
                                name="no_invoice"                                
                            />
                            <input
                                type="text"
                                id="no_invoice"
                                class="form-control"
                                value="JFE-INV-{{$no }}"
                                name="no_invoice"
                                disabled
                            />

                          </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                          <label for="" class="col-3">Customer Name</label>
                          <div class="col-4">
                            <input type="text" class="form-control" id="customer_name" name="customer_name" />
                            @error('customer_name')
                              <span class="text-danger"> {{$message}} </span>
                            @enderror
                          </div>
                        </div>
                        <div class="row mb-3 align-items-center">
                          <label for="" class="col-3">Delivery Date</label>
                          <div class="col-4">
                            <input id="tanggal" class="form-control" type="date" name="tanggal" />
                            @error('tanggal')
                              <span class="text-danger"> {{$message}} </span>
                            @enderror
                          </div>
                        </div>
                        <div class="row justify-content-end">
                          <div class="col-sm-4 text-end">
                            <div class="btn btn-primary btn-transaksi">
                              Add Transaction
                            </div>
                          </div>
                        </div>
                        <table class="table table-form">
                          <tbody>
                            <tr>
                              <td class="col-2">
                                <input
                                  class="form-control"
                                  type="text"
                                  id="coil_number"
                                  name="coil_number-1"
                                  value=""
                                  placeholder="coil number"
                                />
                                @error('coil_number-1')
                                  <span class="text-danger"> {{$message}} </span>
                                @enderror
                              </td>
                              <td class="col-2">
                                <input
                                  class="form-control"
                                  type="text"
                                  id="width"
                                  name="width-1"
                                  value=""
                                  placeholder="Width"
                                />
                                @error('width-1')
                                  <span class="text-danger"> {{$message}} </span>
                                @enderror
                              </td>
                              <td class="col-2">
                                <input
                                  class="form-control"
                                  type="text"
                                  id="length"
                                  name="length-1"
                                  value=""
                                  placeholder="Length"
                                />
                                @error('length-1')
                                  <span class="text-danger"> {{$message}} </span>
                                @enderror
                              </td>
                              <td class="col-2">
                                <input
                                  class="form-control"
                                  type="text"
                                  id="thicness"
                                  name="thickness-1"
                                  value=""
                                  placeholder="thicness"
                                />
                                @error('thickness-1')
                                  <span class="text-danger"> {{$message}} </span>
                                @enderror
                              </td>
                              <td class="col-2">
                                <input
                                  class="form-control"
                                  type="text"
                                  id="weight"
                                  name="weight-1"
                                  value=""
                                  placeholder="weight"
                                />
                                @error('weight-1')
                                  <span class="text-danger"> {{$message}} </span>
                                @enderror
                              </td>
                              <td class="col-2">
                                <input
                                  class="form-control"
                                  type="text"
                                  id="price"
                                  name="price-1"
                                  value=""
                                  placeholder="price"
                                />
                                @error('price-1')
                                <span class="text-danger"> {{$message}} </span>
                              @enderror
                              </td>
                              <td class="col-2">
                                <div class="btn btn-outline-danger" id="delete">X</div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <div class="row">
                          <button type="submit" class="btn btn-primary">Process</button>
                        </div>
                      </form>
                    </div>
                </div>                    
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

{{-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> --}}
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
  // Menangkap elemen formulir dan tabel
  const form = document.querySelector("form");
  const tableBody = document.querySelector(".table-form tbody");
  const btnAdd = document.querySelector(".btn-transaksi");
  const coilNumber = document.getElementById("coil_number");
  const btnDelete = document.querySelector("#delete");
  const inputCustomerName = document.querySelector("#customer_name");
  const inputNoInvoice = document.querySelector("#no_invoice");
  const inputTanggal = document.querySelector("#tanggal");

  // ================ ADD BARIS/ROW TRANSAKSI ================
  btnAdd.addEventListener("click", function (event) {
    event.preventDefault();

    // Menambahkan baris baru ke dalam tabel
    const rowCount = tableBody.rows.length;
    console.log(rowCount);
    var newRow = tableBody.insertRow();
    newRow.innerHTML = `
      <td><input class="form-control" name="coil_number-${rowCount + 1}" id="coil_number" type="text" value="" placeholder="coil_number" ></td>
      <td><input class="form-control" name="width-${rowCount + 1}" id="width" type="text" value="" placeholder="Width" ></td>
      <td><input class="form-control" name="length-${rowCount + 1}" id="length" type="text" value="" placeholder="Length" ></td>
      <td><input class="form-control" name="thickness-${rowCount + 1}" id="thickness" type="text" value="" placeholder="thickness"></td>
      <td><input class="form-control" name="weight-${rowCount + 1}" id="weight" type="text" value="" placeholder="weight" ></td>
      <td><input class="form-control" name="price-${rowCount + 1}" id="price" type="text" value="" placeholder="price" ></td>
      <td><div class="btn btn-outline-danger" id="delete">X</div></td>
      `;

    // CARA 1 REMOVE/DELETE
    const btnRemove = newRow.querySelector("#delete");
    btnRemove.addEventListener("click", function () {
      tableBody.removeChild(newRow);
    });
  });

  // ================ DELETE BARIS/ROW TRANSAKSI ================
  btnDelete.addEventListener("click", function (e) {
    // CARA 2 REMOVE/DELETE
    e.preventDefault();
    const rows_tr = document.querySelector(".table-form tbody tr");      
    const rowToDelete = event.target.parentNode.parentNode;    
    // tableBody.removeChild(rowToDelete);

        // tableBody.removeChild(rowToDelete);

    if (rows_tr.length > 1) {
      const rowToDelete = event.target.parentNode.parentNode;
      tableBody.removeChild(rowToDelete);
    }
  });
</script>
@endsection
