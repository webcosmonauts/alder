@extends('alder::layouts.main')

@section('scripts-body')
    <link rel="stylesheet" href="{{ asset('vendor/contact-form/css/contact-form.css') }}">
    <script src="{{ asset('vendor/contact-form/js/contact-form.js') }}"></script>
@endsection

@section('content')

    <form action="lalala" method="post">

        <div class="form-group mb-5">
            <label for="contact-form-title"> Title </label>
            <input type="text" name="title" id="contact-form-title" class="form-control" required>
        </div>


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

                <!-- Template builder -->
                <div id="contact-form-template-builder">

                    <div class="d-flex flex-wrap components">
                        <a href="#" data-component="text"> Text </a>
                        <a href="#" data-component="email"> Email </a>

                        <a href="#" data-component="submit"> Submit </a>
                    </div>

                    <label for="contact-form-content" hidden></label>
                    <textarea name="content" rows="10" id="contact-form-content" class="form-control"></textarea>
                </div>
                <!--  -->
            </div>


            <!-- tab 2 -->
            <div class="tab-pane" id="email-tab-pane" role="tabpanel" aria-labelledby="email-tab">

                <!-- recipient -->
                <div class="form-group row">
                    <label for="contact-form-recipient" class="col-sm-2 col-form-label"> Recipient </label>

                    <div class="col-sm-10">
                        <input type="text" name="recipient" id="contact-form-recipient" class="form-control">
                    </div>
                </div>

                <!-- sender -->
                <div class="form-group mb-5 row">
                    <label for="contact-form-sender" class="col-sm-2 col-form-label"> Sender </label>

                    <div class="col-sm-10">
                        <input type="text" name="sender" id="contact-form-sender" class="form-control">
                    </div>
                </div>


                <!-- theme -->
                <div class="form-group row">
                    <label for="contact-form-theme" class="col-sm-2 col-form-label"> Recipient </label>

                    <div class="col-sm-10">
                        <input type="text" name="theme" id="contact-form-theme" class="form-control">
                    </div>
                </div>


                <!-- Additional headers -->
                <div class="form-group row">
                    <label for="contact-form-additional-headers" class="col-sm-2 col-form-label"> Additional
                        headers </label>

                    <div class="col-sm-10">
                        <textarea name="additional-headers" id="contact-form-additional-headers"
                                  class="form-control"></textarea>
                    </div>
                </div>

                <!-- Message content -->
                <div class="form-group row">
                    <label for="contact-form-message-content" class="col-sm-2 col-form-label"> Message content</label>

                    <div class="col-sm-10">
                        <textarea name="message-content" rows="8" id="contact-form-message-content"
                                  class="form-control"></textarea>
                    </div>
                </div>

            </div>
        </div>


        <input type="submit" class="btn btn-primary btn-success" value="Submit">
    </form>



    <!-- MODAL -->
    <div class="alder-modal" id="contact-form-template-modal" tabindex="-1">
        <div class="alder-modal-content">

            <div class="form-group row" data-component="text email">
                <label for="contact-form-item-name" class="col-sm-2 col-form-label"> Name </label>

                <div class="col-sm-10">
                    <input type="text" id="contact-form-item-name" class="form-control">
                </div>
            </div>


            <div class="form-group mb-5 row" data-component="text email">
                <label for="contact-form-item-required" class="col-sm-2 col-form-label"> Required </label>

                <div class="col-sm-10">
                    <input type="checkbox" id="contact-form-item-required">
                </div>
            </div>


            <div class="form-group mb-5 row" data-component="submit">
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