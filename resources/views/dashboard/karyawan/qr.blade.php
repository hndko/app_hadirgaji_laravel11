@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <span class="h5 m-0">QR Code for {{ $karyawan->name }} ({{ $karyawan->nip }})</span>
                            <div class="card-tools">
                                <a href="{{ route('karyawan.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <img id="qr-code" src="{{ asset($qrImagePath) }}" alt="QR Code" class="img-fluid mb-3">
                            <br>
                            <a id="download-link" href="{{ asset($qrImagePath) }}" download
                                class="btn btn-sm btn-success">Download QR Code</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery to update QR code every 5 seconds -->
    <script>
        $(document).ready(function() {
            function refreshQrCode() {
                // Get the current image source (without the query parameters)
                let imgSrc = $('#qr-code').attr('src').split('?')[0];

                // Add a timestamp to the image URL to force refresh
                $('#qr-code').attr('src', imgSrc + '?' + new Date().getTime());

                // Optionally, you can also update the download link to the new image
                $('#download-link').attr('href', imgSrc + '?' + new Date().getTime());
            }

            // Call the function every 5 seconds (5000 milliseconds)
            setInterval(refreshQrCode, 5000);
        });
    </script>
@endsection
