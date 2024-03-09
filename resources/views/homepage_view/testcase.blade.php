@extends('homelayout.layout')

@section('title')
    List Test Case
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3>List Test Case</h3>
                        <div class="d-flex justify-content-end px-3 pb-3">
                            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addTestCaseModal">
                                Add Test Case
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($data_testcase->isEmpty())
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading text-center">No Data</h4>
                                <p class="text-center">There is no data</p>
                            </div>
                        @else
                            <table class="table table-striped" id="tableTestCase">
                                <thead>
                                    <tr>
                                        <th scope="col">Test Case Domain</th>
                                        <th scope="col">Module Name</th>
                                        <th scope="col">Test Description</th>
                                        <th scope="col">Test Case Type</th>
                                        <th scope="col">Test Case Step</th>
                                        <th scope="col">Test Data</th>
                                        <th scope="col">Expected Result</th>
                                        <th scope="col">Actual Result</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data_testcase as $testcase)
                                    <tr>
                                        <td>{{$testcase->test_domain}}</td>
                                        <td>{{$testcase->module_name}}</td>
                                        <td>{{$testcase->test_description}}</td>
                                        <td>{{$testcase->test_case_type}}</td>
                                        <td>{{$testcase->test_step}}</td>
                                        <td>{{$testcase->test_data}}</td>
                                        <td>{{$testcase->expected_result}}</td>
                                        <td>{{$testcase->actual_result}}</td>
                                        <td>
                                            <div class="d-flex">
                                                <form method="POST" action="{{ route('home.deleteTestCase', $testcase->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm me-2" onclick="return confirm('Are you sure you want to delete this?')">Delete</button>
                                                </form>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editTestCaseModal{{$testcase->id}}">
                                                    Edit
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="editTestCaseModal{{$testcase->id}}" tabindex="-1" aria-labelledby="editTestCaseModal{{$testcase->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editTestCaseModal{{$testcase->id}}">Edit Test Case</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST" action="{{ route('home.editTestCase', $testcase->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="test_domain{{$testcase->id}}">Edit Test Domain</label>
                                                            <textarea id="test_domain{{$testcase->id}}" name="test_domain" class="form-control" required>{{ $testcase->test_domain }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="module_name{{$testcase->id}}">Edit Module Name</label>
                                                            <textarea id="module_name{{$testcase->id}}" name="module_name" class="form-control" required>{{ $testcase->module_name }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="test_description{{$testcase->id}}">Edit Description</label>
                                                            <textarea id="test_description{{$testcase->id}}" name="test_description" class="form-control" required>{{ $testcase->test_description }}</textarea>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label for="test_case_type{{$testcase->id}}">Edit Test Case Type</label>
                                                            <select class="form-select" id="test_case_type{{$testcase->id}}" name="test_case_type" required>
                                                                <option value="1" {{ $testcase->test_case_type ? 'selected' : '' }}>True</option>
                                                                <option value="0" {{ !$testcase->test_case_type ? 'selected' : '' }}>False</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="test_step{{$testcase->id}}">Edit Test Step</label>
                                                            <textarea id="test_step{{$testcase->id}}" name="test_step" class="form-control" required>{{ $testcase->test_step }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="test_data{{$testcase->id}}">Edit Test Data</label>
                                                            <textarea id="test_data{{$testcase->id}}" name="test_data" class="form-control" required>{{ $testcase->test_data }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="expected_result{{$testcase->id}}">Edit Expected Result</label>
                                                            <textarea id="expected_result{{$testcase->id}}" name="expected_result" class="form-control" required>{{ $testcase->expected_result }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="actual_result{{$testcase->id}}">Edit Actual Result</label>
                                                            <textarea id="actual_result{{$testcase->id}}" name="actual_result" class="form-control" required>{{ $testcase->actual_result }}</textarea>
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
        </div>
    </div>

    <div class="modal fade" id="addTestCaseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Test Case</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('home.addTestCase') }}" method="post" >
                        @csrf
                        <div class="mb-3">
                            <label for="test_domain" class="form-label">Test Case Domain</label>
                            <input type="text" class="form-control" id="test_domain" name="test_domain" required>
                        </div>
                        <div class="mb-3">
                            <label for="module_name" class="form-label">Module Name</label>
                            <input type="text" class="form-control" id="module_name" name="module_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="test_description" class="form-label">Test Description</label>
                            <textarea class="form-control" id="test_description" name="test_description" required> </textarea>
                        </div>
                        <div class="mb-3">
                            <label for="test_case_type" class="form-label">Test Case Type</label>
                            <select class="form-select" id="test_case_type" name="test_case_type" required>
                                <option value="1">True</option>
                                <option value="0">False</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="test_step" class="form-label">Test Case Step</label>
                            <textarea class="form-control" id="test_step" name="test_step" required> </textarea>
                        </div>
                        <div class="mb-3">
                            <label for="test_data" class="form-label">Test Data</label>
                            <textarea class="form-control" id="test_data" name="test_data" required> </textarea>
                        </div>
                        <div class="mb-3">
                            <label for="expected_result" class="form-label">Expected Result</label>
                            <input type="text" class="form-control" id="expected_result" name="expected_result" required>
                        </div>
                        <div class="mb-3">
                            <label for="actual_result" class="form-label">Actual Result</label>
                            <input type="text" class="form-control" id="actual_result" name="actual_result" required>
                        </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
