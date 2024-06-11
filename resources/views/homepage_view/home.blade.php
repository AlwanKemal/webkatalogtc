@extends('homelayout.layout')

@section('title')
    Home Page
@endsection

@section('content')
    <div class="container">
        <div class="row">   
            @if (Auth::user()->role_id == 1)       
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
                    <div class="d-flex justify-content-end px-3 pb-3">
                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addTestCaseModal">
                            Add Test Case
                        </button>
                        <form action="{{ route('home.deleteAllTestCase') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete all test cases?')">Delete All</button>
                        </form>
                    </div>
                    <div class="card">                   
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">List Test Case</h3>
                        </div>
                        <div class="card-body" style="overflow-x: auto;">                           
                            @if ($data_testcase->isEmpty())
                                <div class="alert alert-danger" role="alert">
                                    <h4 class="alert-heading text-center">No Data</h4>
                                    <p class="text-center">There is no data</p>
                                </div>
                            @else
                                <table class="table table-striped" id="tableTestCaseA">
                                    <thead>
                                        <tr>
                                            <th scope="col">Test Case Domain</th>
                                            <th scope="col">Pattern</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Module Name</th>
                                            <th scope="col">Test Description</th>
                                            <th scope="col">Test Case Type</th>
                                            <th scope="col">Test Case Step</th>                                
                                            <th scope="col">Expected Result</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data_testcase as $testcase)
                                        <tr>
                                            <td>{{$testcase->test_domain}}</td>
                                            <td>
                                                @if($testcase->test_case_pattern)
                                                    <span class="badge bg-info">Independent</span>
                                                @else
                                                    <span class="badge bg-warning">Dependent</span>
                                                @endif
                                            </td>
                                            <td>{{$testcase->test_data}}</td>
                                            <td>{{$testcase->module_name}}</td>
                                            <td>{{$testcase->test_description}}</td>
                                            <td>
                                                @if($testcase->test_case_type)
                                                    <span class="badge bg-success">Positive</span>
                                                @else
                                                    <span class="badge bg-danger">Negative</span>
                                                @endif
                                            </td>
                                            <td><pre>{{$testcase->test_step}}<pre></td>                                            
                                            <td>{{$testcase->expected_result}}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <form method="POST" action="{{ route('home.deleteTestCase', $testcase->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm me-2" onclick="return confirm('Are you sure you want to delete this?')"><i
                                                            class="fas fa-trash"></i></button>
                                                    </form>
                                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editTestCaseModal{{$testcase->id}}"><i 
                                                            class="fas fa-edit"></i></button>
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
                                                                <label for="test_case_pattern{{$testcase->id}}">Edit Pattern</label>
                                                                <select class="form-select" id="test_case_pattern{{$testcase->id}}" name="test_case_pattern" required>
                                                                    <option value="1" {{ $testcase->test_case_pattern ? 'selected' : '' }}>Independent</option>
                                                                    <option value="0" {{ !$testcase->test_case_pattern ? 'selected' : '' }}>Dependent</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="test_data{{$testcase->id}}">Edit Category</label>
                                                                <textarea id="test_data{{$testcase->id}}" name="test_data" class="form-control" required>{{ $testcase->test_data }}</textarea>
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
                                                                    <option value="1" {{ $testcase->test_case_type ? 'selected' : '' }}>Positive</option>
                                                                    <option value="0" {{ !$testcase->test_case_type ? 'selected' : '' }}>Negative</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="test_step{{$testcase->id}}">Edit Test Step</label>
                                                                <textarea id="test_step{{$testcase->id}}" name="test_step" class="form-control" required>{{ $testcase->test_step }}</textarea>
                                                            </div>                            
                                                            <div class="mb-3">
                                                                <label for="expected_result{{$testcase->id}}">Edit Expected Result</label>
                                                                <textarea id="expected_result{{$testcase->id}}" name="expected_result" class="form-control" required>{{ $testcase->expected_result }}</textarea>
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
            @else
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
                    <div class="d-flex justify-content-end px-3 pb-3">
                        <form method="GET" action="{{ route('home.filterByModule') }}" class="d-flex">
                            <div class="input-group" style="width: 170px; margin-right: 10px;">
                            <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                <select class="form-select" id="filterValue" name="filterValue">
                                    <option value=""></option>
                                    @foreach($distinctModules as $module)
                                        <option value="{{ $module }}">{{ $module }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('home') }}" class="btn btn-secondary ms-2">default</a>
                        </form>
                    </div>
                    <div class="card">  
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">List Test Case</h3>                     
                        <div class="input-group" style="width: 200px;">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                        </div>
                    </div>
                        <div class="card-body" style="overflow-x: auto;">
                            @if ($data_testcase->isEmpty())
                                <div class="alert alert-danger" role="alert">
                                    <h4 class="alert-heading text-center">No Data</h4>
                                    <p class="text-center">There is no data</p>
                                </div>
                            @else
                                <form method="post" action="{{ route('home.saveSelectedTestCases') }}">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <table class="table table-striped" id="tableTestCase">
                                        <thead>
                                            <tr> 
                                                <th scope="col">Select</th>                
                                                <th scope="col">Test Case Domain</th>
                                                <th scope="col">Pattern</th>
                                                <th scope="col">Category</th>
                                                <th scope="col">Module Name</th>
                                                <th scope="col">Test Description</th>
                                                <th scope="col">Test Case Type</th>
                                                <th scope="col">Test Case Step</th>                                               
                                                <th scope="col">Expected Result</th>                                            
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data_testcase as $testcase)
                                                <tr>    
                                                    <td>
                                                        <input type="checkbox" name="selected_test_cases[]" value="{{ $testcase->id }}">
                                                    </td>                                       
                                                    <td>{{$testcase->test_domain}}</td>
                                                    <td>
                                                        @if($testcase->test_case_pattern)
                                                            <span class="badge bg-info">Independent</span>
                                                        @else
                                                            <span class="badge bg-warning">Dependent</span>
                                                        @endif
                                                    </td>
                                                    <td>{{$testcase->test_data}}</td>
                                                    <td>{{$testcase->module_name}}</td>
                                                    <td>{{$testcase->test_description}}</td>
                                                    <td>
                                                        @if($testcase->test_case_type)
                                                            <span class="badge bg-success">Positive</span>
                                                        @else
                                                            <span class="badge bg-danger">Negative</span>
                                                        @endif
                                                    </td>
                                                    <td><pre>{{$testcase->test_step}}<pre></td>                                   
                                                    <td>{{$testcase->expected_result}}</td>                      
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary">Save Selected</button>
                                    </div>  
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if (Auth::user()->role_id != 1)
        <button id="scrollToBottomBtn" class="btn btn-primary btn-sm" style="position: fixed; bottom: 20px; right: 20px; display: none;">
            <i class="fas fa-chevron-down"></i> Scroll to Bottom
        </button>
    @endif

    {{-- Modal Add Test Case --}}
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
                            <label for="test_case_pattern" class="form-label">Pattern</label>
                            <select class="form-select" id="test_case_pattern" name="test_case_pattern" required>
                                <option value="1">Independent</option>
                                <option value="0">Dependent</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="test_data" class="form-label">Category</label>
                            <textarea class="form-control" id="test_data" name="test_data" required> </textarea>
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
                                <option value="1">Positive</option>
                                <option value="0">Negative</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="test_step" class="form-label">Test Case Step</label>
                            <textarea class="form-control" id="test_step" name="test_step" required> </textarea>
                        </div>                       
                        <div class="mb-3">
                            <label for="expected_result" class="form-label">Expected Result</label>
                            <input type="text" class="form-control" id="expected_result" name="expected_result" required>
                        </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#tableTestCaseA').DataTable();
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

            function checkBottom() {
                var tableHeight = $('#tableTestCase').height();
                var scrollPosition = $(window).scrollTop();
                var windowHeight = $(window).height();

                if (scrollPosition + windowHeight >= tableHeight) {
                    $('#scrollToBottomBtn').fadeOut();
                } else {
                    $('#scrollToBottomBtn').fadeIn();
                }
            }

            checkBottom();
            $(window).scroll(function () {
                checkBottom();
            });

            $('#scrollToBottomBtn').click(function () {
                var tableHeight = $('#tableTestCase').height();
                $('html, body').animate({ scrollTop: tableHeight }, 'slow');
            });
        });
    </script>

@endsection
