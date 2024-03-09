@extends('homelayout.layout')

@section('title')
    Manage User
@endsection

@section('content')

<div class="container">
    <div class="row">           
        <div class="col-lg-12 col-md-12 col-sm-12 mt-5">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3 mb-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3 mb-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Manage User</h3>
                    <div class="input-group" style="width: 200px;">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                    </div>
                </div>
                <div class="card-body" style="overflow-x: auto;">
                    @if ($users->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            <h4 class="alert-heading text-center">No User Registered</h4>
                            <p class="text-center">There Are No Registered User.</p>
                        </div>
                    @else                           
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>No. HP</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    @if ($user->role_id == 2) 
                                        <tr>
                                            <td>{{ $user->fullname }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->no_hp }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('home.deleteUser', $user->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm me-2" onclick="return confirm('Are you sure you want to delete this?')"><i
                                                        class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>                                       
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function () {
            $('#searchInput').on('keyup', function () {
                var searchText = $(this).val().toLowerCase();
                filterData(searchText);
            });

            function filterData(searchText) {
                $('tbody tr').filter(function () {
                    var rowText = $(this).text().toLowerCase();
                    var showRow = true;
                    if (searchText !== '') {
                        showRow = rowText.indexOf(searchText) > -1;
                    }
                    $(this).toggle(showRow);
                });
            }
        });
    </script>
@endsection
