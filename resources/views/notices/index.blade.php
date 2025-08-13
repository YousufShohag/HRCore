@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Notices</h2>
    <a href="{{ route('notices.create') }}" class="btn btn-primary mb-3">Create Notice</a>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mb-3">← Back to Dashboard</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Publish Date</th>
                <th>Expiry Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notices as $notice)
                <tr>
                    <td>{{ $notice->title }}</td>
                    <td>{{ Str::limit(strip_tags($notice->content), 100) }}</td>
                    <td>{{ \Carbon\Carbon::parse($notice->publish_date)->format('m/d/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($notice->expiry_date)->format('m/d/Y') }}</td>
                    <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editNoticeModal{{ $notice->id }}">Edit</button>
                    <form action="{{ route('notices.destroy', $notice->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this notice?')">Delete</button>
                    </form>
                    <a href="{{ route('notices.print', $notice->id) }}" target="_blank" class="btn btn-info btn-sm">Print</a>
                </td>
                    {{-- <td>
                        <a href="{{ route('notices.print', $notice->id) }}" target="_blank" class="btn btn-info btn-sm">Print</a>
                    </td> --}}
                </tr>

                <!-- Edit Modal -->
            <div class="modal fade" id="editNoticeModal{{ $notice->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('notices.update', $notice->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Notice</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Title</label>
                                    <input type="text" name="title" value="{{ $notice->title }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Content</label>
                                    <textarea name="content" class="form-control" rows="4" required>{{ $notice->content }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Publish Date</label>
                                    <input type="date" name="publish_date" value="{{ \Carbon\Carbon::parse($notice->publish_date)->format('Y-m-d') }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Expiry Date</label>
                                    <input type="date" name="expiry_date" value="{{ $notice->expiry_date ? \Carbon\Carbon::parse($notice->expiry_date)->format('Y-m-d') : '' }}" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Save Changes</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
