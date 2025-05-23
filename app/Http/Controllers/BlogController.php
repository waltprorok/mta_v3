<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogImageRequest;
use App\Models\Blog;
use App\Services\BlogService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class BlogController extends Controller
{
    protected $blogLimit = 6;

    /**
     * @var BlogService
     */
    private $blogService;

    /**
     * @param BlogService $blogService
     */
    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

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
            ->select(['id', 'author_id', 'image', 'title', 'slug', 'released_on', 'created_at', 'updated_at'])
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
            $this->blogService->saveBlogPost($blog, $request);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    public function show(Blog $blog): View
    {
        return view('blog.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        return response()->json($blog);
    }

    public function update(StoreBlogPostRequest $request, Blog $blog): JsonResponse
    {
        try {
            $this->blogService->saveBlogPost($blog, $request, true);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([], Response::HTTP_CREATED);
    }

    public function updateImage(UpdateBlogImageRequest $request, Blog $blog): JsonResponse
    {
        try {
            $blog = $this->blogService->updateBlogImage($request, $blog);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json([], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['image' => $blog->image], Response::HTTP_CREATED);
    }

    /**
     * @throws Exception
     */
    public function destroy(Blog $blog): JsonResponse
    {
        $blog->delete();

        return response()->json();
    }
}
