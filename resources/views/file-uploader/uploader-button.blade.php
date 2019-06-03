@extends('alder::layouts.main')

@section('scripts-body')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('button-image').addEventListener('click', (event) => {
                event.preventDefault();
                window.open('/file-manager/fm-button', 'fm', 'width=1000,height=600,top=200,left=400');
            });
        });

        // set file link
        function fmSetLink($url) {
            document.getElementById('image_label').value = $url;
        }
    </script>
@endsection

@section('content')
    <div class="input-group">
        <input type="text" id="image_label" class="form-control" name="image"
               aria-label="Image" aria-describedby="button-image">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="button-image">Select</button>
        </div>
    </div>
@endsection