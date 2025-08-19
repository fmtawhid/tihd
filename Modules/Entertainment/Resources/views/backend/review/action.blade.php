<div class="d-flex gap-2 align-items-center justify-content-end">

    @if(!$data->trashed())
        {{-- Edit --}}
        @hasPermission('edit_reviews')
        <a href="{{ route('backend.reviews.edit', $data->id) }}" 
           class="btn btn-primary-subtle btn-sm fs-4" 
           data-bs-toggle="tooltip" 
           title="{{ __('messages.edit') }}">
            <i class="ph ph-pencil align-middle"></i>
        </a>
        @endhasPermission

        {{-- Soft Delete (Trash) --}}
        @hasPermission('delete_reviews')
        <a href="{{ route('backend.reviews.destroy', $data->id) }}" 
           id="delete-{{ $module_name }}-{{ $data->id }}" 
           class="btn btn-secondary-subtle btn-sm fs-4" 
           data-type="ajax" 
           data-method="DELETE" 
           data-token="{{ csrf_token() }}" 
           data-bs-toggle="tooltip" 
           title="{{ __('messages.delete') }}" 
           data-confirm="{{ __('messages.are_you_sure?') }}">
            <i class="ph ph-trash align-middle"></i>
        </a>
        @endhasPermission

    @else
        {{-- Restore --}}
        @hasPermission('restore_reviews')
        <a class="btn btn-success-subtle btn-sm fs-4 restore-tax" 
           data-confirm-message="{{__('messages.are_you_sure_restore')}}" 
           data-success-message="{{trans('messages.restore_form', ['form' => 'Review'])}}" 
           href="{{ route('backend.reviews.restore', $data->id) }}" 
           data-bs-toggle="tooltip" 
           title="{{ __('messages.restore') }}">
            <i class="ph ph-arrow-clockwise align-middle"></i>
        </a>
        @endhasPermission

        {{-- Force Delete --}}
        @hasPermission('force_delete_reviews')
        <a href="{{ route('backend.reviews.force_delete', $data->id) }}" 
           id="delete-{{ $module_name }}-{{ $data->id }}" 
           class="btn btn-danger-subtle btn-sm fs-4" 
           data-type="ajax" 
           data-method="DELETE" 
           data-token="{{ csrf_token() }}" 
           data-bs-toggle="tooltip" 
           title="{{ __('messages.force_delete') }}" 
           data-confirm="{{ __('messages.are_you_sure?') }}">
            <i class="ph ph-trash align-middle"></i>
        </a>
        @endhasPermission
    @endif

</div>
