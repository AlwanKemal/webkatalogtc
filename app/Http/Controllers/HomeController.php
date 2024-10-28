<?php

namespace App\Http\Controllers;

use App\Exports\TestCasesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\TestCase;
use App\Models\SelectedTestCase;
use App\Models\MatchedSentence;
use App\Models\MatchedSentenceUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\PdfToText\Pdf;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Spatie\Regex\Regex;
use App\Models\Document;
use App\Models\TfidfResult;
use Web64\LaravelNlp\Facades\NLP;

class HomeController extends Controller
{

    public function index()
    {
        $data_testcase = TestCase::all();
        return view('homepage_view.home', compact('data_testcase'));
    }

    public function profile($id)
    {
        $user = User::find($id);
        return view('homepage_view.profil', compact('user'));
    }

    public function addTestCase(Request $request)
    {
        $data_testcase = new TestCase([
            'test_domain' => $request->get('test_domain'),
            'test_case_pattern' => $request->get('test_case_pattern'),
            'test_data' => $request->get('test_data'),
            'module_name' => $request->get('module_name'),
            'test_description' => $request->get('test_description'),
            'test_case_type' => $request->get('test_case_type'),
            'test_step' => $request->get('test_step'),          
            'expected_result' => $request->get('expected_result'),          
        ]);
        $data_testcase->save();   
        return redirect()->back()->with('success', 'Test Case saved!');
    }

    public function editTestCase(Request $request, $id)
    {
        $request->validate([
            'test_domain' => 'required',
            'test_case_pattern' => 'required',
            'test_data' => 'required',
            'module_name' => 'required',
            'test_description' => 'required',
            'test_case_type' => 'required',
            'test_step' => 'required',           
            'expected_result' => 'required',
        ]);

        $data_testcase = TestCase::findOrFail($id);
        $data_testcase->test_domain = $request->test_domain;
        $data_testcase->test_case_pattern = $request->test_case_pattern;
        $data_testcase->test_data = $request->test_data;
        $data_testcase->module_name = $request->module_name;
        $data_testcase->test_description = $request->test_description;
        $data_testcase->test_case_type = $request->test_case_type;
        $data_testcase->test_step = $request->test_step;     
        $data_testcase->expected_result = $request->expected_result;
        $data_testcase->save();   

        return redirect()->back()->with('success', 'Test Case Updated!');
    }

    public function deleteTestCase($id)
    {
        $data_testcase = TestCase::findOrFail($id);
        $data_testcase->delete();

        return redirect()->back()->with('success', 'Test Case Deleted!');
    }

    public function deleteAllTestCase()
    {
        $testCases = TestCase::all();
        foreach ($testCases as $testCase) {
            $testCase->delete();
        }
        return redirect()->back()->with('success', 'All test cases deleted successfully!');
    }

    public function saveSelectedTestCases(Request $request)
    {
        $request->validate([
            'selected_test_cases' => 'required|array|min:1',
            'user_id' => 'required',
        ]);
        $selectedTestCaseIds = $request->input('selected_test_cases');

        foreach ($selectedTestCaseIds as $testCaseId) {
            SelectedTestCase::create([
                'test_case_id' => $testCaseId,
                'user_id' => $request->user_id, 
            ]);
        }

        return redirect()->back()->with('success', 'Selected test cases saved successfully.');
    }

    public function showSelectedTestCases()
    {
        $selected_test_cases = SelectedTestCase::where('user_id', auth()->user()->id)->get();
        return view('homepage_view.selected', compact('selected_test_cases'));
    }

    public function deleteAllSelectedTestCases()
    {
        SelectedTestCase::where('user_id', auth()->user()->id)->delete();
        return redirect()->back()->with('success', 'All selected test cases deleted successfully.');
    }

    public function deleteSelectedTestCase($id)
    {
        SelectedTestCase::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Selected test case deleted successfully.');
    }

    public function showUser()
    {
        $users = User::where('role_id', 2)->get();
        return view('homepage_view.manage', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->role_id != 1) {
            $user->delete();
            return redirect()->back()->with('success', 'User has been deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Admin user cannot be deleted.');
        }
    }

    public function export(Request $request) 
    {
        $userId = auth()->user()->id;
        return Excel::download(new TestCasesExport($userId), 'selected_test_cases.xlsx');
    }

    public function showAnalyze()
    {
        $translatedSentences = Session::get('translatedSentences', []);
        return view('homepage_view.Analysis', compact('translatedSentences'));
    }
    
