@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{__('alder::theme.appearance_title')}} </h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body ">
            <h2>{{$theme->theme_name}}</h2>

            <div class="row">

                <div class="col-12 col-lg-3">
                    @php
                        function recursiveArrayToList(Array $array = array())
                        {
                            echo '<ul>';

                            foreach ($array as $key => $value) {

                                if (is_array($value)) {
                                    echo '<li>' . $key;
                                    recursiveArrayToList($value);
                                    echo '</li>';
                                }
                                else{
                                    echo "<li>".$value."</li>";
                                }
                            }
                            echo '</ul>';
                        }

                        recursiveArrayToList($views_hierarchy);
                    @endphp
                </div>
                <div class="col-12 col-lg-9">
                    <form action="{{route('alder.saveRoots')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @foreach($roots as $root_val)

                            <?php $root_val = $root_val->first()?>
                            {{--                        @dd($root_val->slug)--}}
                            @if ($root_val->slug == 'timezone')
                                <label for="{{$root_val->slug}}">{{$root_val->title}}</label>
                                <select class="form-control" name="{{$root_val->slug}}" id="{{$root_val->slug}}">
                                    @foreach($root_val->options as $zones)
                                        <option {{$root_val->value != $zones ? : 'selected' }} value="{{$zones}}">{{$zones}}</option>
                                    @endforeach
                                </select> <br>
                            @elseif($root_val->slug == 'favicon')
                                <div class="custom-file">
                                    <label class="custom-file-label" for="{{$root_val->slug}}">{{$root_val->title}}</label>
                                    <input class="custom-file-input" type="file" name="userfile"> </div><br><br>
                            @elseif($root_val->slug == 'leaves_per_page')
                                <label for="{{$root_val->slug}}">{{$root_val->title}}</label>
                                <input class="form-control" type="number" name="{{$root_val->slug}}"
                                       value="{{$root_val->value}}"> <br>
                            @elseif($root_val->slug == 'static_index_page')
                                <label for="{{$root_val->slug}}">{{$root_val->title}}</label>
                                <input class="form-control" type="text" name="{{$root_val->slug}}"
                                       value="{{$root_val->value}}"> <br>
                            @else
                            @endif
                        @endforeach
                        <button type="submit" class="btn btn-success btn-icon-split">
                            <span class="icon text-white-50">
                              <i class="fas fa-save"></i>
                            </span>
                            <span class="text">{{ __('alder::generic.save') }}</span>
                        </button>
                    </form>
                    @if(session()->has('success'))
                        <div class="card mb-4 border-left-{{ session()->get('alert-type', 'success') }}">
                            <div class="card-body">
                                {{ session()->get('success') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
