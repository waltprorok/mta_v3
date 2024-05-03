<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BlogController extends Controller
{
    protected $blogLimit = 6;

    /**
     * Display a listing of the resource.
     * @return View
     */
    public function index(): View
    {
        $blogs = Blog::query()
            ->with('author')
            ->published()
            ->latestFirst()
            ->paginate($this->blogLimit)
            ->onEachSide(3);

        return view('blog.index', compact('blogs'));
    }

    /**
     * Admin list of blog posts
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $blogs = BlogResource::collection(
            Blog::query()
                ->with('author')
                ->latestFirst()
                ->published()
                ->get()
        );

        return response()->json($blogs);
    }

    /**
     * Show the form for creating a new blog post.
     * @return View
     */
    public function create(): View
    {
        return view('webapp.admin.blog.create');
    }

    /**
     * @param StoreBlogPostRequest $request
     * @return RedirectResponse
     */
    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $blog = new Blog();

        $this->saveBlogPost($blog, $request);

        return redirect(route('admin.blog.list'))->with('success', 'Your blog article has been saved.');
    }

    /**
     * Display the specified resource.
     * @param $slug
     * @return View
     */
    public function show($slug): View
    {
        $blog = Blog::query()->where('slug', $slug)->firstOrFail();

        return view('blog.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Blog $id
     * @return View
     */
    public function edit(Blog $id): View
    {
        return view('webapp.admin.blog.edit')->with('blog', $id);
    }

    /**
     * @param StoreBlogPostRequest $request
     * @param Blog $id
     * @return RedirectResponse
     */
    public function update(StoreBlogPostRequest $request, Blog $id): RedirectResponse
    {
        $this->saveBlogPost($id, $request);

        return back()->with('success', 'Your news article has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Blog $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Blog $id): JsonResponse
    {
        $id->delete();

        return response()->json();
    }

    /**
     * @param $editBlog
     * @param StoreBlogPostRequest $request
     * @return void
     */
    private function saveBlogPost($editBlog, StoreBlogPostRequest $request): void
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

    /**
     * @param Blog $blog
     * @param StoreBlogPostRequest $request
     * @return void
     */
    private function setBlogPost(Blog $blog, StoreBlogPostRequest $request): void
    {
        $blog->author_id = Auth::id();
        $blog->title = $request->get('title');
        $blog->slug = $request->get('slug');
        $blog->body = $request->get('body');
        $blog->released_on = $request->get('released_on') . ' ' . $request->get('release_time');
    }
}