    public function analyzeSRS(Request $request)
    {
        $request->validate([
            'srs_file' => 'required|mimes:pdf',
            'product_name' => 'required|string',
            'roles_involved' => 'required|string',
        ]);

        $text = Pdf::getText($request->file('srs_file'));
        $product_name = $request->input('product_name');
        $roles_involved = $request->input('roles_involved');
        $roles_involved_array = explode(',', $roles_involved);

        foreach ($roles_involved_array as $role) {
            $keywords[] = preg_quote(trim($role), '/') . '\s+(?:harus|dapat|shall|should|may|will|must)';
        }

        $keywords = array_merge($keywords, [
            'sistem\s+(?:harus|dapat)', 
            'the\s+system\s+(?:shall|should|may|will|must)', 
            preg_quote($product_name, '/') . '\s+(?:harus|dapat|shall|should|may|will|must)', 
            'the\s+' . preg_quote($product_name, '/') . '\s+system\s+(?:shall|should|may|will|must|can)', 
            'sistem\s+' . preg_quote($product_name, '/') . '\s+(?:harus|dapat)',
        ]);

        $pattern = '/(' . implode('|', $keywords) . ')[^.!?]*(?:[.!?]|\n|$)/is';

        preg_match_all($pattern, $text, $matches);
        $matchedSentences = $matches[0] ?? [];

        $translatedSentences = [];
        foreach ($matchedSentences as $sentence) {
            $translatedSentence = NLP::translate($sentence, 'en', 'id');
            $translatedSentences[] = $translatedSentence;
        }

        \App\Models\MatchedSentence::truncate(); 

        foreach ($matchedSentences as $index => $match) {
            \App\Models\MatchedSentence::create([
                'sentence' => $match,
                'translated_sentence' => $translatedSentences[$index], 
            ]);
        }

        Session::put('translatedSentences', $translatedSentences);

        return redirect()->route('home.showAnalyze');
    }

    public function showAnalyzeUser()
    {
        $user_id = auth()->id(); 
        $translatedSentences = \App\Models\MatchedSentenceUser::where('user_id', $user_id)->pluck('translated_sentence');
        return view('homepage_view.AnalysisU', compact('translatedSentences'));
    }

    public function deleteAnalyzeResult(Request $request)
    {
        $user_id = auth()->id(); 
        MatchedSentenceUser::where('user_id', $user_id)->delete();
        $request->session()->put('translatedSentences', []);
        return redirect()->route('home.showAnalyzeUser')->with('success', 'Analysis results deleted successfully');
    }

    public function analyzeSRSU(Request $request)
    {
        $request->validate([
            'srs_file' => 'required|mimes:pdf',
            'product_name' => 'required|string',
            'roles_involved' => 'required|string',
        ]);

        $text = Pdf::getText($request->file('srs_file'));
        $product_name = strtolower($request->input('product_name'));
        $roles_involved = strtolower($request->input('roles_involved'));
        $text = strtolower($text);
        $roles_involved_array = explode(',', $roles_involved);

        foreach ($roles_involved_array as $role) {
            $keywords[] = preg_quote(trim($role), '/') . '\s+(?:harus|dapat|shall|should|may|will|must)';
        }

        $keywords = array_merge($keywords, [
            'sistem\s+(?:harus|dapat|akan)', 
            'the\s+system\s+(?:shall|should|may|will|must)', 
            preg_quote($product_name, '/') . '\s+(?:harus|dapat|shall|should|may|will|must)', 
            'the\s+' . preg_quote($product_name, '/') . '\s+system\s+(?:shall|should|may|will|must|can)', 
            'sistem\s+' . preg_quote($product_name, '/') . '\s+(?:harus|dapat|akan)',
        ]);

        $pattern = '/(' . implode('|', $keywords) . ')[^.!?]*(?:[.!?]|\n|$)/is';

        preg_match_all($pattern, $text, $matches);
        $matchedSentences = $matches[0] ?? [];

        $translatedSentences = [];
        foreach ($matchedSentences as $sentence) {
            $translatedSentence = NLP::translate($sentence, 'id', 'en');
            $translatedSentences[] = $translatedSentence;
        }

        $user_id = auth()->id(); 

        \App\Models\MatchedSentenceUser::where('user_id', $user_id)->delete(); 

        foreach ($matchedSentences as $index => $match) {
            \App\Models\MatchedSentenceUser::create([
                'sentence' => $match,
                'translated_sentence' => $translatedSentences[$index], 
                'user_id' => $user_id, 
            ]);
        }

        Session::put('translatedSentences', $translatedSentences);

        return redirect()->route('home.showAnalyzeUser');
    }

    public function saveSelectedTestCase(Request $request)
    {
        $request->validate([
            'selected_test_cases' => 'required|array|min:1',
            'user_id' => 'required',
        ]);
        $selectedTestCaseIds = $request->input('selected_test_cases');

        foreach ($selectedTestCaseIds as $testCaseId) {
            SelectedTestCase::create([
                'test_case_id' => $testCaseId,
                'user_id' => $request->user_id, 
            ]);
        }

        return redirect()->route('home.showAnalyzeUser');
    }

    public function searchSentence(Request $request)
    {
        $keyword = $request->input('keyword');
        $filteredTestCases = TestCase::where('test_description', 'like', '%' . $keyword . '%')->get();
        return view('homepage_view.testcasefiltered', compact('filteredTestCases', 'keyword'));
    }
}
