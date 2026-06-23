<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;

class CommentController extends Controller
{
    public function index(Issue $issue)
    {
        $comments = $issue->comments()->latest()->paginate(5);
        return response()->json($comments);
    }

    public function store(StoreCommentRequest $request, Issue $issue)
    {
        $comment = $issue->comments()->create($request->validated());
        return response()->json([
            'success' => true,
            'comment' => $comment
        ]);
    }

    public function update(StoreCommentRequest $request, Issue $issue, Comment $comment)
    {
        $comment->update($request->validated());
        return response()->json([
            'success' => true,
            'comment' => $comment
        ]);
    }

    public function destroy(Issue $issue, Comment $comment)
    {
        $comment->delete();
        return response()->json(['success' => true]);
    }
}