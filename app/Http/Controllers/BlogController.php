<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Http\Requests\StoreBlogPost;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BlogController extends Controller
{
    protected $blogLimit = 6;

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $blogs = Blog::with('author')
            ->latestFirst()
            ->published()
            ->paginate($this->blogLimit)
            ->onEachSide(3);

        return view('blog.index', compact('blogs'));
    }

    /**
     * @return View
     * Admin list of blog posts
     */
    public function list(): View
    {
        $blogs = Blog::with('author')
            ->published()
            ->latestFirst()
            ->get();

        return view('webapp.blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new blog post.
     *
     * @return View
     */
    public function create(): View
    {
        return view('webapp.blog.create');
    }

    /**
     * @param StoreBlogPost $request
     * @return RedirectResponse
     */
    public function store(StoreBlogPost $request): RedirectResponse
    {
        $blog = new Blog();

        $this->commitBlogPost($blog, $request);

        return redirect(route('admin.blog.list'))->with('success', 'Your blog article has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param Blog $slug
     * @return Application|Factory|View
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
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $update = Blog::findOrFail($id);

        return view('webapp.blog.edit')->with('update', $update);
    }

    /**
     * @param StoreBlogPost $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(StoreBlogPost $request, $id): RedirectResponse
    {
        $editBlog = Blog::findOrFail($id);

        $this->commitBlogPost($editBlog, $request);

        return back()->with('success', 'Your news article has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy(int $id)
    {
        $deleteBlog = Blog::findOrFail($id);
        $deleteBlog->delete();

        return redirect(route('admin.blog.list'))->with('success', 'Your blog article has been deleted.');
    }

    /**
     * @param Blog $blog
     * @param StoreBlogPost $request
     */
    public function setBlogPost(Blog $blog, StoreBlogPost $request): void
    {
        $blog->author_id = Auth::id();
        $blog->title = $request->get('title');
        $blog->slug = $request->get('slug');
        $blog->body = $request->get('body');
        $blog->image = $request->get('updateImage');
        $blog->released_on = $request->get('released_on') . ' ' . $request->get('release_time');
    }

    /**
     * @param $editBlog
     * @param StoreBlogPost $request
     */
    public function commitBlogPost($editBlog, StoreBlogPost $request): void
    {
        $this->setBlogPost($editBlog, $request);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'MTA_' . date('Ymd_hms') . "." . $file->getClientOriginalExtension();
            Storage::disk('blog')->put($fileName, File::get($file));
            $editBlog->image = $fileName;
        }
        
        $editBlog->save();
    }

}
