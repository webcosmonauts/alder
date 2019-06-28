@extends('alder::layouts.main')

@section('scripts-body')
    <script>
		 $(document).ready(function () {

			 $('input, select, textarea').on("change", function () {
				 $("#message").attr("hidden", true);
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

				 var message = $("#message");

				 $.ajax({
					 url: "{{ route("alder.roots.update")}}",
					 data: {
						 param: name,
						 value: value,
						 _token: $('[name=_token]').val()
					 },
					 method: "PUT",

					 success: function (response) {

						 if (message.length) {
							 message.find("span").html(response.message);
							 message.addClass("alert-" + response["alert-type"]).removeAttr("hidden");
						 } else {


							 var html = "<div id=\"message\" class=\"alert position-absolute alert-" + response["alert-type"] + " mb-4\"  style=\"top:30px; right: 30px;\">\n" +
								 "        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
								 "            <i class=\"material-icons\">close</i>\n" +
								 "        </button>\n" +
								 "\n" +
								 "        <span>" + response.message + "</span>\n" +
								 "    </div>";

							 $("#page-heading").after(html);
						 }
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
    <div id="page-heading" class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Roots</h1>
        @include('alder::components.locale-switcher')
    </div>


    <div id="message" class="alert mb-4 position-absolute" style="top:30px; right: 30px;" hidden>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <i class="material-icons">close</i>
        </button>

        <span></span>
    </div>

    @foreach($root_types as $root_type)
        <div class="card shadow mb-4">
            <a href="#collapseCard{{ Str::camel($root_type->slug) }}" class="d-block card-header collapsed 	 py-3"
               data-toggle="collapse" role="button" aria-expanded="true"
               aria-controls="collapseCard{{ Str::camel($root_type->name) }}">
                <h6 class="m-0 font-weight-bold text-primary">{{ __("alder::root_types.$root_type->slug") }}</h6>
            </a>
            <div class="collapse" id="collapseCard{{ Str::camel($root_type->slug) }}" style="">
                <div class="card-body">
                    <div class="row">
                        @foreach($root_type->roots as $root)
                            @if ($root->is_visible)
                                @if($root->slug == 'index-page')
                                    <div class="col-xl-4 col-lg-6">
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
                                    </div>
                                @else
                                    <div class="col-xl-4 col-lg-6 d-flex align-items-center">
                                        @include('alder::components.root_input', $root)
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    @endforeach
@endsection
