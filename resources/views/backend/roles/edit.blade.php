@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h4>{{ __('Edit Role') }}</h4>

        <form method="POST" action="{{ route('backend.role.update', $role) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title">{{ __('Role Title') }}</label>
                <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $role->title) }}">
                @error('title')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="name">{{ __('Role Name') }}</label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $role->name) }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
            <a href="{{ route('backend.role.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
        </form>
    </div>
</div>

<script>
document.getElementById('title').addEventListener('input', function() {
    let val = this.value.toLowerCase()
        .replace(/[^a-z0-9]+/g, '_')
        .replace(/^_+|_+$/g, '');
    let nameInput = document.getElementById('name');
    if (!nameInput.dataset.edited) {
        nameInput.value = val;
    }
});

document.getElementById('name').addEventListener('input', function() {
    this.dataset.edited = true;
});
</script>
@endsection
