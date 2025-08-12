@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ __($module_title) }} @endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>{{ __($module_title) }} {{ __($module_action) }}</h4>
        <a href="{{ route('backend.role.create') }}" class="btn btn-primary">{{ __('Create Role') }}</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th class="text-end">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                <tr id="role_{{ $role->id }}">
                    <td>{{ $role->title }}</td>
                    <td>{{ $role->name }}</td>
                    <td class="text-end">
                        <a href="{{ route('backend.role.edit', $role) }}" class="btn btn-sm btn-warning">{{ __('Edit') }}</a>
                        <button onclick="deleteRole({{ $role->id }})" class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $roles->links() }}
    </div>
</div>

<script>
function deleteRole(id) {
    if(!confirm('Are you sure to delete this role?')) return;

    let url = "{{ route('backend.role.destroy', ':id') }}".replace(':id', id);

    $.ajax({
        url: url,
        type: 'DELETE',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(response) {
            if(response.status) {
                $('#role_' + id).fadeOut();
                alert(response.message);
            } else {
                alert(response.message || 'Failed to delete.');
            }
        },
        error: function() {
            alert('Something went wrong!');
        }
    });
}
</script>
@endsection
