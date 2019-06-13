@extends('alder::layouts.main')


@section('scripts-body')
    <link rel="stylesheet" href="{{asset('js/themes/snow.css')}}">
    <script src="{{asset('js/quill.min.js')}}"></script>
    <script src="{{asset('js/content-quill.js')}}"></script>
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $leaf_type->title }}</h1>
    </div>

    <form action="{{ $edit ? route("alder.$leaf_type->slug.update", $leaf->slug) : route("alder.$leaf_type->slug.store") }}"
          method="POST">
        @csrf
    {{$edit ? method_field('PUT') : method_field('POST')}}

    @php
        $right_panel_count = 0;
        $lcm = $params->lcm;

        $mainRightPanelCounter = 0;
        foreach($lcm as $lcm_item => $k):
            if(isset($k->panel) && $k->panel === 'right') $mainRightPanelCounter++;
        endforeach;


    @endphp


    <!-- tabs -->
        <ul class="nav nav-tabs position-relative border-bottom-0" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="main-section-tab" data-toggle="tab" href="#main-section"
                   role="tab">{{__('alder::lcm.main')}}</a>
            </li>

            @php $tabsCounter = 0; @endphp
            @foreach($lcm as $lcm_item)

                @if(isset($lcm_item->fields))

                    @php $tabsCounter++; @endphp

                    <li class="nav-item">
                        <a class="nav-link" id="section-{{$tabsCounter}}-tab" data-toggle="tab"
                           href="#section-{{$tabsCounter}}" role="tab">{{$lcm_item->display_name}}</a>
                    </li>
                @endif
            @endforeach
        </ul>

        <!-- tabs content -->
        <div class="tab-content" id="myTabContent">

            <!-- *** MAIN TAB *** -->
            <div class="tab-pane fade card shadow show active" id="main-section" role="tabpanel">
                <div class="row">
                    <div class="col-lg-{{ $mainRightPanelCounter > 0 ? '9' : '12' }}">
                        <div class=" mb-4 border-top-0">
                            <div class="card-body">
                                @foreach(['title', 'slug', 'user_id'] as $field)
                                    <label for="{{ $field }}">{{ $field }}</label>
                                    <div class="input-group mb-2">
                                        <input type="text" name="{{ $field}}" id="{{ $field }}" class="form-control"
                                               placeholder="{{ $field}}"
                                               aria-label="{{ $field }}" aria-describedby="{{ $field }}"
                                               value="{{ $edit ? $leaf->$field : '' }}">

                                    </div>
                                @endforeach

                                <label for="#content">Content</label>
                                <div class="input-group mb-2">

                                    <div id="quill" style="height: 400px; width: 100%"></div>

                                    <textarea type="text" rows="8" hidden name="content" id="content"
                                              class="form-control"
                                              aria-label="content" aria-describedby="content"
                                              placeholder="Content"></textarea>
                                </div>


                                @foreach($lcm as $lcm_item => $k)
                                    @php $field_name = $lcm_item; $label = $k->display_name; @endphp

                                    @if(isset($k->type))
                                        @if(!isset($k->panel) || $k->panel === "left")
                                            @include('alder::components.input')
                                        @endif
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    </div>

                @if($mainRightPanelCounter > 0)
                    <!-- SIDEBAR -->
                        <div class="col-lg-3">
                            <div class=" mb-4">
                                <div class="card-body">
                                    @foreach($lcm as $lcm_item => $k)
                                        @php $field_name = $lcm_item; $label = $k->display_name;@endphp

                                        @if(isset($k->type) && isset($k->panel) && $k->panel === "right")
                                            @include('alder::components.input')
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- *** END MAIN TAB *** -->


            <!-- *** OTHER TABS **** -->
            @php $tabsCounter = 0; @endphp
            @foreach($lcm as $lcm_item)



                @if(isset($lcm_item->fields))

                    @php $tabsCounter++; @endphp
                    <div class="tab-pane card shadow fade show" id="section-{{$tabsCounter}}" role="tabpanel">

                        @php
                            $rightPanelCounter = 0;
                            foreach ($lcm_item->fields as $lcm_subitem => $k):
                              if(isset($k->panel) && $k->panel === "right") $rightPanelCounter++;
                            endforeach;
                        @endphp

                        <div class="row">
                            <div class="col-lg-{{ $rightPanelCounter > 0 ? '9' : '12' }}">
                                <div class="mb-4">
                                    <div class="card-body">

                                        @foreach($lcm_item->fields as $lcm_subitem => $k)
                                            @php $field_name = $lcm_subitem; $label = $k->display_name; @endphp

                                            @if(!isset($k->fields))
                                                @if(!isset($k->panel) || $k->panel === "left")
                                                    @include('alder::components.input')
                                                @endif
                                            @endif
                                        @endforeach

                                    </div>
                                </div>
                            </div>

                        @if($rightPanelCounter > 0)
                            <!-- SIDEBAR -->
                                <div class="col-lg-3">
                                    <div class=" mb-4">
                                        <div class="card-body">
                                            @foreach($lcm_item->fields as $lcm_subitem => $k)
                                                @php $field_name = $lcm_subitem; $label = $k->display_name; @endphp

                                                @if(!isset($k->fields) && isset($k->panel) && $k->panel === "right")
                                                    @include('alder::components.input')
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
            @endif
        @endforeach
        <!--  -->
        </div>


        <button type="submit" class="btn btn-success btn-icon-split mb-1">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-save"></i>
                                        </span>
            <span class="text">{{ __('alder::generic.save') }}</span>
        </button>


    </form>
@endsection

