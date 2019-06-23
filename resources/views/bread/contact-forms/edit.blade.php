@extends('alder::layouts.main')

@section('scripts-body')

    <link rel="stylesheet" href="{{asset('/vendor/contact-form/css/contact-form.css')}}">
    <script src="{{asset('/vendor/contact-form/contact-form.js')}}"></script>
@endsection

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('alder::leaf_types.contact-forms.singular') }}</h1>
    </div>

    @if(session()->has('success'))
        <div class="card mb-4 border-left-{{ session()->get('alert-type', 'success') }}">
            <div class="card-body">
                {{ session()->get('success') }}
            </div>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body">

            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="pill" href="#parcer">Form</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#mailer">Mailer</a>
                </li>
            </ul>

            <form action="{{ $edit ? route("alder.contact-forms.update",  $id) : route("alder.contact-forms.save_form")}}"
                  method="post">
                @csrf
                {{$edit ? method_field('PUT') : method_field('POST')}}
                <div class="tab-content">
                    <div id="parcer" class="container tab-pane active"><br>


                        <!-- Template builder -->
                        <div class="form-group mb-5">
                            <label for="contact-form-title"> Title </label>
                            <input type="text" name="title" id="contact-form-title" class="form-control"
                                   value="{{ $edit ? $content->title : '' }}"
                                   required>
                            <br>
                            <label for="contact-form-title"> Slug </label>
                            <input type="text" name="slug" id="contact-form-slug" class="form-control"
                                   value="{{ $edit ? $content->slug : '' }}"
                                   required>
                            <br>
                            <label for="is_accessible">Visibility</label>
                            <input type="checkbox" class="icheck"
                                   name="is_accessable" {{ $edit && $form->is_accessible == '1' ? 'checked' : ''}}>
                        </div>
                        <div id="contact-form-template-builder">

                            <div class="d-flex flex-wrap components">
                                <a href="#" data-component="text"> Text </a>
                                <a href="#" data-component="email"> Email </a>
                                <a href="#" data-component="tel"> Telephone </a>
                                <a href="#" data-component="date"> Date </a>
                                <a href="#" data-component="textarea" class="mr-4"> Textarea </a>

                                <a href="#" data-component="select">Select</a>
                                <a href="#" data-component="checkbox">Checkbox</a>
                                <a href="#" data-component="radio" class="mr-4">Radio</a>

                                <a href="#" data-component="acceptance"> Acceptance </a>
                                <a href="#" data-component="file">File</a>
                                <a href="#" data-component="submit"> Submit </a>
                            </div>

                            <label for="contact-form-content" hidden></label>
                            <textarea name="template-content" rows="10" id="contact-form-content"
                                      class="form-control">{{ $edit ? $template['template-content'] : '' }}</textarea>
                        </div>
                        <div>

                        </div>
                        <!--  -->



                    </div>
                    <div id="mailer" class="container tab-pane fade"><br>

                        @csrf
                        <p>
                            @if($read == true)
                                @foreach($array_key as $key => $val)
                                    {{$val}},
                                @endforeach
                            @endif
                        </p>

                        <input type="hidden" name="array_mail" value="{{$arr_total}}">

                        @if ($read)
                            @foreach($array_mailer as $key => $value)
                                {{--                            @if ( $value->input_type == 'text')--}}
                                {{--                                @if ($value->title != 'message_content')--}}
                                {{--                                    <label for="{{$value->slug}}">{{$value->title}}</label>--}}
                                {{--                                    <input  name="{{$value->slug}}" class="form-control" type="text" id="{{$value->slug}}" value="{{$value->value}}"  ><br>--}}
                                {{--                                @else--}}
                                {{--                                    <label  for="{{$value->slug}}" required>{{$value->title}}</label>--}}
                                {{--                                    <textarea name="{{$value->slug}}" class="form-control"--}}
                                {{--                                              id="{{$value->slug}}">{{$value->value}}</textarea><br>--}}
                                {{--                                @endif--}}
                                {{--                            @elseif($value->input_type == 'password')--}}
                                {{--                                <label for="{{$value->slug}}">{{$value->title}}</label>--}}
                                {{--                                <input  name="{{$value->slug}}" class="form-control" type="text" id="{{$value->slug}}" value="{{$value->value}}" ><br>--}}
                                {{--                            @elseif($value->input_type == 'number')--}}
                                {{--                                <label for="{{$value->slug}}">{{$value->title}}</label>--}}
                                {{--                                <input  name="{{$value->slug}}" class="form-control" type="number" id="{{$value->slug}}" value="{{$value->value}}"  ><br>--}}
                                {{--                            @elseif($value->input_type == 'checkbox')--}}
                                {{--                                <label for="{{$value->slug}}">{{$value->title}}</label>--}}
                                {{--                                <input  name="{{$value->slug}}" class="" type="checkbox" id="{{$value->slug}}" {{$value->value ? 'checked' : ''}} ><br>--}}
                                {{--                            @elseif($value->input_type == 'select')--}}
                                {{--                                <label for="{{$value->slug}}">{{$value->title}}</label>--}}
                                {{--                                <select  name="{{$value->slug}}" class="browser-default custom-select" >--}}
                                {{--                                    <option value="tls">TLS</option>--}}
                                {{--                                    <option value="ssl">SSL</option>--}}
                                {{--                                </select><br><br>--}}
                                {{--                            @endif--}}
                                <label for="{{$key}}">{{$key}}</label>
                                <input  name="{{$key}}" class="form-control" type="text" id="{{$key}}" value="{{$value}}" ><br>

                            @endforeach
                        @else
                            @foreach($mailer as $key => $value)
                                @if ( $value->input_type == 'text')
                                    @if ($value->title != 'message_content')
                                        <label for="{{$value->slug}}">{{$value->title}}</label>
                                        <input  name="{{$value->slug}}" class="form-control" type="text" id="{{$value->slug}}" value="{{$value->value}}"  ><br>
                                    @else
                                        <label  for="{{$value->slug}}" required>{{$value->title}}</label>
                                        <textarea name="{{$value->slug}}" class="form-control"
                                                  id="{{$value->slug}}">{{$value->value}}</textarea><br>
                                    @endif
                                @elseif($value->input_type == 'password')
                                    <label for="{{$value->slug}}">{{$value->title}}</label>
                                    <input  name="{{$value->slug}}" class="form-control" type="text" id="{{$value->slug}}" value="{{$value->value}}" ><br>
                                @elseif($value->input_type == 'number')
                                    <label for="{{$value->slug}}">{{$value->title}}</label>
                                    <input  name="{{$value->slug}}" class="form-control" type="number" id="{{$value->slug}}" value="{{$value->value}}"  ><br>
                                @elseif($value->input_type == 'checkbox')
                                    <label for="{{$value->slug}}">{{$value->title}}</label>
                                    <input  name="{{$value->slug}}" class="" type="checkbox" id="{{$value->slug}}" {{$value->value ? 'checked' : ''}} ><br>
                                @elseif($value->input_type == 'select')
                                    <label for="{{$value->slug}}">{{$value->title}}</label>
                                    <select  name="{{$value->slug}}" class="browser-default custom-select" >
                                        <option value="tls">TLS</option>
                                        <option value="ssl">SSL</option>
                                    </select><br><br>
                                @endif
                                {{--                            <label for="{{$key}}">{{$key}}</label>--}}
                                {{--                            <input  name="{{$key}}" class="form-control" type="text" id="{{$key}}" value="{{$value}}" ><br>--}}

                            @endforeach
                        @endif
                        <button class="btn btn-success">Send</button>

                    </div>




                </div>
            </form>
        </div>

    </div>

    <!-- MODAL -->
    <div class="alder-modal" id="contact-form-template-modal" tabindex="-1">
        <div class="alder-modal-content">
            <div class="alder-modal-close">&times;</div>

            <!-- REQUIRED -->
            <div class="form-group row" data-component="text email tel date textarea select checkbox radio file">
                <label for="contact-form-item-required" class="col-sm-2 col-form-label"> Required </label>

                <div class="col-sm-10">
                    <input type="checkbox" id="contact-form-item-required">
                </div>
            </div>

            <!-- NAME -->
            <div class="form-group row"
                 data-component="text email tel date textarea select checkbox radio file acceptance">
                <label for="contact-form-item-name" class="col-sm-2 col-form-label"> Name </label>

                <div class="col-sm-10">
                    <input type="text" id="contact-form-item-name" class="form-control">
                </div>
            </div>

            <!-- OPTIONS -->
            <div class="form-group row" data-component="select checkbox radio">
                <label for="contact-form-item-options" class="col-12 col-form-label"> Options </label>
                <em class="col-12">Each option on a separate line "value : Label"</em>

                <div class="col-12">
                    <textarea id="contact-form-item-options" rows="5" class="form-control"></textarea>
                </div>
            </div>


            <!-- Allowed file types -->
            <div class="form-group row" data-component="file">
                <label for="contact-form-item-allowed-file-types" class="col-12 col-form-label"> Allowed file
                    types</label>

                <div class="col-12">
                    <input type="text" id="contact-form-item-allowed-file-types" class="form-control">
                </div>
            </div>

            <!-- Condition -->
            <div class="form-group row" data-component="acceptance">
                <label for="contact-form-item-condition" class="col-sm-2 col-form-label"> Condition </label>

                <div class="col-sm-10">
                    <input type="text" id="contact-form-item-condition" class="form-control">
                </div>
            </div>

            <!-- LABEL -->
            <div class="form-group row" data-component="submit">
                <label for="contact-form-item-required" class="col-sm-2 col-form-label"> Label </label>
                <div class="col-sm-10">
                    <input type="text" id="contact-form-item-label" class="form-control">
                </div>
            </div>
            <button class="btn btn-primary">Insert</button>
        </div>
    </div>
    <!-- END MODAL -->
@endsection