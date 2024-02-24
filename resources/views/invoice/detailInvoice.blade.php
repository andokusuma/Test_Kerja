@extends('master')
@section('content')

<div class="container  mt-4">  
  <div class="card">
    <div class="card-header">
      <div
        class="d-flex flex-row justify-content-between align-items-center"
      >
        <h5>{{ \Carbon\Carbon::parse($tanggal)->format('d F, Y') }}</h5>
        <div class="">{{$no_invoice}}</div>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-6">
          <h5>From :</h5>
          <p class="fw-bold">{{$invoiceDetails->first()->invoices->customer_name}}</p>
          <p class="w-75">
            Madalinskiego 8 71-101 Szczecin, Poland Email: info@webz.com.pl
            Phone: +48 444 666 3333
          </p>
        </div>
        {{-- <div class="col-sm-6 pl-5">
          <h5 class="">To:</h5>
          <p class="fw-bold">Bob Mart</p>
          <p class="w-75">
            Attn: Daniel Marek 43-190 Mikolow, Poland Email:
            marek@daniel.com Phone: +48 123 456 789
          </p>
        </div> --}}
      </div>
      <div class="table-responsive">
        <table class="table table-editable table-nowrap align-middle table-edits">
          <thead class="table-light ">
            <tr>
              <th scope="col">Coil Number</th>
              <th scope="col">Width</th>
              <th scope="col">Length</th>
              <th scope="col">Thickness</th>
              <th scope="col">Weight</th>
              <th scope="col">Price</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            
            @foreach ($invoiceDetails as $invoice)            
              <tr>
                <td id="coil_number" data-field="coil_number">{{$invoice->coil_number}}</td>
                <td id="width" data-field="width">{{$invoice->width}}</td>
                <td id="length" data-field="length">{{$invoice->length}}</td>
                <td id="thickness" data-field="thickness">{{$invoice->thickness}}</td>
                <td id="weight" data-field="weight">{{$invoice->weight}}</td>                
                <td id="price" data-field="price">Rp. {{ number_format($invoice->price, 2, ',', '.') }}</td>
                <td class="d-flex">
                  <a
                    class="btn btn-outline-secondary btn-sm edit"
                    title="Edit"
                    data-field={{$invoice->id}}
                  >
                    <!-- <i class="fas fa-pencil-alt"></i> -->
                    <i
                      class="fa-regular fa-pen-to-square"
                      title="Edit"
                    ></i>
                  </a>
                  <a
                    class="btn btn-outline-secondary btn-sm cancel"
                    title="Cancel" style="display: none"
                  >
                    <!-- <i class="fas fa-pencil-alt"></i> -->
                    <i class='fas fa-times' title="Cancel"></i>
                  </a>
                </td>
              </tr>                
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="row justify-content-end">
        <div class="col-lg-4">`
          <table class="table">
            <tbody>
              <tr>
                <td class="">
                  <strong>Total Price: </strong>
                </td>
                <td class="">
                  <strong>Rp. {{ number_format($totalPrice, 2, ',', '.') }}</strong>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="row">
        <button class="btn btn-primary">Print</button>
      </div>
    </div>
  </div>
</div>
<form method="POST" class="form" style="display: none">
  @csrf
</form>
<script>
  function toggleEdit(td) {
    // Mendapatkan id dari elemen td
      var id = td.getAttribute('id');
      var input = document.createElement('input');
      input.type = 'text';
      input.value = td.textContent;
      input.className = 'form-control';    
      input.name = id;
      
      // Clear the content of the cell and append the input              
      td.innerHTML = '';
      td.appendChild(input);                   
      
      input.focus();                      
  }

  function saveChanges(row) {
    const form = document.querySelector('form');
    var inputs = row.querySelectorAll('input');    
    
    inputs.forEach(function (input) {
      var td = input.parentNode;
      td.textContent = input.value;      
      form.appendChild(input);
    });
    const editButton = row.querySelector('.edit'); // Ganti dengan selector yang sesuai
    const idDetail = editButton.getAttribute('data-field');  
    form.action = "{{ route('invoice.detail.update', ['id' => ':id']) }}".replace(':id', idDetail);  


    // Replace the 'fa-save' class with 'fa-pen-to-square' for all edit icons    
    const editIcons = row.querySelectorAll('.fa-regular.fa-save');
    editIcons.forEach(function (editIcon) {
        editIcon.classList.replace('fa-save', 'fa-pen-to-square');
    });
    form.submit();
  }

  function cancelChanges(row) {
    var inputs = row.querySelectorAll('input');    

    inputs.forEach(function (input) {
      var td = input.parentNode;
      td.textContent = input.value;
    });    

    const editIcons = row.querySelectorAll('.fa-regular.fa-save');
    editIcons.forEach(function (editIcon) {
        editIcon.classList.replace('fa-save', 'fa-pen-to-square');
    });
  }

  const editButtons = document.querySelectorAll('.edit');
  const cancelButtons = document.querySelectorAll('.cancel');  

  const inputData = [];
  let isEditingGlobal = false;
  let isEditing = true;
  editButtons.forEach(function (button) {              
    button.addEventListener('click', function () {
      const row = this.closest('tr');
      const cells = row.querySelectorAll('td[id]');    
      const cancelBtn = row.querySelector('.cancel');         

      
      if(isEditing ){
        cancelBtn.style.display = 'inline';
        
        var editIcon = row.querySelector('.fa-regular.fa-pen-to-square');
        editIcon.classList.replace('fa-pen-to-square', 'fa-save');     
            
        // Iterate through each cell in the row
        cells.forEach(function (cell) {
          toggleEdit(cell);              
        });                    
      }      
      if(!isEditing){   
        cancelBtn.style.display = 'none';                           
        saveChanges(row);       
      }
      isEditing = !isEditing;      
    }); 
  });

  cancelButtons.forEach(function (cancelBtn) {
  cancelBtn.addEventListener('click', function () {  
    isEditing = !isEditing;
    var row = this.closest('tr');
    cancelChanges(row);
    cancelBtn.style.display = 'none';
  });
});
</script>



{{-- <script>
  document.addEventListener('DOMContentLoaded', function () {
    function toggleEdit(td) {
        var input = document.createElement('input');
        input.type = 'text';
        input.value = td.textContent;
        input.className = 'form-control';

        // Clear the content of the cell and append the input
        td.innerHTML = '';
        td.appendChild(input);

        // Create the edit button
        var editBtn = document.createElement('a');
        editBtn.className = 'btn btn-outline-secondary btn-sm edit';
        editBtn.title = 'Edit';
        editBtn.id = 'edit';
        editBtn.innerHTML = "<i class='fa-regular fa-pen-to-square' title='Edit'></i>";
        td.appendChild(editBtn);

        // Create the save button
        var saveBtn = document.createElement('a');
        saveBtn.className = 'btn btn-outline-secondary btn-sm save';
        saveBtn.title = 'Save';
        saveBtn.id = 'save';
        saveBtn.innerHTML = "<i class='fas fa-save'></i>";
        td.appendChild(saveBtn);

        // Create the cancel button
        var cancelBtn = document.createElement('a');
        cancelBtn.className = 'btn btn-outline-secondary btn-sm cancel';
        cancelBtn.title = 'Cancel';
        cancelBtn.id = 'cancel';
        cancelBtn.innerHTML = "<i class='fas fa-times'></i>";
        td.appendChild(cancelBtn);

        // Focus on the input
        input.focus();

        // Handle click event for edit button
        editBtn.addEventListener('click', function () {
            // Restore the original content of the cell
            td.innerHTML = td.dataset.originalContent;
        });

        // Handle click event for save button
        saveBtn.addEventListener('click', function () {
            td.textContent = input.value;
            // Remove the buttons
            td.removeChild(editBtn);
            td.removeChild(saveBtn);
            td.removeChild(cancelBtn);
        });

        // Handle click event for cancel button
        cancelBtn.addEventListener('click', function () {
            // Restore the original content of the cell
            td.innerHTML = td.dataset.originalContent;
        });
    }

    var editButtons = document.querySelectorAll('.edit');
    editButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var row = this.closest('tr');

            // Iterate through each cell in the row
            var cells = row.querySelectorAll('td[id]');
            cells.forEach(function (cell) {
                toggleEdit(cell);
            });
        });
    });
});
</script> --}}



@endsection