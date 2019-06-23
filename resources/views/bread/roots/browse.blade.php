@extends('alder::layouts.main')

@section('scripts-body')
    <script>
		 $(document).ready(function () {

			 $('input, select, textarea').on("change", function () {
				 var name = $(this).attr("name");

				 if ($(this).prop("type") === "checkbox") {
					 var checked = $('[name=' + name + ']:checked');
					 if (checked.length)
						 rootsUpdateQuery(name, "true");
					 else
						 rootsUpdateQuery(name, null);
				 }

				 else if ($(this).prop("type") === "radio") {
					 var checked = $('[name=' + name + ']:checked');
					 var arr = [];
					 checked.each(function () {
						 arr.push($(this).val());
					 });

					 rootsUpdateQuery(name, arr);
				 }

				 else {
					 rootsUpdateQuery(name, $(this).val());
				 }
			 });


			 function rootsUpdateQuery(name, value) {
				 $.ajax({
					 url: "{{ route("alder.roots.update")}}",
					 data: {
						 param: name,
						 value: value,
						 _token: $('[name=_token]').val()
					 },
					 method: "PUT",

					 success: function (msg) {
					 },

					 error: function (msg) {
					 }
				 });
			 }

		 });
    </script>
@endsection

@section('content')
    @csrf

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Roots</h1>
        @include('alder::components.locale-switcher')
    </div>

    @foreach($root_types as $root_type)
        <div class="card shadow mb-4">
            <a href="#collapseCard{{ Str::camel($root_type->slug) }}" class="d-block card-header py-3"
               data-toggle="collapse" role="button" aria-expanded="true"
               aria-controls="collapseCard{{ Str::camel($root_type->name) }}">
                <h6 class="m-0 font-weight-bold text-primary">{{ __("alder::root_types.$root_type->slug") }}</h6>
            </a>
            <div class="collapse show" id="collapseCard{{ Str::camel($root_type->slug) }}" style="">
                <div class="card-body">
                    @foreach($root_type->roots as $root)
                        @if($root->slug == 'index-page')
                            <label for="index-page">{{ $root->title }}</label>
                            <div class="input-group mb-4">
                                <select class="custom-select" name="{{ $root->slug }}" id="index-page"
                                        placeholder="{{ $root->title }}" aria-label="{{ $root->title }}"
                                        aria-describedby="{{ $root->title }}">
                                    @foreach($index_pages as $page)
                                        <option value="{{ $page->id }}
                                        {{ $root->value == $page->id ? 'selected' : '' }}">
                                            {{ $page->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            @include('alder::components.root_input', $root)
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@endsection
