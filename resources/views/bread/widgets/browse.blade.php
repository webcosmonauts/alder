@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{__('alder::widgets.widgets')}}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body row">
            <div class="col-12 col-lg-3">

            </div>
            <div class="col-12 col-lg-9">
                @if(!empty($widgets))
                    @foreach($widgets as $key=>$single_widget)
                        <form action="{{route('alder.widgets.update')}}" method="post">
                            <h3>{{$single_widget->title}}</h3>
                            @csrf
                            @foreach($single_widget->toArray() as $k=>$v)

                                @if($k == "value")
                                    <textarea type="text" id="{{$single_widget->slug}}-{{$k}}"
                                           name="{{$single_widget->slug}}"
                                              class="form-control">{{$v}}</textarea>
                                @endif


                            @endforeach
                            <br>
                            <button type="submit" class="btn btn-success">{{__('alder::generic.save')}}</button>
                        </form>
                        <hr>
                    @endforeach
                @endif

            </div>

        </div>
    </div>
@endsection
