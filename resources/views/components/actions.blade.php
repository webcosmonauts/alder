@if(isset($leaf->leaf_type->slug) && !empty($leaf->leaf_type->slug) && $preview !== false)
    @switch($leaf->leaf_type->slug)
        @case("posts")
        <a href="{{ "/".$leaf->id}}" class="btn btn-sm btn-success btn-icon-split ml-3">
        <span class="icon text-white-50">
            <i class="fas fa-location-arrow"></i>
        </span>
            <span class="text">{{ __('alder::generic.preview') }}</span>
        </a>
        @break

        @case("pages")
        <a href="{{ "/".$leaf->id}}" class="btn btn-sm btn-success btn-icon-split ml-3">
        <span class="icon text-white-50">
            <i class="fas fa-location-arrow"></i>
        </span>
            <span class="text">{{ __('alder::generic.preview') }}</span>
        </a>
        @break

        @default

    @endswitch
@endif
<a href="{{ route("alder.$route.show", $leaf->id) }}" class="btn btn-sm btn-primary btn-icon-split ml-3">
    <span class="icon text-white-50">
        <i class="fas fa-book-open"></i>
    </span>
    <span class="text">{{ __('alder::generic.read') }}</span>
</a>

<!-- TODO if can edit -->
<a href="{{ route("alder.$route.edit", $leaf->id) }}" class="btn btn-sm btn-warning btn-icon-split ml-3">
    <span class="icon text-white-50">
        <i class="fas fa-edit"></i>
    </span>
    <span class="text">{{ __('alder::generic.edit') }}</span>
</a>

<!-- TODO if can delete -->
<a href="{{ route("alder.$route.destroy", $leaf->id) }}" class="btn btn-sm btn-danger btn-icon-split ml-3">
    <span class="icon text-white-50">
        <i class="fas fa-trash-alt"></i>
    </span>
    <span class="text">{{ __('alder::generic.delete') }}</span>
</a>
