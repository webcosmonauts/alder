<a href="{{ route("alder.$route.show", $param) }}" class="btn btn-primary btn-icon-split ml-3">
    <span class="icon text-white-50">
        <i class="fas fa-book-open"></i>
    </span>
    <span class="text">{{ __('alder::generic.read') }}</span>
</a>

<!-- TODO if can edit -->
<a href="{{ route("alder.$route.edit", $param) }}" class="btn btn-warning btn-icon-split ml-3">
    <span class="icon text-white-50">
        <i class="fas fa-edit"></i>
    </span>
    <span class="text">{{ __('alder::generic.edit') }}</span>
</a>

<!-- TODO if can delete -->
<a href="{{ route("alder.$route.destroy", $param) }}" class="btn btn-danger btn-icon-split ml-3">
    <span class="icon text-white-50">
        <i class="fas fa-trash-alt"></i>
    </span>
    <span class="text">{{ __('alder::generic.delete') }}</span>
</a>