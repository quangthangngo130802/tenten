@extends('backend.layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ isset($qrCode) ? route('qrcode.save', $qrCode->id) : route('qrcode.save') }}" method="POST">
                @csrf
                <h5 class="section-title">{{ isset($qrCode) ? 'Cập nhật QR Code' : 'Tạo QR Code' }}</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="qr_name" class="form-label">Tên QR</label>
                            <input type="text" class="form-control @error('qr_name') is-invalid @enderror" id="qr_name"
                                name="qr_name" placeholder="Nhập tên QR"
                                value="{{ old('qr_name', isset($qrCode) ? $qrCode->qr_name : '') }}" />
                            @error('qr_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="qr_link" class="form-label">Link QR</label>
                            <input type="url" class="form-control @error('qr_link') is-invalid @enderror" id="qr_link"
                                name="qr_link" placeholder="Nhập link QR" required
                                value="{{ old('qr_link', isset($qrCode) ? $qrCode->qr_link : '') }}" />
                            @error('qr_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                {{ isset($qrCode) ? 'Cập nhật' : 'Tạo QR Code' }}
                            </button>
                        </div>
                    </div>

                    <!-- Cột hiển thị QR Code -->
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <div id="qrcode" class="p-3 border" style="min-width: 250px; min-height: 250px;">
                            @if (isset($qrCode))
                                <img id="qrImage" src="{{ $qrCode->default_link }}" alt="QR Code"
                                    style="cursor: pointer;">
                            @else
                                <img src="" alt="">
                            @endif
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
@push('scripts')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            // alert('1');
            $("#qr_link").on("input", function() {
                // alert(1);
                let qrName = $("#qr_name").val();
                let qrLink = $(this).val();

                if (qrLink.length > 0) {
                    $.ajax({
                        url: "{{ route('qrcode.imageurl') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            qr_name: qrName,
                            qr_link: qrLink
                        },
                        success: function(response) {
                            if (response.success) {
                                $("#qrcode img").attr("src", response.qr_code_url).show();
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    $("#qrcode img").hide();
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const qrImage = document.getElementById("qrImage");

            if (qrImage) {
                qrImage.addEventListener("click", function() {
                    fetch(qrImage.src)
                        .then(response => response.blob())
                        .then(blob => {
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement("a");
                            a.href = url;
                            a.download = "qr_code.png"; // Tên file khi tải về
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            window.URL.revokeObjectURL(url);
                        })
                        .catch(error => console.error("Lỗi tải ảnh:", error));
                });
            }
        });
    </script>
@endpush
