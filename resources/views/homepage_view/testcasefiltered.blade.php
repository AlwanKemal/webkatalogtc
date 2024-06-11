@extends('homelayout.layout')

@section('title')
    Test Case Filtered
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
                        <h3 class="mb-0">Search Test Case with Catalog</h3>
                        <div class="text-end">
                            <a href="{{ route('home.showAnalyzeUser') }}" class="btn btn-primary text-white"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                    </div>                   
                    <div class="card-body" style="overflow-x: auto;">
                        @if ($filteredTestCases->isEmpty())
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading text-center">No Data</h4>
                                <p class="text-center">No test cases were found to match</p>
                            </div>
                        @else
                            <form id="saveTestCaseForm" method="post" action="{{ route('home.saveSelectedTestCase') }}">
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
                                        @foreach($filteredTestCases as $testcase)
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
                                                <td>{{$testcase->test_step}}</td>                                               
                                                <td>{{$testcase->expected_result}}</td>                         
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-3">
                                    <button id="saveSelectedBtn" type="submit" class="btn btn-primary" disabled>Save Selected</button>
                                </div>  
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const checkboxes = document.querySelectorAll('input[name="selected_test_cases[]"]');
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', function() {
                const saveSelectedBtn = document.getElementById('saveSelectedBtn');
                const atLeastOneChecked = Array.from(checkboxes).some((checkbox) => checkbox.checked);
                saveSelectedBtn.disabled = !atLeastOneChecked;
            });
        });
    </script>
@endsection
