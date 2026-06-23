@if($issues->isEmpty())
    <div class="alert alert-info">No issues found.</div>
@else
    <div class="card">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Tags</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($issues as $issue)
                <tr>
                    <td><a href="{{ route('projects.issues.show', [$project, $issue]) }}">{{ $issue->title }}</a></td>
                    <td><span class="badge badge-{{ $issue->status }}">{{ $issue->status }}</span></td>
                    <td><span class="badge badge-{{ $issue->priority }}">{{ $issue->priority }}</span></td>
                    <td>
                        @foreach($issue->tags as $tag)
                            <span class="badge" style="background-color: {{ $tag->color ?? '#6c757d' }}">{{ $tag->name }}</span>
                        @endforeach
                    </td>
                    <td>{{ $issue->due_date ?? '-' }}</td>
                    <td>
                        <a href="{{ route('projects.issues.edit', [$project, $issue]) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('projects.issues.destroy', [$project, $issue]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif