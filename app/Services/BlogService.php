<?php

namespace App\Services;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BlogService
{

    public function updateBlogImage($request, $blog)
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

    public function saveBlogPost($editBlog, $request, bool $update = false): void
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
