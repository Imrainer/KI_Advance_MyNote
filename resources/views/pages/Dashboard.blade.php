<x-layout title="Dashboard">
 
<div class="d-flex">
<x-Sidebar photo="{{$admin->photo}}" name="{{$admin->name}}"></x-Sidebar>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Form Tambahkan User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>    
      <div class="modal-body">
      
        <form action="/register" method="POST">
          @csrf
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Nama</label>
              <input type="text" class="form-control" name="nama">
            </div>

            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Email</label>
              <input type="text" class="form-control" name="email">
            </div>

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Nomor Telepon</label>
                <input type="number" class="form-control" name="nomor_telepon">
              </div>


            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Password</label>
              <input type="password" class="form-control" name="password" id="exampleInputPassword1">
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="exampleCheck1">
              <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-outline-primary">Submit</button>
       
      </form> 
    </div>

      </div>
    </div>
  </div>
</div>

  <div class=" col-md-10">

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@foreach($errors->all() as $msg)
  <div class="alert alert-danger">{{$msg}}</div>
  @endforeach
  

<div class="mt-5 ms-5 col-md-9 ">

    <button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-outline-success mb-1 mt-3"><i class="fas fa-user-plus"></i> Add New</button>
    
    <form action="http://localhost/laravel_tasknote/public/dashboard" method="GET" class="col-md-6 mt-3">
      <div class="mb-3 d-flex">
        <i class="fas fa-search mt-2 me-3"></i>
        <input type="search" name="search" class="form-control col-md-5" placeholder="Type here">
        <button class="btn btn-outline-success ms-3">Search</button>
      </div>
    </form>
    
    <table class="table">
        <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">Nama</th>
            <th scope="col">No. Telepon</th>
            <th scope="col">Email</th>
            <th scope="col">Photo</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>       
          @foreach ($data as $index => $item)
          <tr>
            <th scope="row">{{ $index + 1 }}</th>
            <td>{{$item->name}}</td>
            <td>{{$item->phone}}</td>
            <td>{{$item->email}}</td>
            @if (empty($item->photo) )
            <td> </td>
            @else
            <td> <img src="{{ asset ('storage/' . $item->foto) }}"  class="rounded-circle" width="40px" alt="Foto Profil "></td>
            @endif
            <td>
            <a href="http://localhost/laravel/public/edit/{{$item->id}}" class="me-1 fas fa-pen text-primary text-decoration-none"></a>
            <a href="http://localhost/laravel/public/deleteuser/{{$item->id}}" class="ms-1 fas fa-trash text-danger"></a>  
            <td>
          </tr>
          @endforeach
        </tbody>
      </table>

      {{$data->links()}}
</div>
</div>

</x-layout>