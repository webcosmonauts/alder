@extends('alder::layouts.main')


@section('scripts-body')

    <!-- QUILL -->
    <link rel="stylesheet" href="{{asset('js/themes/snow.css')}}">
    <script src="{{asset('js/quill.min.js')}}"></script>

    <!-- LCM picker -->
    <script src="{{asset('vendor/LCM-picker/LCM-picker.js')}}"></script>
    <!-- LCM switcher -->
    <script src="{{asset('vendor/LCM-switcher/LCM-switcher.js')}}"></script>

    <!-- Page builder -->
    <link rel="stylesheet" href="{{asset('vendor/page-builder/page-builder.css')}}">
    <script src="{{asset('vendor/page-builder/page-builder.js')}}"></script>
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            {{ __("alder::leaf_types.$leaf_type->slug.add_new") }}
        </h1>
        @include('alder::components.locale-switcher')
    </div>

    <form id="edit-form"
          action="{{ $edit ? route("alder.$leaf_type->slug.update", $leaf->id) : route("alder.$leaf_type->slug.store") }}"
          enctype="multipart/form-data"
          method="POST">
        @csrf

        <input type="hidden" name="lcm" id="lcm">
        <div hidden id="this-leaf-type">{{$leaf_type->slug}}</div>

    {{$edit ? method_field('PUT') : method_field('POST')}}

    @php
        $mainRightPanelCounter = 0;

           foreach($params as $lcm_group) :
               $lcm = $lcm_group->lcm;

               foreach($lcm as $lcm_item => $k):
                    if(isset($k->panel) && $k->panel === 'right') $mainRightPanelCounter++;
               endforeach;

           endforeach;;
    @endphp


    <!-- tabs -->
        <div class="card card-nav-tabs card-plain">
            <div class="card-header card-header-danger">
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="main-section-tab" data-toggle="tab" href="#main-section"
                                   role="tab">{{__('alder::lcm.main')}}</a>
                            </li>

                            @php $tabsCounter = 0; @endphp
                            @foreach($params as $lcm_group)
                                @php
                                    $lcm = $lcm_group->lcm;
                                    $conditions = $lcm_group->conditions;
                                    $conditions_str = "";

                                    foreach($conditions as $cond_item):
                                        $conditions_str .= $cond_item->parameter . ":" . $cond_item->operator . ":" . $cond_item->value . " ";
                                    endforeach;
                                @endphp


                                @foreach($lcm as $lcm_item_k => $lcm_item_v)

                                    @if(isset($lcm_item_v->fields))
                                        @php $tabsCounter++; @endphp

                                        <li class="nav-item" data-condition="{{$conditions_str}}" hidden>
                                            <a class="nav-link" id="section-{{$tabsCounter}}-tab" data-toggle="tab"
                                               data-tab-name="{{$lcm_item_k}}"
                                               href="#section-{{$tabsCounter}}"
                                               role="tab">{{$lcm_item_v->display_name}}</a>
                                        </li>
                                    @endif
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- tabs content -->
        <div class="tab-content mb-5" id="myTabContent">

            <!-- *** MAIN TAB *** -->
            <div class="tab-pane fade card shadow show active" id="main-section" role="tabpanel">
                <div class="row">
                    <div class="col-lg-{{ $mainRightPanelCounter > 0 ? '9' : '12' }} mb-4 ">
                        <div class="card-body">
                            @foreach(['title', 'slug'] as $field)
                                <label for="{{ $field }}">{{ __("alder::table_columns.$field") }}</label>
                                <div class="input-group mb-2">
                                    <input type="text" name="{{ $field}}" id="{{ $field }}" class="form-control"
                                           placeholder="{{ $field }}"
                                           data-{{$field}}="1"
                                           aria-label="{{ $field }}" aria-describedby="{{ $field }}"
                                           value="{{ $edit ? $leaf->$field : '' }}">
                                </div>
                            @endforeach
                            <label for="status_id">{{ __('alder::leaf_statuses.singular') }}</label>
                            <div class="input-group mb-2">
                                <select name="status_id" id="status_id" class="form-control"
                                        aria-label="{{ __('alder::leaf_statuses.singular') }}"
                                        aria-describedby="{{ __('alder::leaf_statuses.singular') }}">
                                    @php
                                        $current_status = ($edit && (isset($leaf->status_id)))
                                            ? $leaf->status_id
                                            : \Webcosmonauts\Alder\Models\LeafStatus::where('slug', 'published')->value('id');
                                    @endphp
                                    @foreach($relations->statuses as $status)
                                        <option {{ $status->id == $current_status ? 'selected' : '' }}
                                                value="{{ $status->id }}">
                                            {{ __("alder::leaf_statuses.$status->slug") }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @foreach($params as $lcm_group)

                                @php
                                    $lcm = $lcm_group->lcm;
                                    $conditions = $lcm_group->conditions;
                                    $conditions_str = "";

                                    foreach($conditions as $cond_item):
                                        $conditions_str .= $cond_item->parameter . ":" . $cond_item->operator . ":" . $cond_item->value . " ";
                                    endforeach;
                                @endphp

                                @foreach($lcm as $lcm_item => $k)
                                    @php
                                        $field_name = $lcm_item;
                                        $label = $k->display_name;
                                        $field = $k;

                                        $field_value = "";

                                        if($edit && isset($leaf[$field_name]))
                                            $field_value = $leaf[$field_name];
                                    @endphp

                                    @if(isset($k->type))

                                        @php $type = $k->type; @endphp

                                        @if(!isset($k->panel) || $k->panel === "left")
                                            @include('alder::components.input')
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                @if($mainRightPanelCounter > 0)
                    <!-- SIDEBAR -->
                        <div class="col-lg-3 mb-4">
                            <div class="card-body">


                                @foreach($params as $lcm_group)
                                    @php
                                        $lcm = $lcm_group->lcm;
                                        $conditions = $lcm_group->conditions;
                                        $conditions_str = "";

                                        foreach($conditions as $cond_item):
                                                $conditions_str .= $cond_item->parameter . ":" . $cond_item->operator . ":" . $cond_item->value . " ";
                                        endforeach;
                                    @endphp



                                    @foreach($lcm as $lcm_item => $k)
                                        @php
                                            $field_name = $lcm_item;
                                            $label = $k->display_name;
                                            $field = $k;

                                            $field_value = "";

                                            if($edit && isset($leaf[$field_name]))
                                                $field_value = $leaf[$field_name];
                                        @endphp

                                        @if(isset($k->type) && isset($k->panel) && $k->panel === "right")
                                            @php $type = $k->type; @endphp
                                            @include('alder::components.input')
                                        @endif
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- *** END MAIN TAB *** -->


            <!-- *** OTHER TABS **** -->
            @php $tabsCounter = 0; @endphp


            @foreach($params as $lcm_group)

                @php
                    $lcm = $lcm_group->lcm;
                    $conditions = $lcm_group->conditions;
                    $conditions_str = "";

                    foreach($conditions as $cond_item):
                            $conditions_str .= $cond_item->parameter . ":" . $cond_item->operator . ":" . $cond_item->value . " ";
                    endforeach;
                @endphp

                @foreach($lcm as $tab => $lcm_item)

                    @if(isset($lcm_item->fields))
                        @php $tabsCounter++; @endphp
                        <div class="tab-pane card shadow fade show" data-condition="{{$conditions_str}}" hidden
                             id="section-{{$tabsCounter}}" role="tabpanel">

                            @php
                                $rightPanelCounter = 0;
                                foreach ($lcm_item->fields as $lcm_subitem => $k):
                                  if(isset($k->panel) && $k->panel === "right") $rightPanelCounter++;
                                endforeach;
                            @endphp

                            <div class="row">
                                <div class="col-lg-{{ $rightPanelCounter > 0 ? '9' : '12' }} mb-4">
                                    <div class="card-body">

                                        @foreach($lcm_item->fields as $lcm_subitem => $k)
                                            @php
                                                $field_name = $lcm_subitem;
                                                $label = $k->display_name;
                                                $field = $k;

                                                $field_value = "";
                                                if($edit && isset($leaf[$tab]))
                                                    $field_value = $leaf[$tab]->$field_name;
                                            @endphp

                                            @if(isset($k->type))
                                                @php $type = $k->type; @endphp
                                                @if(!isset($k->panel) || $k->panel === "left")
                                                    @include('alder::components.input')
                                                @endif
                                            @endif
                                        @endforeach

                                    </div>
                                </div>

                            @if($rightPanelCounter > 0)
                                <!-- SIDEBAR -->
                                    <div class="col-lg-3 mb-4">
                                        <div class="card-body">
                                            @foreach($lcm_item->fields as $lcm_subitem => $k)
                                                @php
                                                    $field_name = $lcm_subitem;
                                                    $label = $k->display_name;
                                                    $field = $k;

                                                    $field_value = "";
                                                    if($edit && isset($leaf[$tab]))
                                                        $field_value = $leaf[$tab]->$field_name;
                                                @endphp

                                                @if(isset($k->type) && isset($k->panel) && $k->panel === "right")
                                                    @php $type = $k->type; @endphp
                                                    @include('alder::components.input')
                                                @endif
                                            @endforeach
                                        </div>

                                    </div>
                                @endif
                            </div>
                        </div>
                @endif
            @endforeach
        @endforeach
        <!--  -->
        </div>


        <textarea name="content" id="content" hidden></textarea>


        <!-- PAGE BUILDER -->
        <div id="page-builder">
            <div class="card shadow mb-5">
                <div class="card-header"><h5 class="text-primary"> {{__('alder::generic.content')}} </h5></div>
                <div class="card-body">

                    <div id="page-builder-components" class="d-flex flex-wrap mb-5">
                        <a href="#" class="btn btn-sm btn-success mb-2 mr-2" data-component="slider"> Slider </a>
                        <a href="#" class="btn btn-sm btn-success mb-2 mr-2"
                           data-component="tiles"> {{__('alder::generic.tiles')}} </a>

                        <a href="#" class="btn btn-sm btn-success mb-2 mr-2" data-component="img_left_text_right">
                            {{__('alder::generic.img_left_text_right')}}
                        </a>


                        <a href="#" class="btn btn-sm btn-success mb-2 mr-2"
                           data-component="circle_diagram">{{__('alder::generic.circle_diagram')}}</a>


                        <a href="#" class="btn btn-sm btn-success mb-2 mr-2" data-component="center_text_tile">
                            {{__('alder::generic.center_text_tile')}}
                        </a>

                        <a href="#" class="btn btn-sm btn-success mb-2 mr-2"
                           data-component="partners"> {{__('alder::generic.partners')}} </a>

                        <a href="#" class="btn btn-sm btn-success mb-2 mr-2"
                           data-component="center_image">{{__('alder::generic.center_image')}}</a>

                        <a href="#" class="btn btn-sm btn-success mb-2 mr-2"
                           data-component="text_with_map">{{__('alder::generic.text_with_map')}}</a>

                        <a href="#" class="btn btn-sm btn-success mb-2 mr-2" data-component="html"> HTML </a>
                    </div>


                    <div id="page-builder-content">
                        @if($edit)

                            @php
                                $content = $leaf->content;
                                if($content) $content = json_decode($content);
                            @endphp

                            @if($leaf->content)
                                @foreach($content as $component)
                                    <div class="page-builder-content-item" data-component="{{$component->component}}"
                                         style="background-image: url({{asset('vendor/page-builder/img/'. $component->component .'.jpg')}})">

                                        <div class="page-builder-content-item__actions">
                                            <div class="circle-icon" title="W górę" data-action="up">
                                                <em class="fa fa-angle-up"></em>
                                            </div>
                                            <div class="circle-icon" title="Na dół" data-action="down">
                                                <em class="fa fa-angle-down"></em></div>
                                        </div>

                                        <div class="page-builder-content-item__delete delete-icon">×</div>

                                        <div hidden>
                                            @include("alder::components.page_builder")
                                            @include("alder::components.page_builder_animation", ['editing' => true])
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <!-- ******************************* -->
        <!-- PAGE BUILDER PATTERNS -->
        <div id="page-builder-patterns" hidden>

            <!-- SLIDER -->
            <div data-component="slider" data-thumbnail="{{asset('vendor/page-builder/img/slider.jpg')}}">
                <div class="repeater">

                    <h4 class="text-primary font-weight-bold mb-4"> Slider </h4>

                    <div class="card-body">
                        <div class="rptr-field card shadow mb-4">
                            <div class="delete-icon rptr-field__delete">&times;</div>

                            <div class="card-header"><h5
                                        class="text-primary font-weight-bold"> {{__('alder::generic.slide')}} </h5>
                            </div>
                            <div class="card-body">
                                [input:text:title:{{__('alder::generic.title')}}]
                                [textarea:text:{{__('alder::generic.text')}}]
                                [input:text:link:Link]
                                [input:text:link_text:{{__('alder::generic.link_text')}}]
                                [input:file:image:{{__('alder::generic.image')}}]

                                <div class="rptr-field__add btn btn-sm btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                             <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text"> {{__('alder::generic.add_row')}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- TILES -->
            <div data-component="tiles" data-thumbnail="{{asset('vendor/page-builder/img/tiles.jpg')}}">
                <div class="repeater">
                    <h4 class="text-primary font-weight-bold mb-4"> {{__('alder::generic.tiles')}} </h4>

                    <div class="card-body">
                        <div class="rptr-field card shadow mb-4">
                            <div class="delete-icon rptr-field__delete">&times;</div>

                            <div class="card-header">
                                <h5 class="text-primary font-weight-bold"> {{__('alder::generic.tile')}} </h5></div>
                            <div class="card-body">
                                [input:number:number:{{__('alder::generic.number')}}]
                                [input:text:title:{{__('alder::generic.title')}}]
                                [input:file:image:{{__('alder::generic.image')}}]

                                <div class="rptr-field__add btn btn-sm btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                             <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text"> {{__('alder::generic.add_row')}} </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                @include("alder::components.page_builder_animation", ['editing' => false])
            </div>


            <!-- LEFT IMG RIGHT TEXT -->
            <div data-component="img_left_text_right"
                 data-thumbnail="{{asset('vendor/page-builder/img/img_left_text_right.jpg')}}">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="text-primary font-weight-bold">{{__('alder::generic.img_left_text_right')}}</h5>
                    </div>
                    <div class="card-body">
                        [input:number:left_column_size:{{__('alder::generic.left_column_size')}} (%)]
                        [input:file:image:{{__('alder::generic.image')}}]

                        [input:number:right_column_size:{{__('alder::generic.right_column_size')}} (%)]
                        [input:text:title:{{__('alder::generic.title')}}]
                        [textarea:text:{{__('alder::generic.text')}}]
                        [input:text:link_text:{{__('alder::generic.link_text')}}]
                        [input:text:link:Link]
                    </div>
                </div>

                @include("alder::components.page_builder_animation", ['editing' => false])
            </div>


            <!-- CENTER TEXT TILE-->
            <div data-component="center_text_tile"
                 data-thumbnail="{{asset('vendor/page-builder/img/center_text_tile.jpg')}}">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="text-primary font-weight-bold">{{__('alder::generic.center_text_tile')}}</h5>
                    </div>
                    <div class="card-body">
                        [input:text:title:{{__('alder::generic.title')}}]
                        [textarea:text:{{__('alder::generic.text')}}]
                        [input:text:link_text:{{__('alder::generic.link_text')}}]
                        [input:text:link:Link]
                        [input:file:background:{{__('alder::generic.background')}}]
                    </div>
                </div>

                @include("alder::components.page_builder_animation", ['editing' => false])
            </div>


            <!-- CIRCLE DIAGRAM -->
            <div data-component="circle_diagram"
                 data-thumbnail="{{asset('vendor/page-builder/img/circle_diagram.jpg')}}">

                <h4 class="text-primary font-weight-bold mb-4"> {{__('alder::generic.circle_diagram')}} </h4>

                <div class="card mb-4">
                    <div class="card-body">
                        [input:text:section_title:{{__('alder::generic.section_title')}}]
                        [input:text:section_subtitle:{{__('alder::generic.section_subtitle')}}]
                    </div>
                </div>

                <div class="repeater">
                    <div class="card-body">
                        <div class="rptr-field card shadow mb-4">
                            <div class="delete-icon rptr-field__delete">&times;</div>

                            <div class="card-header">
                                <h5 class="text-primary font-weight-bold"> {{__('alder::generic.item')}} </h5></div>
                            <div class="card-body">
                                [input:number:percent:{{__('alder::generic.percent')}}]
                                [input:file:percent_bg:{{__('alder::generic.percent_bg')}}]
                                [input:text:text:{{__('alder::generic.text')}}]
                                [input:number:amount:{{__('alder::generic.amount')}}]

                                [input:number:amount_2:{{__('alder::generic.amount')}} 2]
                                [input:text:text_2:{{__('alder::generic.text')}} 2]

                                <div class="rptr-field__add btn btn-sm btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                             <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text"> {{__('alder::generic.add_row')}} </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                @include("alder::components.page_builder_animation", ['editing' => false])
            </div>

            <!-- PARTNERS -->
            <div data-component="partners" data-thumbnail="{{asset('vendor/page-builder/img/partners.jpg')}}">
                <div class="repeater">
                    <h4 class="text-primary font-weight-bold mb-4"> {{__('alder::generic.partners')}} </h4>


                    <div class="card mb-4">
                        <div class="card-body">
                            [input:text:section_title:{{__('alder::generic.section_title')}}]
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="rptr-field card shadow mb-4">
                            <div class="delete-icon rptr-field__delete">&times;</div>

                            <div class="card-header">
                                <h5 class="text-primary font-weight-bold"> {{__('alder::generic.partner_logo')}} </h5>
                            </div>
                            <div class="card-body">

                                [input:text:link_to_the_partner:{{__('alder::generic.link_to_partner')}}]
                                [input:file:partner_image:{{__('alder::generic.partner_img')}}]

                                <div class="rptr-field__add btn btn-sm btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                             <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text"> {{__('alder::generic.add_row')}} </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                @include("alder::components.page_builder_animation", ['editing' => false])
            </div>


            <!-- Center image -->
            <div data-component="center_image" data-thumbnail="{{asset('vendor/page-builder/img/center_image.jpg')}}">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="text-primary font-weight-bold">{{__('alder::generic.center_image')}}</h5>
                    </div>
                    <div class="card-body">
                        [input:file:image:{{__('alder::generic.image')}}]
                    </div>
                </div>

                @include("alder::components.page_builder_animation", ['editing' => false])
            </div>


            <!-- HTML -->
            <div data-component="html" data-thumbnail="{{asset('vendor/page-builder/img/html.jpg')}}">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="text-primary font-weight-bold">{{__('alder::generic.content')}}</h5>
                    </div>
                    <div class="card-body">
                        [textarea:html_content]
                    </div>
                </div>

                @include("alder::components.page_builder_animation", ['editing' => false])
            </div>


            <!-- TEXT WITH MAP -->
            <div data-component="text_with_map" data-thumbnail="{{asset('vendor/page-builder/img/text_with_map.jpg')}}">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="text-primary font-weight-bold">{{__('alder::generic.text_with_map')}}</h5>
                    </div>

                    <div class="card-body">
                        [input:number:left_column_size:{{__('alder::generic.left_column_size')}} (%)]
                        [textarea:text:{{__('alder::generic.text')}}]

                        [input:number:right_column_size:{{__('alder::generic.right_column_size')}} (%)]

                        [input:number:lat:{{__('alder::generic.lat')}}]
                        [input:number:lng:{{__('alder::generic.lng')}}]
                        [input:number:zoom:{{__('alder::generic.zoom')}}]
                    </div>
                </div>

                @include("alder::components.page_builder_animation", ['editing' => false])
            </div>
        </div>


        <!-- PAGE BUILDER MODAL -->
        <div id="page-builder-modal">
            <div class="close-modal delete-icon">&times;</div>

            <div class="content"></div>


            <div class="btn-container text-right">
                <button type="button" id="page-builder-modal-save" class="btn btn-primary btn-icon-split mt-3">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-save"></i>
                                        </span>
                    <span class="text">{{ __('alder::generic.save') }} </span>
                </button>
            </div>
        </div>


        <button type="submit" class="btn btn-success btn-icon-split mb-1">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-save"></i>
                                        </span>
            <span class="text">{{ __('alder::generic.save') }}</span>
        </button>
    </form>
@endsection

