@extends('homelayout.layout')

@section('title')
    Disable Date
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2>Disable Dates</h2>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif

                <!-- Form untuk menambahkan tanggal -->
                <form method="POST" action="{{ route('home.disableDateStore') }}">
                    @csrf
                    <div class="form-group">
                        <label for="disabled_date">Choose Date to Disable</label>
                        <input type="date" id="disabled_date" name="disabled_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Disable Date</button>
                </form>

                <hr>

                <!-- Tabel untuk menampilkan daftar tanggal yang dinonaktifkan -->
                <h3>Disabled Dates Table:</h3>
                @if($disabledDates->isEmpty())
                    <p>No disabled dates yet.</p>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($disabledDates as $date)
                            <tr>
                                <td>{{ $date->disabled_date }}</td>
                                <td>{{ $date->description }}</td>
                                <td>
                                    <div class="d-flex">
                                        <form method="POST" action="{{ route('home.disableDateDestroy', $date->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm me-2" onclick="return confirm('Are you sure you want to delete this?')">Delete</button>
                                        </form>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ModalDeskripsiEdit{{$date->id}}">
                                            Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <!-- Modal Edit -->
                            <div class="modal fade" id="ModalDeskripsiEdit{{$date->id}}" tabindex="-1" aria-labelledby="ModalDeskripsiEditLabel{{$date->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalDeskripsiEditLabel{{$date->id}}">Edit Description</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST" action="{{ route('home.disableDateUpdate', $date->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="edit_description{{$date->id}}">Edit Description</label>
                                                    <textarea id="edit_description{{$date->id}}" name="edit_description" class="form-control" required>{{ $date->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
