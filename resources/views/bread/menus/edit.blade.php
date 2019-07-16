@extends('alder::layouts.main')

<style>
    #myEditor .list-group-item {
        padding: 5px !important;
        border: 1px solid #ddd;
        margin-bottom: 10px;
        border-radius: 3px;
    }
</style>

@section('scripts-body')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
    <link rel="stylesheet" href="bootstrap-iconpicker/css/bootstrap-iconpicker.min.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>-->
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <script type="text/javascript" src="{{asset("vendor/menus/jquery-menu-editor.min.js")}}"></script>
    <script type="text/javascript"
            src="{{asset("vendor/menus/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js")}}"></script>
    <script type="text/javascript"
            src="{{asset("vendor/menus/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js")}}"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
		 jQuery(document).ready(function () {
			 /* =============== DEMO =============== */
			 // menu items
			 var arrayjson = '<?php echo $comtent ?>';
			 // icon picker options
			 var iconPickerOptions = {searchText: "Buscar...", labelHeader: "{0}/{1}"};
			 // sortable list options
			 var sortableListOptions = {
				 placeholderCss: {'background-color': "#cccccc"}
			 };

			 var editor = new MenuEditor('myEditor', {listOptions: sortableListOptions, iconPicker: iconPickerOptions});
			 editor.setForm($('#frmEdit'));
			 editor.setUpdateButton($('#btnUpdate'));

			 $('#btnReload').ready(function () {
				 editor.setData(arrayjson);
			 });

			 $('#myEditor').on('DOMSubtreeModified', function () {
				 var str = editor.getString();
				 $("#out").text(str);
			 });

			 $("#btnUpdate").click(function () {
				 if ($('#text').val() != '' && $('#href').val() != '') {
					 editor.update();
					 $('.edit_menu').css('display', 'none')
				 }

			 });

			 $('#btnAdd').click(function () {
				 editor.add();
			 });

			 $(document).on('click', '.btnEdit', function () {
				 $('.edit_menu').css('display', 'block')
			 });

			 $('.save_menu').click(function () {
				 $('.save_form_btn').submit();
			 });

			 $('.add_custom_link').click(function () {
				 if ($('.custom_link_text').val() != '' && $('.custom_link_url').val() != '') {
					 $('#text').attr('value', $('.custom_link_text').val());
					 $('#href').attr('value', 'http://' + $('.custom_link_url').val());
					 editor.add();
				 }
			 });


			 $(function () {
				 var dragged_object = '';
				 $('.textBlock').draggable({
					 start: function () {
						 dragged_object = $(this);
					 },
					 helper: 'clone'
				 });

				 $('.block2').droppable({
					 hoverClass: 'dropHere',
					 drop: function () {

						 var type = dragged_object.attr('data-type');
						 $('#text').attr('value', dragged_object.text().trim());

						 var typesPrefix = {
							 "pages": '/',
							 "posts": '/posty/',
							 "reports": '/reports/',
							 "post-tags": "/post-tags/",
							 "post-categories": "/posts/categories/"
						 };

						 $("#href").attr('value', typesPrefix[type] + dragged_object.attr('name'));

						 editor.add();
						 dragged_object = '';
					 }
				 });
			 });

			 /* ====================================== */

			 /** PAGE ELEMENTS **/
			 $('[data-toggle="tooltip"]').tooltip();
			 $.getJSON("https://api.github.com/repos/davicotico/jQuery-Menu-Editor", function (data) {
				 $('#btnStars').html(data.stargazers_count);
				 $('#btnForks').html(data.forks_count);
			 });
		 });
    </script>



