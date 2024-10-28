@extends('homelayout.layout')

@section('title')
    Selected Test Cases
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
                        <h3 class="mb-0">Selected Test Case</h3>
                        <div class="input-group" style="width: 200px;">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                        </div>
                    </div>
                    <div class="card-body" style="overflow-x: auto;">
                        @if ($selected_test_cases->isEmpty())
                            <div class="alert alert-warning" role="alert">
                                <h4 class="alert-heading text-center">No Selected Test Cases</h4>
                                <p class="text-center">There is no selected test cases.</p>
                            </div>
                        @else                           
                            <table class="table table-striped" id="tableTestCaseS">
                                <thead>
                                    <tr>
                                        <th scope="col">Test Case</th>
                                        <th scope="col">Pattern</th>
                                        <th scope="col">Actor</th>
                                        <th scope="col">Module Name</th>
                                        <th scope="col">Test Description</th>
                                        <th scope="col">Test Case Type</th>
                                        <th scope="col">Test Case Step</th>                                      
                                        <th scope="col">Expected Result</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($selected_test_cases as $selected_test_case)
                                        <tr>
                                            <td>{{ $selected_test_case->testCase->test_domain }}</td>
                                            <td>
                                                @if($selected_test_case->testCase->test_case_pattern)
                                                    <span class="badge bg-info">Independent</span>
                                                @else
                                                    <span class="badge bg-warning">Dependent</span>
                                                @endif
                                            </td>
                                            <td>{{ $selected_test_case->testCase->test_data }}</td>
                                            <td>{{ $selected_test_case->testCase->module_name }}</td>
                                            <td>{{ $selected_test_case->testCase->test_description }}</td>
                                            <td>
                                                @if($selected_test_case->testCase->test_case_type)
                                                    <span class="badge bg-success">Positive</span>
                                                @else
                                                    <span class="badge bg-danger">Negative</span>
                                                @endif
                                            </td>
                                            <td><pre>{{ $selected_test_case->testCase->test_step }}<pre></td>                                           
                                            <td>{{ $selected_test_case->testCase->expected_result }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('home.deleteSelectedTestCase', $selected_test_case->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this?')"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>                                 
                            <div class="row justify-content-end mt-3">
                                <div class="col-auto">
                                    <form method="POST" action="{{ route('home.deleteAllSelectedTestCases') }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete all selected test cases?')">Delete All</button>
                                    </form>
                                </div>
                                <div class="col-auto">
                                    <form method="POST" action="{{ route('home.export') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Export to XLS</button>
                                    </form>
                                </div>
                            </div>        
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button id="scrollToBottomBtn" class="btn btn-primary btn-sm" style="position: fixed; bottom: 20px; right: 20px; display: none;">
        <i class="fas fa-chevron-down"></i> Scroll to Bottom
    </button>

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

            function checkBottom() {
                var tableHeight = $('#tableTestCaseS').height();
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
                var tableHeight = $('#tableTestCaseS').height();
                $('html, body').animate({ scrollTop: tableHeight }, 'slow');
            });
        });
    </script>
@endsection
