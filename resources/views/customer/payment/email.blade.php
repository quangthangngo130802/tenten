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
                            @forelse ($emaillist as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $email->id ? 'selected' : '' }}
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
                        <label>Tên miền</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" id="domain" class="form-control" placeholder="Nhập tên miền" />
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
                            <option value="12" attrvalue="{{ $email->price*12 }}">1 năm ({{
                                number_format($email->price*12, 0, ',', '.') }} đ)</option>
                            <option value="24" attrvalue="{{ $email->price*24 }}">2 năm ({{
                                number_format($email->price*24, 0, ',', '.') }} đ)</option>
                            <option value="36" attrvalue="{{ $email->price*36 }}">3 năm ({{
                                number_format($email->price*36, 0, ',', '.') }})</option>
                            <option value="48" attrvalue="{{ $email->price*48 }}">4 năm ({{
                                number_format($email->price*48, 0, ',', '.') }} đ)</option>
                            <option value="60" attrvalue="{{ $email->price*60 }}">5 năm ({{
                                number_format($email->price*60, 0, ',', '.') }} đ)</option>
                        </select>
                    </div>
                </div>
            </div>


            {{-- <div class="list-group-item">
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
            </div> --}}
        </div>
    </div>
    <div class="col-sm-5">
        <div class="cart">
            <h3 class="cart-title">Tổng tiền Email Server</h3>
            <div class="cart-inner">
                <div class="tg_bd_bt">
                    <div class="tg_cart_name">
                        {{-- <span class="cl1">CloudServer</span> --}}
                        <span class="cl2" style="color: #e76a38" id="productNameUpdate">{{ $email->package_name
                            }}</span>
                        {{-- <span class="cl4" id="OsNameUpdate">{{ $os[0]->name }}</span> --}}
                    </div>
                    <div class="rate" id="productPriceUpdate">{{number_format($email->price*12, 0, ',', '.') }} đ</div>
                    <div class="py-2" id="backup" style="display: flex; justify-content: space-between">

                    </div>
                </div>
                <div class="cart-total">
                    <p>
                        <strong class="tg_fl">Tổng tiền chưa VAT: </strong>
                        <strong class="tg_fr" id="tong_price">{{number_format($email->price * 12, 0, ',', '.') }}
                            đ</strong>
                    </p>
                    {{-- <p>
                        <strong class="tg_fl">Tổng tiền VAT: </strong>
                        <strong class="tg_fr" id="vat">600.000 đ</strong>
                    </p> --}}
                    <p>
                        <strong class="tg_fl">Tổng cộng: </strong>
                        <strong class="tg_fr"><span id="tong_cong" style="color: red">{{number_format($email->price*12,
                                0,
                                ',', '.') }}
                                đ</span></strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="text-center mt-4">
    <form action="{{ route('customer.email.addtocart') }}" method="post">
        @csrf
        <input type="hidden" name="product_id" id="product_id" value="{{ $email->id }}">
        <input type="hidden" name="numbertg" id="numbertg" value="12">
        <input type="hidden" name="time_type" id="time_type" value="month">
        <input type="hidden" name="domain" id="domain_new">
        <input type="hidden" name="totalprice" id="totalprice" value="{{ $email->price }}">
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
    const quantitySelect = document.getElementById("quantity");

    const productNameUpdate = document.getElementById("productNameUpdate");

    const productPriceUpdate = document.getElementById("productPriceUpdate");
    const tongPrice = document.getElementById("tong_price");
    const tongCong = document.getElementById("tong_cong");


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

        const selectedQuantity = quantitySelect.options[quantitySelect.selectedIndex];

        const packageName = selectedProduct.textContent.trim();

        const months = parseInt(selectedQuantity.value) || 1;
        const basePriceForDuration = currentBasePrice * months;


        const totalPrice = basePriceForDuration ;

        // Cập nhật hiển thị
        productNameUpdate.textContent = packageName;

        productPriceUpdate.textContent = formatCurrency(basePriceForDuration) + " đ";
        tongPrice.textContent = formatCurrency(totalPrice) + " đ";
        tongCong.textContent = formatCurrency(totalPrice) + " đ";


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
        const months = parseInt(selectedQuantity.value) || 1;  // Sử dụng giá trị đã chọn
        const basePriceForDuration = currentBasePrice * months;

        // Tính lại tổng giá sau khi cập nhật
        const totalPrice = basePriceForDuration;

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
        updateDurationOptions(currentBasePrice);
    });

    quantitySelect.addEventListener("change", function () {
        const selectedQuantity = quantitySelect.options[quantitySelect.selectedIndex];
        document.getElementById("numbertg").value = selectedQuantity.value;
        updatePriceForSelectedDuration(selectedQuantity);  // Cập nhật giá khi thay đổi số tháng
    });
    // Cập nhật các lựa chọn ban đầu
    updatePrice();



});
document.getElementById('domain').addEventListener('input', function () {
        // Gán giá trị nhập vào trường thứ hai
        document.getElementById('domain_new').value = this.value;
    });

</script>
@endpush
