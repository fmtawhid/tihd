@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h4>Role: {{ $role->name }}</h4>
        <p><strong>Permissions:</strong></p>
        @foreach ($role->permissions as $permission)
            <span class="badge bg-info">{{ $permission->name }}</span>
        @endforeach
        <br><br>
        <a href="{{ route('backend.role.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
