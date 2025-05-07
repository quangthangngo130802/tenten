<div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-6" id="contentModalLabel">Nội dung</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea name="content" class="form-control" id="content_noidung" rows="10" cols="80"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary btn-sm" id="saveButton">Lưu</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal nhỏ -->
<div class="modal fade" id="giaHanModal" tabindex="-1" aria-labelledby="giaHanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title fs-6" id="giaHanModalLabel">Gia hạn dịch vụ</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form id="giaHanForm">
                    <div class="row mb-2">

                        <input type="hidden" id="service_id" class="form-control form-control-sm"
                        value="" disabled>

                        <div class="col-6">
                            <label class="form-label small">Bắt đầu</label>
                            <input type="date" id="startDate" class="form-control form-control-sm"
                                value="" disabled>
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Kết thúc</label>
                            <input type="date" id="endDate" class="form-control form-control-sm"
                                value="" disabled>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Gia hạn thêm</label>
                        <select id="extendTime" class="form-select form-select-sm" required>
                            <option value="">-- Chọn --</option>
                            <option value="3">3 tháng</option>
                            <option value="5">5 tháng</option>
                            <option value="12">1 năm</option>
                            <option value="24">2 năm</option>
                            <option value="36">3 năm</option>
                            <option value="48">4 năm</option>
                            <option value="60">5 năm</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Kết thúc mới</label>
                        <input type="date" id="newEndDate" class="form-control form-control-sm" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="giaHanForm" class="btn btn-success btn-sm">Lưu</button>
            </div>
        </div>
    </div>
</div>
