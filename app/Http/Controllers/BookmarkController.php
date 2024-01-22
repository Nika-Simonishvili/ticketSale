<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bookmark\StoreBookmarkRequest;
use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BookmarkController extends Controller
{
    public function index(): JsonResponse
    {
        $authUserBookmarks = Bookmark::with('user', 'event')
            ->whereBelongsTo(\Auth::user())->get();

        return Response::success([
            'bookmarks' => BookmarkResource::collection($authUserBookmarks),
        ]);
    }

    public function store(StoreBookmarkRequest $request): JsonResponse
    {
        \Auth::user()->bookmarks()->create($request->validated());

        return Response::success([
            'message' => 'Event saved in your bookmarks.',
        ]);
    }

    public function destroy(Bookmark $bookmark)
    {
        $bookmark->delete();

        return Response::success([
            'message' => 'Bookmark deleted.',
        ]);
    }
}
