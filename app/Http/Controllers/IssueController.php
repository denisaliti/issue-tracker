<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\User;

class IssueController extends Controller
{
    public function index(Project $project)
    {
    $issues = $project->issues()
        ->with('tags', 'users')
        ->when(request('status'), fn($q) => $q->where('status', request('status')))
        ->when(request('priority'), fn($q) => $q->where('priority', request('priority')))
        ->when(request('tag'), fn($q) => $q->whereHas('tags', fn($q) => $q->where('tags.id', request('tag'))))
        ->when(request('search'), fn($q) => $q->where(function($q) {
            $q->where('title', 'like', '%'.request('search').'%')
              ->orWhere('description', 'like', '%'.request('search').'%');
        }))
        ->get();

    $tags = Tag::all();

    if (request()->ajax()) {
        $html = view('issues.partials.table', compact('project', 'issues'))->render();
        return response()->json(['html' => $html]);
    }

    return view('issues.index', compact('project', 'issues', 'tags'));
    }

   public function create(Project $project)
{
    $users = User::all();
    $tags = \App\Models\Tag::all();
    return view('issues.create', compact('project', 'users', 'tags'));
}

   public function store(StoreIssueRequest $request, Project $project)
{
    $issue = $project->issues()->create($request->validated());
    if ($request->has('members')) {
        $issue->users()->sync($request->members);
    }
    if ($request->has('tags')) {
        $issue->tags()->sync($request->tags);
    }
    return redirect()->route('projects.issues.index', $project)->with('success', 'Issue created successfully!');
}

    public function show(Project $project, Issue $issue)
    {
    $issue->load('tags', 'project', 'users');
    $tags = Tag::all();
    $users = User::all();
    return view('issues.show', compact('project', 'issue', 'tags', 'users'));
    }

   public function edit(Project $project, Issue $issue)
{
    $users = User::all();
    $tags = \App\Models\Tag::all();
    return view('issues.edit', compact('project', 'issue', 'users', 'tags'));
}

 public function update(UpdateIssueRequest $request, Project $project, Issue $issue)
{
    $issue->update($request->validated());
    $issue->users()->sync($request->members ?? []);
    $issue->tags()->sync($request->tags ?? []);
    return redirect()->route('projects.issues.show', [$project, $issue])->with('success', 'Issue updated successfully!');
}

    public function destroy(Project $project, Issue $issue)
    {
        $issue->delete();
        return redirect()->route('projects.issues.index', $project)->with('success', 'Issue deleted successfully!');
    }

    public function attachTag(Issue $issue, Tag $tag)
    {
        $issue->tags()->syncWithoutDetaching([$tag->id]);
        return response()->json(['success' => true, 'message' => 'Tag attached!']);
    }

    public function detachTag(Issue $issue, Tag $tag)
    {
        $issue->tags()->detach($tag->id);
        return response()->json(['success' => true, 'message' => 'Tag detached!']);
    }
    public function attachUser(Issue $issue, User $user)
    {
    $issue->users()->syncWithoutDetaching([$user->id]);
    return response()->json(['success' => true]);
    }

    public function detachUser(Issue $issue, User $user)
    {
    $issue->users()->detach($user->id);
    return response()->json(['success' => true]);
    }
}