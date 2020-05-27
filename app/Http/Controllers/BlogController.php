<?php

namespace App\Http\Controllers;

use App\Blog;
use Auth;
use File;
use Storage;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BlogController extends Controller
{
    protected $blogLimit = 3;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $blogs = Blog::with('author')
            ->latestFirst()
            ->published()
            ->paginate($this->blogLimit)->onEachSide(3);

        return view('blog.index', compact('blogs'));
    }

    /**
     * @return Factory|View
     * Admin list of blog posts
     */
    public function list()
    {
        $blogs = Blog::with('author')
            ->latestFirst()
            ->published()
            ->get();

        return view('webapp.blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('webapp.blog.create');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'author_id' => 'required',
            'title' => 'required|string|max:100',
            'slug' => 'required|string|max:100',
            'body' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'released_on' => 'required',
        ]);

//        $blog = new Blog($request->all());

        $blog = new Blog();
        $blog->author_id = $request->get('author_id');
        $blog->title = $request->get('title');
        $blog->slug = $request->get('slug');
        $blog->body = $request->get('body');
        $blog->image = $request->get('updateImage');
        $blog->released_on = $request->get('released_on') . ' ' . $request->get('release_time');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'MTA_' . date('Ymd_hms') . "." . $file->getClientOriginalExtension();
            Storage::disk('blog')->put($fileName, File::get($file));
            $blog->image = $fileName;
        }

        $blog->save();

        return redirect(route('admin.blog.list'))->with('success', 'Your blog article has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param Blog $slug
     * @return Response
     */
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        return view('blog.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $update = Blog::findOrFail($id);
        return view('webapp.blog.edit')->with('update', $update);
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'author_id' => 'required',
            'title' => 'required|string|max:100',
            'slug' => 'required|string|max:100',
            'body' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'released_on' => 'required',
        ]);

        $editBlog = Blog::findOrFail($id);
        $editBlog->author_id = $request->get('author_id');
        $editBlog->title = $request->get('title');
        $editBlog->slug = $request->get('slug');
        $editBlog->body = $request->get('body');
        $editBlog->image = $request->get('updateImage');
        $editBlog->released_on = $request->get('released_on') . ' ' . $request->get('release_time');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'MTA_' . date('Ymd_hms') . "." . $file->getClientOriginalExtension();
            Storage::disk('blog')->put($fileName, File::get($file));
            $editBlog->image = $fileName;
            $editBlog->save();
        } else {
            $editBlog->save();
        }

        return back()->with('success', 'Your news article has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $deleteBlog = Blog::findOrFail($id);
        $deleteBlog->delete();

        return redirect(route('admin.blog.list'))->with('success', 'Your blog article has been deleted.');
    }

}
