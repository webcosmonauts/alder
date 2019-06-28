@extends('alder::layouts.main')

@section('scripts-body')
    @include("themes.nimoz.parts.header")
    <script src="{{asset('vendor/contact-form/contact-form-parser.js')}}"></script>
@endsection

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('alder::leaf_types.contact-forms.singular') . " $forms->title" }}</h1>
    </div>

    <div class="card shadow">
        <div class="card-body">

            <div class="container">

                <form action="#">
                    @csrf
                    <div class="inner-page-container container">
                        <div class="row m-0">
                            <div class="inner-page-content">
                                <div class="inner-page-content">
                                    <div class="form form-with-shortcode" hidden>
                                        @php echo $contact['template-content']; @endphp
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


    </div>


@endsection