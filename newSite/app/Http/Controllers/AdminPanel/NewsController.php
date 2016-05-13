<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use AIBattle\News;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class NewsController extends Controller
{
    //

    public function showNews() {
        return view('adminPanel/news/news', ['news' => News::orderBy('id', 'desc')->simplePaginate(5)]);
    }
    
    public function showCreateNewsForm() {
        return view('adminPanel/news/newsForm', ['mode' => 'create', 'newsCount' => count(News::all()) + 1]);
    }

    public function showNewsById($id) {
        $news = News::findOrFail($id);
        return view('adminPanel/news/showNews', ['news' => $news]);
    }

    public function showEditNewsForm($id) {
        $news = News::findOrFail($id);
        return view('adminPanel/news/newsForm', ['mode' => "edit", 'news' => $news]);
    }

    public function createNews(Request $request) {

        $this->validate($request, [
            'title' => 'required',
            'newsText' => 'required',
            'datetimepicker' => 'required',
        ]);

        // news is valid - push it to DB

        $news = new News();

        $news->header = $request->input('title');
        $news->text = $request->input('newsText');
        $news->date = Carbon::createFromFormat('d/m/Y', $request->input('datetimepicker'));

        $news->save();

        return redirect('adminPanel/news');
    }

    public function editNews(Request $request, $id) {

        if ($request->has('update')) {

            $this->validate($request, [
                'title' => 'required',
                'newsText' => 'required',
                'datetimepicker' => 'required',
            ]);

            $news = News::findOrFail($id);

            $news->header = $request->input('title');
            $news->text = $request->input('newsText');
            $news->date = Carbon::createFromFormat('d/m/Y', $request->input('datetimepicker'));

            $news->save();

            return redirect('adminPanel/news');

        } else if ($request->has('delete')) {
                
            $news = News::findOrFail($id);
            $news->delete();

            return redirect('adminPanel/news');
        } else
            abort(404);

    }
}
