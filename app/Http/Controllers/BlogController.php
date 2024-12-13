<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogImageRequest;
use App\Models\Blog;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BlogController extends Controller
{
    protected $blogLimit = 6;

    /**
     * Marketing blog list page
     * @return View
     */
    public function index(): View
    {
        $blogs = Blog::query()
            ->with('author:id,first_name,last_name')
            ->published()
            ->latestFirst()
            ->paginate($this->blogLimit)
            ->onEachSide(3);

        return view('blog.index')->with('blogs', $blogs);
    }

    /**
     * Admin list of blog posts
     */
    public function list(): JsonResponse
    {
        $blogs = Blog::query()
            ->select('id', 'author_id', 'image', 'title', 'slug', 'released_on', 'created_at', 'updated_at')
            ->with('author:id,first_name,last_name')
            ->latestFirst()
            ->published()
            ->get();

        return response()->json($blogs);
    }

    public function store(StoreBlogPostRequest $request)
    {
        try {
            $blog = new Blog();
            $this->saveBlogPost($blog, $request);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    public function show(string $slug): View
    {
        $blog = Blog::query()->where('slug', $slug)->firstOrFail();

        return view('blog.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        return response()->json($blog);
    }

    public function update(StoreBlogPostRequest $request, Blog $blog): JsonResponse
    {
        try {
            $this->saveBlogPost($blog, $request, true);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    public function updateImage(UpdateBlogImageRequest $request, Blog $blog): JsonResponse
    {
        try {
            $blog = $this->updateBlogImage($request, $blog);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['image' => $blog->image], Response::HTTP_CREATED);
    }

    public function destroy(Blog $id): JsonResponse
    {
        $id->delete();

        return response()->json();
    }

    private function updateBlogImage($request, $blog)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'MTA_' . date('Ymd_hms') . "." . $file->getClientOriginalExtension();
            Storage::disk('blog')->put($fileName, File::get($file));
            $blog->image = $fileName;
            $blog->update();
        }

        return $blog;
    }

    private function saveBlogPost($editBlog, $request, $update = false): void
    {
        $this->setBlogPost($editBlog, $request);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = 'MTA_' . date('Ymd_hms') . "." . $file->getClientOriginalExtension();
            Storage::disk('blog')->put($fileName, File::get($file));
            $editBlog->image = $fileName;
        }

        $update ? $editBlog->update() : $editBlog->save();
    }

    private function setBlogPost(Blog $blog, $request): void
    {
        $releaseDate = Carbon::parse($request->get('released_on'))->format('Y-m-d');

        $blog->author_id = Auth::id();
        $blog->title = $request->get('title');
        $blog->slug = $request->get('slug');
        $blog->body = $request->get('body');
        $blog->released_on = $releaseDate . ' ' . $request->get('release_time');
    }
}
