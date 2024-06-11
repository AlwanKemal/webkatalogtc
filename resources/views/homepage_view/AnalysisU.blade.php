@extends('homelayout.layout')

@section('title')
    analyze-SRS
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Upload SRS Document</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('home.analyzeSRSU') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Nama produk/software</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="roles_involved" class="form-label">Role yang terlibat dalam produk/software (gunakan koma jika lebih dari satu)</label>
                                <input type="text" class="form-control" id="roles_involved" name="roles_involved" required>
                            </div>
                            <div class="mb-3">
                                <label for="srs_file" class="form-label">Select SRS Document (PDF)</label>
                                <input type="file" class="form-control" id="srs_file" name="srs_file" accept=".pdf" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload and Analyze</button>
                        </form>                                   
                    </div>
                </div>
            </div>
        </div>
        @if(isset($translatedSentences) && count($translatedSentences) > 0) 
            <div class="row justify-content-center mt-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center" style="font-size: 22px; font-weight: bold;">Analysis Result</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Translated Sentences</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($translatedSentences as $index => $translatedSentence)
                                            <tr>
                                                <td>{{ $translatedSentence }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <form method="POST" action="{{ route('home.deleteAnalyzeResult') }}">
                                @csrf
                                @method('DELETE')
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="submit" class="btn btn-danger">Clear</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
