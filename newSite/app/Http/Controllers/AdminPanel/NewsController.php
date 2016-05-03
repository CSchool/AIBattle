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
        return view('adminPanel/news', ['news' => News::orderBy('id', 'desc')->simplePaginate(5), 'user' => Auth::user()]);
    }
    
    public function showCreateNewsForm() {
        return view('adminPanel/createNews', ['user' => Auth::user(), 'newsCount' => count(News::all()) + 1]);
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

    public function getEditNews($id) {
        $news = News::find($id);

        if (isset($news))
            return view('adminPanel/editNews', ['news' => $news]);
        else
            abort(404);
    }

    public function editNews(Request $request) {

        if ($request->input('update') !== null) {

            $this->validate($request, [
                'title' => 'required',
                'newsText' => 'required',
                'datetimepicker' => 'required',
            ]);

            $news = News::find($request->input('newsId'));

            if (isset($news)) {
                $news->header = $request->input('title');
                $news->text = $request->input('newsText');
                $news->date = Carbon::createFromFormat('d/m/Y', $request->input('datetimepicker'));

                $news->save();

                return redirect('adminPanel/news');
            }
            else
                abort(404);
        } else if ($request->input('delete') !== null) {
                
                $news = News::find($request->input('newsId'));
                if (isset($news))
                    $news->delete();

                return redirect('adminPanel/news');
        } else
            abort(404);

    }
}