@endsection

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $leaf_type->title }}</h1>
        @include('alder::components.locale-switcher')
    </div>
    <!-- tabs content -->
    <div class="tab-content" id="myTabContent">

        <!-- *** MAIN TAB *** -->
        <div class="tab-pane fade card shadow show active" id="main-section" role="tabpanel">
            <div class="row">
                <div class="col-lg-12">
                    <form class="save_form_btn"
                          action="{{ $edit ? route("alder.$leaf_type->slug.update", $menu->id) : route("alder.$leaf_type->slug.store") }}"
                          method="POST">
                        @csrf
                        {{$edit ? method_field('PUT') : method_field('POST')}}

                        <div style="padding: 10px 15px">
                            @foreach([__('alder::generic.title'), 'slug'] as $field)
                                <label for="{{ $field }}">{{ $field }}</label>
                                <div class="input-group mb-2">
                                    <input type="text" name="{{ $field}}" id="{{ $field }}" class="form-control"
                                           placeholder="{{ $field}}"
                                           data-{{$field}}="1"
                                           aria-label="{{ $field }}" aria-describedby="{{ $field }}"
                                           value="{{ $edit ? $menu->$field : '' }}">
                                </div>
                            @endforeach
                            <div class="card" style="display: none">
                                <div class="card-header">JSON Output
                                </div>
                                <div class="card-body">
                                    <div class="form-group"><textarea name="content" id="out" class="form-control"
                                                                      cols="50"
                                                                      rows="10">{{!$edit ?  : $menu->content}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                    <div class=" mb-4 border-top-0">
                        <div class="card-body">


                            <div class="row">
                                <div class="col-12 col-lg-4">
                                    @foreach($leaf_types as $single_leaf)
                                        @if(!empty($single_leaf))
                                            @if ($single_leaf->slug == 'menus')
                                            @elseif($single_leaf->slug == 'post-tags')
                                            @elseif($single_leaf->slug == 'contact-form-message')
                                            @else
                                                <div class="card shadow mb-0 mt-2">
                                                    <!-- Card Header - Accordion -->
                                                    <a href="#menu-{{$single_leaf->slug}}"
                                                       class="d-block card-header py-3 collapsed" data-toggle="collapse"
                                                       role="button" aria-expanded="false"
                                                       aria-controls="collapsemenu-{{$single_leaf->slug}}">
                                                        <h6 class="m-0 font-weight-bold text-primary">{{$single_leaf->slug}}
                                                            ({{count($single_leaf->leaves)}})
                                                        </h6>
                                                    </a>

                                                    <!-- Card Content - Collapse -->
                                                    <div class="collapse " id="menu-{{$single_leaf->slug}}">
                                                        <div class="card-body ">
                                                            @if(!empty($single_leaf->leaves))
                                                                <ul class="list-group mb-3 mb-2">
                                                                    @foreach($single_leaf->leaves as $singular)
                                                                        <li class="list-group-item textBlock"
                                                                            draggable="true"
                                                                            data-type="{{$single_leaf->slug}}"
                                                                            name="{{$singular->slug}}"
                                                                            style="border-bottom: 1px solid #ddd; margin-bottom: 10px;">
                                                                            {{$singular->title}}
                                                                        </li>

                                                                    @endforeach
                                                                </ul>
                                                                <a href="{{url('/alder/'.$single_leaf->slug.'/create')}}"
                                                                   class="btn btn-success btn-icon-split">
                                                                        <span class="icon text-white-50">
                                                                            <i class="fas fa-plus-circle"></i>
                                                                        </span>
                                                                    <span class="text">{{ $edit ? __('alder::generic.edit') : __('alder::generic.add_new') }}
                                                                        {{ lcfirst(__("alder::leaf_types.$leaf_type->slug.singular")) }}</span>


                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                    <div class="card shadow mb-0">
                                        <!-- Card Header - Accordion -->
                                        <a href="#menu-custom-link" class="d-block card-header py-3 collapsed"
                                           data-toggle="collapse" role="button" aria-expanded="false"
                                           aria-controls="collapsemenu-{{$single_leaf->slug}}">
                                            <h6 class="m-0 font-weight-bold text-primary">
                                                {{__('alder::generic.add_new_custom_link')}}
                                            </h6>
                                        </a>
                                        <!-- Card Content - Collapse -->
                                        <div class="collapse" id="menu-custom-link">
                                            <ul class="list-group mb-3">
                                                <li class="list-group-item custom-link-add-new">
                                                    <input type="text" class="form-control mb-1 custom_link_url"
                                                           placeholder="http://" required>
                                                    <input type="text" class="form-control mb-1 custom_link_text"
                                                           required placeholder="{{__('alder::generic.title')}}">

                                                    {{--                                                    <a href="" class="btn btn-success btn-icon-split">--}}
                                                    {{--                                                                        <span class="icon text-white-50">--}}
                                                    {{--                                                                            <i class="fas fa-plus-circle"></i>--}}
                                                    {{--                                                                        </span>--}}
                                                    {{--                                                        <span ></span>--}}
                                                    {{--                                                    </a>--}}
                                                    <button class="btn btn-success btn-icon-split add_custom_link">{{ __('alder::generic.add_new_custom_link') }}</button>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>

                                    <button type="submit" style=" margin-top: 30px"
                                            class="btn btn-success btn-icon-split mb-1 save_menu">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-save"></i>
                                        </span>
                                        <span class="text">{{ __('alder::generic.save') }}</span>
                                    </button>
                                </div>
                                <div class="col-12 col-lg-8">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-xl-8 col-lg-12">
                                                <div class="card mb-3 block2">
                                                    <div class="card-header block2"><h5 class="float-left">Menu</h5>

                                                    </div>
                                                    <div class="card-body">
                                                        <ul id="myEditor" class="sortableLists list-group">

                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-xl-4 col-lg-12 edit_menu" style="display: none">
                                                <div class="card border-primary mb-3">
                                                    <div class="card-header bg-primary text-white">{{__('alder::generic.edit_item')}}</div>
                                                    <div class="card-body">
                                                        <form id="frmEdit" class="form-horizontal"
                                                              class="col-12 col-lg-12">

                                                            <div class="form-group">
                                                                <label for="text">{{__('alder::generic.text')}}</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control item-menu "
                                                                           name="text" id="text" required
                                                                           placeholder="Text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="href">URL</label>
                                                                <input type="text" class="form-control item-menu"
                                                                       id="href" name="href" required placeholder="URL">
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="card-footer">
                                                        <button type="button" id="btnUpdate" class="btn btn-primary"
                                                                disabled><i
                                                                    class="fas fa-sync-alt"></i> {{__('alder::generic.update')}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- *** END MAIN TAB *** -->


    </div>



@endsection


