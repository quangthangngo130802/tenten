@extends('backend.layouts.master')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<!-- Modal thông báo -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header  bg-primary text-white">
                <h5 class="modal-title w-100 text-center" id="alertModalLabel">THÔNG BÁO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="alertMessage" class="text-center">Nhập tên miền cần kiểm tra</p>
            </div>
            <div class=" text-center w-100 mb-3">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Form kiểm tra tên miền -->
<h2 class="text-center my-4">KINH DOANH ONLINE - MUA NGAY TÊN MIỀN</h2>
<div class="content">
    <div class="search-box">
        <form id="domainForm" action="{{ route('customer.domain.check.domain') }}" method="post">
            @csrf
            <div class="input-container">
                <input type="text" id="domainInput" name="domain" placeholder="Nhập tên miền của bạn...">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>
        <p class="note">
            Tìm kiếm từ 2 tên miền trở lên, vui lòng nhập mỗi tên miền 1 dòng
            <span class="text-danger">( Mỗi tên miền chỉ 63 ký tự - Có thể đăng ký tối đa 30 tên miền )</span>
        </p>
    </div>
</div>
<div style="width: 75%; margin: 0px auto" style="background: white" class="p-5 card">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach ($listDomain as $key => $item )
            <div class="swiper-slide d-flex text-center justify-content-center align-items-center">
                <div>
                    <p class="m-0 pt-3">{{ $key }}</p>
                    <p class="m-0 pb-3 text-danger">{{ ceil($item['total'] / 1000) }}k</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Nút điều hướng -->
        {{-- <div class="swiper-button-next"></div> --}}
        {{-- <div class="swiper-button-prev"></div> --}}
    </div>
</div>




@endsection
@push('scripts')
<script>
    document.getElementById("domainForm").addEventListener("submit", function(event) {
        let domainInput = document.getElementById("domainInput").value.trim();

        if (domainInput === "") {
            event.preventDefault(); // Ngăn gửi form
            showAlert("Nhập tên miền cần kiểm tra");
        }
    });

    function showAlert(message) {
        document.getElementById("alertMessage").innerText = message;
        let alertModal = new bootstrap.Modal(document.getElementById("alertModal"));
        alertModal.show();
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    var swiper = new Swiper(".mySwiper", {
            slidesPerView: 5, // Mặc định hiển thị 1 slide
            spaceBetween: 10,
            loop: true,
            grabCursor: true,
            // pagination: {
            //     el: ".swiper-pagination",
            //     clickable: true,
            // },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            // breakpoints: {
            //     640: { slidesPerView: 2, spaceBetween: 20 }, // Từ 640px trở lên: 2 slides
            //     768: { slidesPerView: 3, spaceBetween: 30 }, // Từ 768px trở lên: 3 slides
            //     1024: { slidesPerView: 4, spaceBetween: 40 }  // Từ 1024px trở lên: 4 slides
            // }
        });
</script>
@endpush

@push('styles')
<style>
    .swiper {
        width: 100%;
        /* max-width: 80%; */
        /* height: 400px; */
    }

    .swiper-slide {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        background: #f3f3f3;
    }

    .content {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
    }

    .search-box {
        background: #002D5E;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 75%;
        /* Giới hạn độ rộng tối đa */
    }

    .input-container {
        display: flex;
        flex-direction: row;
        border-radius: 5px;
        overflow: hidden;
    }

    .input-container input {
        flex: 1;
        padding: 12px;
        font-size: 16px;
        border: none;
        outline: none;
    }

    .input-container button {
        background: linear-gradient(to right, #5AB4FF, #0078D7);
        color: white;
        border: none;
        padding: 12px 20px;
        font-size: 16px;
        cursor: pointer;
        white-space: nowrap;
    }

    .note {
        color: white;
        font-size: 16px;
        margin-top: 15px;
        margin-bottom: 0px !important;
        word-wrap: break-word;
    }



    @media (max-width: 480px) {
        .content {
            width: 100%;
        }

        .search-box {
            padding: 15px;
            width: 100%;
        }

        .input-container {
            flex-direction: column;
        }

        .input-container input {
            padding: 10px;
            font-size: 14px;
        }

        .input-container button {
            padding: 10px;
            font-size: 14px;
            width: 100%;
        }

        .note {
            font-size: 14px;

            display: block;
            white-space: normal;
            line-height: 1.4;
        }
    }
</style>
@endpush
