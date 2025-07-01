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

                        <input type="hidden" id="service_id" class="form-control form-control-sm" value=""
                            disabled>

                        <div class="col-6">
                            <label class="form-label small">Bắt đầu</label>
                            <input type="date" id="startDate" class="form-control form-control-sm" value=""
                                disabled>
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Kết thúc</label>
                            <input type="date" id="endDate" class="form-control form-control-sm" value=""
                                disabled>
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

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="giaHanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title fs-6" id="giaHanModalLabel">Sửa đổi</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="row mb-2">

                        <input type="hidden" id="service_edit_id" class="form-control form-control-sm"
                            value="" disabled>

                        <div class="col-6">
                            <label class="form-label small">Bắt đầu</label>
                            <input type="date" id="startDate_edit" class="form-control form-control-sm"
                                value="">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Thời gian kết thúc / tháng</label>
                            <input type="number" id="endDate_edit" class="form-control form-control-sm"
                                value="" disabled>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="editForm" class="btn btn-success btn-sm">Lưu</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Đặt lại mật khẩu -->
<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-labelledby="modalResetPassword"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <form id="resetPassword">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="resetPasswordLabel">Đặt lại mật khẩu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">

                    <div class="row mb-2">

                        <input type="hidden" name="user_id" id="reset_user_id">

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Mật khẩu mới</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="new_password" name="password">
                                <i class="fa fa-eye toggle-password" toggle="#new_password" style="position:absolute; top:50%; right:10px; transform:translateY(-50%); cursor:pointer;"></i>
                            </div>
                            <div class="text-danger small" id="error-password"></div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="confirm_password" name="password_confirmation">
                                <i class="fa fa-eye toggle-password" toggle="#confirm_password" style="position:absolute; top:50%; right:10px; transform:translateY(-50%); cursor:pointer;"></i>
                            </div>
                            <div class="text-danger small" id="error-confirm"></div>
                        </div>


                    </div>


                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary btn-sm">Cập nhật mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>
