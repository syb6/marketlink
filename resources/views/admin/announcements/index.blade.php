@extends('layouts.admin')
@section('page_title', 'Platform Announcements')

@section('content')

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-ml border-0 p-4 sticky-top" style="top: 100px;">
            <h5 class="fw-bold mb-4">Post Announcement</h5>
            <form action="{{ route('admin.announcements.store') }}" method="POST" class="form-ml">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Title *</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required placeholder="Short summary">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Message Content *</label>
                    <textarea class="form-control @error('body') is-invalid @enderror" name="body" rows="4" required placeholder="Detailed message to display to users">{{ old('body') }}</textarea>
                    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Message Type</label>
                    <select class="form-select @error('type') is-invalid @enderror" name="type" required>
                        <option value="info">Info (Blue)</option>
                        <option value="success">Success (Green)</option>
                        <option value="warning">Warning (Yellow/Red)</option>
                    </select>
                </div>
                
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" role="switch" id="publish_now" name="publish_now" value="1" checked>
                    <label class="form-check-label fw-bold" for="publish_now">Publish immediately</label>
                </div>
                
                <button type="submit" class="btn btn-primary-ml w-100 justify-content-center">Create Announcement</button>
            </form>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card-ml border-0 p-0 overflow-hidden">
            <div class="p-4 border-bottom">
                <h6 class="fw-bold mb-0">Announcement History</h6>
            </div>
            
            <div class="table-responsive">
                <table class="table table-ml mb-0 border-0 shadow-none">
                    <thead>
                        <tr>
                            <th class="ps-4">Title / Content</th>
                            <th>Type</th>
                            <th>Status / Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                            <tr>
                                <td class="ps-4 py-3" style="max-width: 300px;">
                                    <div class="fw-bold text-dark mb-1">{{ $announcement->title }}</div>
                                    <p class="small text-muted mb-0 text-truncate">{{ $announcement->body }}</p>
                                </td>
                                <td>
                                    @if($announcement->type == 'info')
                                        <span class="badge bg-primary">Info</span>
                                    @elseif($announcement->type == 'success')
                                        <span class="badge bg-success">Success</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Warning</span>
                                    @endif
                                </td>
                                <td>
                                    @if($announcement->published_at)
                                        <span class="small fw-bold text-success d-block mb-1">Published</span>
                                        <span class="small text-muted">{{ $announcement->published_at->format('M d, Y') }}</span>
                                    @else
                                        <span class="small fw-bold text-secondary d-block mb-1">Draft</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        @if(!$announcement->published_at)
                                            <form action="{{ route('admin.announcements.publish', $announcement) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Publish Now"><i class="bi bi-broadcast"></i></button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Delete this announcement?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">No announcements created yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $announcements->links('pagination::bootstrap-5') }}</div>
    </div>
</div>

@endsection
