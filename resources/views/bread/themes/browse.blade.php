@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{__('alder::theme.themes_page_title')}}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body row">
            @foreach($themes as $key=>$theme)
                <div class="col-12 col-lg-3">
                    <div class="card mb-4 {{$active_theme == $theme['theme_slug'] ? "border-left-success" : ""}}">

                        <div class="card-header">

                            @if($theme['version'])
                                {!! $theme['theme_uri'] ? "<a target='_blank' href='".$theme['theme_uri']."'>" : ''  !!}
                                {{$theme['theme_name']}}
                                ({{__("alder::theme.version")}} <b>{{$theme['version']}}</b>)
                                {!! $theme['theme_uri'] ? "</a>" : ''  !!}
                            @endif
                        </div>
                        <div style="height: 200px; overflow: hidden; width: auto;">
                            <img class="card-img-top" src="{{asset(str_replace(public_path().'/themes/', '', $theme['screenshot']))}}" alt="Card image cap">
                        </div>

                        <div class="card-body">
                            @foreach($theme['tags'] as $tag)
                                <span class="badge badge-pill badge-success">{{$tag}}</span>
                            @endforeach

                            @if($theme['theme_description'])
                                <p>{{$theme['theme_description']}}</p>
                            @endif

                            @if($theme['license'])
                                <p>{{__("alder::theme.license")}}:{{$theme['license']}}</p>
                            @endif

                            @if($theme['author'] || $theme['author_uri'])
                                <small>
                                    {!! $theme['author_uri'] ? "<a target='_blank' href='".$theme['author_uri']."'>" : ''  !!}
                                    {{__('alder::theme.by')}}: {{$theme['author']}}
                                    {!! $theme['author_uri'] ? "</a>" : ''  !!}
                                </small>
                            @endif

                        </div>

                        @if($active_theme !== $theme['theme_slug'])
                            <div class="card-footer">
                                <form action="{{route('alder.themes.update')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="active-theme" value="{{$theme['theme_slug']}}">
                                    <button class="btn btn-success"
                                            type="submit">{{__("alder::theme.set_theme")}}</button>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>

            @endforeach

        </div>
    </div>
@endsection
