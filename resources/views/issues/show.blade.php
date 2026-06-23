@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">{{ $issue->title }}</h1>
<small class="text-muted">Project: <a href="{{ route('projects.issues.index', $project) }}">{{ $project->name }}</a></small>    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('projects.issues.edit', [$project, $issue]) }}" class="btn btn-outline-secondary">Edit</a>
        <form action="{{ route('projects.issues.destroy', [$project, $issue]) }}" method="POST" onsubmit="return confirm('Are you sure?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">Delete</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <p>{{ $issue->description ?? 'No description provided.' }}</p>
                <div class="d-flex gap-2">
                    <span class="badge badge-{{ $issue->status }}">{{ $issue->status }}</span>
                    <span class="badge badge-{{ $issue->priority }}">{{ $issue->priority }}</span>
                    @if($issue->due_date)
                        <span class="badge bg-secondary">Due: {{ $issue->due_date }}</span>
                    @endif
                </div>
                @if($issue->users->count() > 0)
                    <div class="mt-2">
                        <strong>Assigned To:</strong>
                        @foreach($issue->users as $user)
                            <span class="badge bg-secondary">{{ $user->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Comments Section --}}
        <div class="card mb-4">
            <div class="card-header"><h5>Comments</h5></div>
            <div class="card-body">
                <div id="comments-list" class="mb-4"></div>
                <div id="load-more-wrap" class="text-center mb-3"></div>

                <h6>Add a Comment</h6>
                <div id="comment-errors" class="alert alert-danger d-none"></div>
                @auth
                    <p class="text-muted">Commenting as <strong>{{ auth()->user()->name }}</strong></p>
                @else
                    <div class="mb-3">
                        <label class="form-label">Your Name *</label>
                        <input type="text" id="author_name" class="form-control" placeholder="Your name">
                    </div>
                @endauth
                <div class="mb-3">
                    <label class="form-label">Comment *</label>
                    <textarea id="comment_body" class="form-control" rows="3" placeholder="Write a comment..."></textarea>
                </div>
                <button id="submit-comment" class="btn btn-primary">Post Comment</button>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Tags Section --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tags</h5>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tagsModal">Manage</button>
            </div>
            <div class="card-body">
                <div id="current-tags">
                    @foreach($issue->tags as $tag)
                        <span class="badge me-1" style="background-color: {{ $tag->color ?? '#6c757d' }}" id="tag-{{ $tag->id }}">
                            {{ $tag->name }}
                            <span class="ms-1" style="cursor:pointer" onclick="detachTag({{ $tag->id }})">×</span>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tags Modal --}}
<div class="modal fade" id="tagsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Tags</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Click a tag to attach/detach it.</p>
                @foreach($tags as $tag)
                    <span class="badge me-1 mb-1 p-2" style="background-color: {{ $tag->color ?? '#6c757d' }}; cursor:pointer"
                        id="modal-tag-{{ $tag->id }}"
                        onclick="toggleTag({{ $tag->id }})">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const issueId = {{ $issue->id }};
    const attachedTags = @json($issue->tags->pluck('id'));
    let currentPage = 1;
    let lastPage = 1;

    function loadComments(page = 1) {
        fetch(`/issues/${issueId}/comments?page=${page}`)
            .then(r => r.json())
            .then(data => {
                lastPage = data.last_page;
                const list = document.getElementById('comments-list');
                if (page === 1) list.innerHTML = '';

                if (data.data.length === 0 && page === 1) {
                    list.innerHTML = '<p class="text-muted">No comments yet.</p>';
                }

                data.data.forEach(comment => {
                    list.innerHTML += commentHTML(comment);
                });

                const loadMoreWrap = document.getElementById('load-more-wrap');
                if (currentPage < lastPage) {
                    loadMoreWrap.innerHTML = `<button class="btn btn-outline-secondary btn-sm" onclick="loadMore()">Load More</button>`;
                } else {
                    loadMoreWrap.innerHTML = '';
                }
            });
    }

    function loadMore() {
        currentPage++;
        loadComments(currentPage);
    }

    function commentHTML(comment) {
        return `
            <div class="border rounded p-3 mb-2" id="comment-${comment.id}">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>${comment.author_name}</strong>
                        <small class="text-muted ms-2">${new Date(comment.created_at).toLocaleString()}</small>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-secondary" onclick="editComment(${comment.id}, '${comment.author_name}', \`${comment.body}\`)">Edit</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteComment(${comment.id})">Delete</button>
                    </div>
                </div>
                <p class="mb-0 mt-1" id="comment-body-${comment.id}">${comment.body}</p>
                <div id="edit-form-${comment.id}" class="d-none mt-2">
                    <input type="text" class="form-control mb-2" id="edit-author-${comment.id}">
                    <textarea class="form-control mb-2" id="edit-body-${comment.id}" rows="2"></textarea>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-primary" onclick="saveComment(${comment.id})">Save</button>
                        <button class="btn btn-sm btn-secondary" onclick="cancelEdit(${comment.id})">Cancel</button>
                    </div>
                </div>
            </div>`;
    }

    document.getElementById('submit-comment').addEventListener('click', function() {
        const authorInput = document.getElementById('author_name');
        const author_name = authorInput ? authorInput.value : '{{ auth()->user()->name ?? "" }}';
        const body = document.getElementById('comment_body').value;
        const errorsDiv = document.getElementById('comment-errors');

        fetch(`/issues/${issueId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ author_name, body })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                errorsDiv.classList.add('d-none');
                const list = document.getElementById('comments-list');
                if (list.innerHTML.includes('No comments')) list.innerHTML = '';
                list.insertAdjacentHTML('afterbegin', commentHTML(data.comment));
                if (authorInput) authorInput.value = '';
                document.getElementById('comment_body').value = '';
            }
        })
        .catch(() => {
            errorsDiv.classList.remove('d-none');
            errorsDiv.innerHTML = 'Please fill in all fields correctly.';
        });
    });

    function toggleTag(tagId) {
        const isAttached = document.getElementById(`tag-${tagId}`);
        if (isAttached) {
            detachTag(tagId);
        } else {
            attachTag(tagId);
        }
    }

    function attachTag(tagId) {
        fetch(`/issues/${issueId}/tags/${tagId}/attach`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) location.reload();
        });
    }

    function detachTag(tagId) {
        fetch(`/issues/${issueId}/tags/${tagId}/detach`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const tag = document.getElementById(`tag-${tagId}`);
                if (tag) tag.remove();
            }
        });
    }

    function deleteComment(commentId) {
        if (!confirm('Delete this comment?')) return;
        fetch(`/issues/${issueId}/comments/${commentId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`comment-${commentId}`).remove();
            }
        });
    }

    function editComment(commentId, authorName, body) {
        document.getElementById(`edit-form-${commentId}`).classList.remove('d-none');
        document.getElementById(`comment-body-${commentId}`).classList.add('d-none');
        document.getElementById(`edit-author-${commentId}`).value = authorName;
        document.getElementById(`edit-body-${commentId}`).value = body;
    }

    function cancelEdit(commentId) {
        document.getElementById(`edit-form-${commentId}`).classList.add('d-none');
        document.getElementById(`comment-body-${commentId}`).classList.remove('d-none');
    }

    function saveComment(commentId) {
        const author_name = document.getElementById(`edit-author-${commentId}`).value;
        const body = document.getElementById(`edit-body-${commentId}`).value;

        fetch(`/issues/${issueId}/comments/${commentId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ author_name, body })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`comment-body-${commentId}`).textContent = data.comment.body;
                cancelEdit(commentId);
            }
        });
    }

    loadComments();
</script>
@endsection