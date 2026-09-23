@extends('layouts.admin')
@section('page_title', 'Product Categories')

@section('content')

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-ml border-0 p-4 sticky-top" style="top: 100px;">
            <h5 class="fw-bold mb-4">Add Category</h5>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="form-ml">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Category Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Bootstrap Icon Class</label>
                    <input type="text" class="form-control" name="icon" value="{{ old('icon') }}" placeholder="e.g. apple, basket">
                    <div class="form-text small"><a href="https://icons.getbootstrap.com/" target="_blank">Browse icons</a>. Omit the 'bi-' prefix.</div>
                </div>
                <button type="submit" class="btn btn-primary-ml w-100 justify-content-center">Create Category</button>
            </form>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card-ml border-0 p-0 overflow-hidden">
            <div class="p-4 border-bottom">
                <h6 class="fw-bold mb-0">Existing Categories</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-ml mb-0 border-0 shadow-none">
                    <thead>
                        <tr>
                            <th class="ps-4">Icon</th>
                            <th>Name</th>
                            <th>Products Count</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="ps-4 text-muted fs-5">
                                    @if($category->icon)
                                        <i class="bi bi-{{ $category->icon }}"></i>
                                    @else
                                        <i class="bi bi-tag"></i>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $category->name }}</td>
                                <td>{{ $category->products_count }} products</td>
                                <td>
                                    <form action="{{ route('admin.categories.toggle', $category) }}" method="POST">
                                        @csrf
                                        <div class="form-check form-switch m-0 d-inline-block">
                                            <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()" {{ $category->is_active ? 'checked' : '' }} title="Toggle Status">
                                        </div>
                                    </form>
                                </td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category? Products in this category might be affected.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No categories created yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
