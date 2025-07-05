@extends('backend.layouts.master')

@section('content')

<div class="row">
    <div class="col-sm-7">
        <div class="list-group">
            <div class="list-group-item">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Gói</label>
                    </div>
                    <div class="col-sm-9">
                        <select class="form-select" name="product_key" id="product_key">
                            @forelse ($cloudlist as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $cloud->id ? 'selected' : '' }}
                                data-price={{ $item->price }}>
                                {{ $item->package_name }}
                            </option>
                            @empty
                            <option value="">Không có gói nào</option>
                            @endforelse
                        </select>
                        {{-- <i>(*) Plan 2 được khuyên dùng khi tạo các website thông thường</i> --}}
                    </div>
                </div>
            </div>
            <div class="list-group-item">
                <div class="row">
                    <div class="col-sm-3">
                        <label>OS</label>
                    </div>
                    <div class="col-sm-9">
                        <select class="form-select" name="os" id="os">
                            @forelse ($os as $item )
                            <option value="{{ $item->name }}" data-id="{{ $item->id }}">{{ $item->name }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>
            <div class="list-group-item">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Thời gian</label>
                    </div>
                    <div class="col-sm-9">
                        <select class="form-select" name="quantity" id="quantity">
                            {{-- <option value="1" attrvalue="{{ $cloud->price }}">1 tháng ({{
                                number_format($cloud->price, 0, ',', '.') }} đ)</option>
                            <option value="6" attrvalue="{{ $cloud->price*6 }}">6 tháng ({{
                                number_format($cloud->price*6, 0, ',', '.') }} đ)</option> --}}
                            <option value="12" attrvalue="{{ $cloud->price*12 }}">1 năm ({{
                                number_format($cloud->price*12, 0, ',', '.') }})</option>
                            <option value="24" attrvalue="{{ $cloud->price*24 }}">2 năm ({{
                                number_format($cloud->price*12*2, 0, ',', '.') }} đ)</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="list-group-item">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Lựa chọn thêm</label>
                    </div>
                    <div class="col-sm-9">
                        <div class="backup_autom">
                            <div class="checkbu_area">
                                <input type="checkbox" name="checkbu_area" id="checkbu_area" value="75000">
                                <label for="checkbu_area">Tự động Backup</label>
                                <p>75.000 đ/tháng</p>
                            </div>
                            <div class="textwidget">
                                <p>Tính năng tự động back-up hàng tuần giúp dữ liệu của bạn được an toàn hơn. Bạn có
                                    thể khôi phục lại dữ liệu từ bản back-up đã được lưu trữ trong 3 tuần</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="cart">
            <h3 class="cart-title">Tổng tiền Cloud Server</h3>
            <div class="cart-inner">
                <div class="tg_bd_bt">
                    <div class="tg_cart_name">
                        {{-- <span class="cl1">CloudServer</span> --}}
                        <span class="cl2" style="color: #e76a38" id="productNameUpdate">{{ $cloud->package_name
                            }}</span>
                        <span class="cl4" id="OsNameUpdate">{{ $os[0]->name }}</span>
                    </div>
                    <div class="rate" id="productPriceUpdate">{{number_format($cloud->price, 0, ',', '.') }} đ</div>
                    <div class="py-2" id="backup" style="display: flex; justify-content: space-between">

                    </div>
                </div>
                <div class="cart-total">
                    <p>
                        <strong class="tg_fl">Tổng tiền chưa VAT: </strong>
                        <strong class="tg_fr" id="tong_price">{{number_format($cloud->price * 12, 0, ',', '.') }}
                            đ</strong>
                    </p>
                    <p>
                        <strong class="tg_fl">Tổng tiền VAT: </strong>
                        <strong class="tg_fr" id="vat">{{number_format(vat_amount($cloud->price*12), 0, ',', '.') }}</strong>
                    </p>
                    <p>
                        <strong class="tg_fl">Tổng cộng: </strong>
                        <strong class="tg_fr"><span id="tong_cong" style="color: red">{{number_format($cloud->price * 12, 0,
                                ',', '.') }}
                                đ</span></strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="text-center mt-4">
    <form action="{{ route('customer.cloud.addtocart') }}" method="post">
        @csrf
        <input type="hidden" name="product_id" id="product_id" value="{{ $cloud->id }}">
        <input type="hidden" name="numbertg" id="numbertg" value="1">
        <input type="hidden" name="time_type" id="time_type" value="month">
        <input type="hidden" name="totalprice" id="totalprice" value="{{ $cloud->price }}">
        <input type="hidden" name="issetbackup" id="issetbackup" value="0">
        <input type="hidden" name="os_id" id="os_id" value="0">
        <button class="btn btn-primary btn-lg">Tiếp tục</button>
    </form>
</div>


@endsection

@push('styles')
<style>
    .list-group-item {
        background-color: #e9f7ef;
        /* Màu nền nhẹ */
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin-bottom: 15px;
        display: inline-block !important;
    }

    .list-group-item label {
        font-weight: bold;
        color: #333;
    }

    .cart {
        /* padding: 20px; */
        /* border: 1px solid #28a745; */
        border-radius: 5px;
        background-color: #e0fff4;
        /* Màu nền cho cart */
    }

    .cart-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #ffff;
        margin-bottom: 15px;
        text-align: center;
        padding: 10px;
        background: #6fb521;
    }

    .cart-total {
        border: 1px solid #dcdcdc;
        padding: 10px 15px;
    }

    .cart-total p {
        font-weight: bold;
        display: flex;
        justify-content: space-between;


    }

    .total {
        color: #28a745;
        font-size: 1.5rem;
    }

    .cart-inner {
        padding: 20px;
    }

    .tg_cart_name span {
        font-weight: 600;
    }

    .rate {
        font-weight: bold;
        color: #dc3545;
        /* Màu đỏ cho giá */
    }

    .checkbu_area {
        display: flex;
        align-items: center;
    }

    #checkbu_area {
        margin-right: 10px;
    }

    .checkbu_area p {
        margin-left: 10px;
        color: #555;
        margin-bottom: 0px;
    }

    .checkbu_area label {
        display: inline-block;
        margin-left: 10px;
    }

    #productPriceUpdate {
        text-align: end;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const productKeySelect = document.getElementById("product_key");
    const osSelect = document.getElementById("os");
    const quantitySelect = document.getElementById("quantity");
    const backupCheckbox = document.getElementById("checkbu_area");

    const productNameUpdate = document.getElementById("productNameUpdate");
    const osNameUpdate = document.getElementById("OsNameUpdate");
    const productPriceUpdate = document.getElementById("productPriceUpdate");
    const tongPrice = document.getElementById("tong_price");
    const tongCong = document.getElementById("tong_cong");
    const backupDiv = document.getElementById("backup");

    let currentBasePrice = parseInt(productKeySelect.options[productKeySelect.selectedIndex].getAttribute("data-price")) || 0;

    // Hàm định dạng tiền tệ
    function formatCurrency(value) {
        return value.toLocaleString("vi-VN", { style: "decimal", minimumFractionDigits: 0 });
    }

    // Hàm cập nhật giá
    function updatePrice() {
        // Lấy giá trị từ các lựa chọn
        const selectedProduct = productKeySelect.options[productKeySelect.selectedIndex];
        console.log(selectedProduct.value);
        document.getElementById("product_id").value = selectedProduct.value;
        const selectedOs = osSelect.options[osSelect.selectedIndex];
        document.getElementById("os_id").value = selectedOs.getAttribute("data-id");
        const selectedQuantity = quantitySelect.options[quantitySelect.selectedIndex];

        const packageName = selectedProduct.textContent.trim();
        const osName = selectedOs.textContent.trim();

        // Cập nhật giá gói khi chọn gói mới
        // currentBasePrice = parseInt(selectedProduct.getAttribute("data-price")) || 0;

        // Tính giá theo thời gian và backup
        const months = parseInt(selectedQuantity.value) || 12;
        const basePriceForDuration = currentBasePrice * months;
        const backupPrice = backupCheckbox.checked ? parseInt(backupCheckbox.value) : 0;

        // Tính tổng tiền
        const totalPrice = basePriceForDuration + backupPrice;

        // Cập nhật hiển thị
        productNameUpdate.textContent = packageName;
        osNameUpdate.textContent = osName;
        productPriceUpdate.textContent = formatCurrency(basePriceForDuration) + " đ";
        tongPrice.textContent = formatCurrency(totalPrice) + " đ";
        tongCong.textContent = formatCurrency(totalPrice) + " đ";

        if (backupCheckbox.checked) {
            // Cập nhật nội dung backup
            document.getElementById("issetbackup").value = 1;
            backupDiv.innerHTML = `
                <span class="tg_fl">Tự động backup: </span>
                <strong class="tg_fr" id="tong_price">${formatCurrency(backupPrice)} đ</strong>
            `;
        } else {
            // Nếu không chọn backup, ẩn nội dung
            backupDiv.innerHTML = '';
            document.getElementById("issetbackup").value = 0;
        }

        document.getElementById("totalprice").value = totalPrice;
    }

    // Hàm cập nhật các tùy chọn thời gian
    function updateDurationOptions(currentBasePrice) {
        const options = quantitySelect.options;
        const selectedProduct = productKeySelect.options[productKeySelect.selectedIndex];

        // Cập nhật lại giá trị cho từng option (thời gian)
        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            const months = parseInt(option.value);

            // Cập nhật giá trị cho từng option
            const priceForDuration = currentBasePrice * months;
            option.setAttribute('attrvalue', priceForDuration);
            option.textContent = `${months} tháng (${formatCurrency(priceForDuration)} đ)`;
        }

        // Sau khi cập nhật lại các option, cần tính lại tổng giá tiền
        updatePrice();
    }

    // Hàm cập nhật lại giá sau khi thay đổi lựa chọn thời gian
    function updatePriceForSelectedDuration(selectedQuantity) {
        const months = parseInt(selectedQuantity.value) || 12;  // Sử dụng giá trị đã chọn
        const basePriceForDuration = currentBasePrice * months;

        // Tính lại tổng giá sau khi cập nhật
        const backupPrice = backupCheckbox.checked ? parseInt(backupCheckbox.value) : 0;
        const totalPrice = basePriceForDuration + backupPrice;

        // Cập nhật giá hiển thị
        productPriceUpdate.textContent = formatCurrency(basePriceForDuration) + " đ";
        tongPrice.textContent = formatCurrency(totalPrice) + " đ";
        tongCong.textContent = formatCurrency(totalPrice) + " đ";
        document.getElementById("totalprice").value = totalPrice;
    }

    // Thêm sự kiện vào các trường
    productKeySelect.addEventListener("change", function () {
        const selectedProduct = productKeySelect.options[productKeySelect.selectedIndex];
        currentBasePrice = parseInt(selectedProduct.getAttribute("data-price"))
        updateDurationOptions(currentBasePrice);  // Cập nhật lại giá trị khi chọn gói
    });

    osSelect.addEventListener("change", function () {
        updatePrice();  // Cập nhật giá khi thay đổi hệ điều hành
    });

    quantitySelect.addEventListener("change", function () {
        const selectedQuantity = quantitySelect.options[quantitySelect.selectedIndex];
        document.getElementById("numbertg").value = selectedQuantity.value;
        updatePriceForSelectedDuration(selectedQuantity);  // Cập nhật giá khi thay đổi số tháng
    });

    backupCheckbox.addEventListener("change", function () {

        updatePrice();  // Cập nhật giá khi thay đổi lựa chọn backup
    });

    // Cập nhật các lựa chọn ban đầu
    updatePrice();
});


</script>
@endpush
