@extends('alder::layouts.main')

@section('scripts-body')

    <link rel="stylesheet" href="{{asset('/vendor/contact-form/css/contact-form.css')}}">
    <script src="{{asset('/vendor/contact-form/contact-form.js')}}"></script>
@endsection

@section('content')


    <div class="form-group mb-5">
        <label for="contact-form-title"> Title </label>
        <input type="text" name="title" id="contact-form-title" class="form-control" required>
    </div>
    @if(session()->has('success'))
        <div class="card mb-4 border-left-{{ session()->get('alert-type', 'success') }}">
            <div class="card-body">
                {{ session()->get('success') }}
            </div>
        </div>
    @endif

    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a class="nav-link active" id="form-tab" data-toggle="tab" href="#form-tab-pane" role="tab"
               aria-controls="form"
               aria-selected="true">Form</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="email-tab" data-toggle="tab" href="#email-tab-pane" role="tab"
               aria-controls="email"
               aria-selected="true">Email</a>
        </li>
    </ul>


    <!-- Tab panes -->
    <div class="tab-content mb-5">

        <!-- tab 1 -->
        <div class="tab-pane active" id="form-tab-pane" role="tabpanel" aria-labelledby="form-tab">

            <form action="" method="post">
                <!-- Template builder -->
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
                    <textarea name="template-content" rows="10" id="contact-form-content" class="form-control"></textarea>
                </div>
                <!--  -->

                <input type="submit" class="btn btn-primary btn-success mt-4" value="Submit">
            </form>
        </div>


        <!-- tab 2 -->
        <div class="tab-pane" id="email-tab-pane" role="tabpanel" aria-labelledby="email-tab">

            <form action="{{route('alder.roots.update')}}" method="post">
            @csrf
            <!-- recipient -->
                <div class="form-group row">
                    <label for="contact-form-recipient" class="col-sm-2 col-form-label"> Recipient </label>

                    <div class="col-sm-10">
                        <input type="text" name="recipient"
                               id="contact-form-recipient" class="form-control"
                               value="{{ $roots->recipient }}">
                    </div>
                </div>

                <!-- sender -->
                <div class="form-group mb-5 row">
                    <label for="contact-form-sender" class="col-sm-2 col-form-label"> Sender </label>

                    <div class="col-sm-10">
                        <input type="text" name="sender" id="contact-form-sender"
                               class="form-control" value="{{ $roots->sender }}">
                    </div>
                </div>


                <!-- theme -->
                <div class="form-group row">
                    <label for="contact-form-theme" class="col-sm-2 col-form-label"> Recipient </label>

                    <div class="col-sm-10">
                        <input type="text" name="theme" id="contact-form-theme"
                               class="form-control" value="{{ $roots->theme }}">
                    </div>
                </div>

                <!-- Additional headers -->
                <div class="form-group row">
                    <label for="contact-form-additional-headers" class="col-sm-2 col-form-label"> Additional
                        headers </label>

                    <div class="col-sm-10">
                        <textarea name="additional_headers" id="contact-form-additional-headers"
                                  class="form-control">{{ $roots->additional_headers }}</textarea>
                    </div>
                </div>

                <!-- Message content -->
                <div class="form-group row">
                    <label for="contact-form-message-content" class="col-sm-2 col-form-label"> Message content</label>

                    <div class="col-sm-10">
                        <textarea name="message_content" rows="8" id="contact-form-message-content"
                                  class="form-control"
                                  value="">{{ $roots->message_content }}</textarea>
                    </div>
                </div>

                <input type="submit" class="btn btn-primary btn-success" value="Submit">

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