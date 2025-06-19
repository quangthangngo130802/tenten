<!-- Modal Hosting -->
<div class="modal fade" id="modalHosting" tabindex="-1" aria-labelledby="modalHostingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHostingLabel">Thông tin Hosting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Chọn gói Hosting</label>
                        <select class="form-select">
                            <option >Chọn một gói</option>
                            @forelse ($hostings as  $hosting)
                                <option value="{{ $hosting->id }}" data-price="{{ $hosting->price }}">{{ $hosting->package_name }} - {{ number_format($hosting->price) }}đ/năm</option>
                            @empty

                            @endforelse
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên domain</label>
                        <input type="text" class="form-control" placeholder="ví dụ : tenmiencuaban.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thời hạn</label>
                        <select class="form-select" name="duration">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i}}">{{ $i }} năm</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thành tiền</label>
                        <input type="text" class="form-control" placeholder="Tổng tiền" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Domain -->
<div class="modal fade" id="modalDomain" tabindex="-1" aria-labelledby="modalDomainLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDomainLabel">Thông tin Domain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                Nội dung chi tiết dịch vụ Domain tại đây.
            </div>
        </div>
    </div>
</div>

<!-- Modal Email -->
<div class="modal fade" id="modalEmail" tabindex="-1" aria-labelledby="modalEmailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEmailLabel">Thông tin Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Chọn gói Hosting</label>
                        <select class="form-select">
                            <option >Chọn một gói</option>
                            @forelse ($emails as  $email)
                                <option value="{{ $email->id }}" ata-price="{{ $email->price }}">{{ $email->package_name }} - {{ number_format($email->price) }}đ/năm</option>
                            @empty

                            @endforelse
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên domain</label>
                        <input type="text" class="form-control" placeholder="ví dụ : tenmiencuaban.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thời hạn</label>
                        <select class="form-select" name="duration">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i}}">{{ $i }} năm</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thành tiền</label>
                        <input type="text" class="form-control" placeholder="Tổng tiền" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cloud -->
<div class="modal fade" id="modalCloud" tabindex="-1" aria-labelledby="modalCloudLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCloudLabel">Thông tin Cloud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Chọn gói Hosting</label>
                        <select class="form-select">
                            <option >Chọn một gói</option>
                            @forelse ($clouds as  $cloud)
                                <option value="{{ $cloud->id }}" ata-price="{{ $cloud->price }}">{{ $cloud->package_name }} - {{ number_format($cloud->price) }}đ/năm</option>
                            @empty

                            @endforelse
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thời hạn</label>
                        <select class="form-select" name="duration">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i}}">{{ $i }} năm</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around">
                            <input type="checkbox" name="checkbu_area" id="checkbu_area" value="75000">
                            <label for="checkbu_area">Tự động Backup</label>
                            <span>75.000 đ/tháng</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thành tiền</label>
                        <input type="text" class="form-control" placeholder="Tổng tiền" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
