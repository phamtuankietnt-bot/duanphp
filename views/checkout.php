<?php 
$page_title = 'Thanh toán - ShoeStore';
include 'views/layout/header.php'; 
?>

<main class="main">
    <div class="container">
        <div class="page-header">
            <h1>Thanh toán</h1>
            <div class="breadcrumb">
                <a href="index.php">Trang chủ</a> > 
                <a href="index.php?action=cart">Giỏ hàng</a> > 
                <span>Thanh toán</span>
            </div>
        </div>

        <div class="checkout-content">
            <div class="checkout-form-section">
                <h2>Thông tin giao hàng</h2>
                
                <?php if (isset($error)): ?>
                    <div class="error-message">
                        <p><?php echo $error; ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?action=checkout" class="checkout-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="customer_name">Họ và tên *</label>
                            <input type="text" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_email">Email *</label>
                            <input type="email" id="customer_email" name="customer_email" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="customer_phone">Số điện thoại *</label>
                            <input type="tel" id="customer_phone" name="customer_phone" required>
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Phương thức thanh toán *</label>
                            <select id="payment_method" name="payment_method" required>
                                <option value="">Chọn phương thức thanh toán</option>
                                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                                <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                                <option value="momo">Ví MoMo</option>
                                <option value="zalopay">Ví ZaloPay</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="customer_address">Địa chỉ giao hàng *</label>
                        <div class="address-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="province">Tỉnh/Thành phố *</label>
                                    <select id="province" name="province" required>
                                        <option value="">Chọn tỉnh/thành phố</option>
                                        <option value="Hà Nội">Hà Nội</option>
                                        <option value="TP. Hồ Chí Minh">TP. Hồ Chí Minh</option>
                                        <option value="Đà Nẵng">Đà Nẵng</option>
                                        <option value="Hải Phòng">Hải Phòng</option>
                                        <option value="Cần Thơ">Cần Thơ</option>
                                        <option value="An Giang">An Giang</option>
                                        <option value="Bà Rịa - Vũng Tàu">Bà Rịa - Vũng Tàu</option>
                                        <option value="Bắc Giang">Bắc Giang</option>
                                        <option value="Bắc Kạn">Bắc Kạn</option>
                                        <option value="Bạc Liêu">Bạc Liêu</option>
                                        <option value="Bắc Ninh">Bắc Ninh</option>
                                        <option value="Bến Tre">Bến Tre</option>
                                        <option value="Bình Định">Bình Định</option>
                                        <option value="Bình Dương">Bình Dương</option>
                                        <option value="Bình Phước">Bình Phước</option>
                                        <option value="Bình Thuận">Bình Thuận</option>
                                        <option value="Cà Mau">Cà Mau</option>
                                        <option value="Cao Bằng">Cao Bằng</option>
                                        <option value="Đắk Lắk">Đắk Lắk</option>
                                        <option value="Đắk Nông">Đắk Nông</option>
                                        <option value="Điện Biên">Điện Biên</option>
                                        <option value="Đồng Nai">Đồng Nai</option>
                                        <option value="Đồng Tháp">Đồng Tháp</option>
                                        <option value="Gia Lai">Gia Lai</option>
                                        <option value="Hà Giang">Hà Giang</option>
                                        <option value="Hà Nam">Hà Nam</option>
                                        <option value="Hà Tĩnh">Hà Tĩnh</option>
                                        <option value="Hải Dương">Hải Dương</option>
                                        <option value="Hậu Giang">Hậu Giang</option>
                                        <option value="Hòa Bình">Hòa Bình</option>
                                        <option value="Hưng Yên">Hưng Yên</option>
                                        <option value="Khánh Hòa">Khánh Hòa</option>
                                        <option value="Kiên Giang">Kiên Giang</option>
                                        <option value="Kon Tum">Kon Tum</option>
                                        <option value="Lai Châu">Lai Châu</option>
                                        <option value="Lâm Đồng">Lâm Đồng</option>
                                        <option value="Lạng Sơn">Lạng Sơn</option>
                                        <option value="Lào Cai">Lào Cai</option>
                                        <option value="Long An">Long An</option>
                                        <option value="Nam Định">Nam Định</option>
                                        <option value="Nghệ An">Nghệ An</option>
                                        <option value="Ninh Bình">Ninh Bình</option>
                                        <option value="Ninh Thuận">Ninh Thuận</option>
                                        <option value="Phú Thọ">Phú Thọ</option>
                                        <option value="Phú Yên">Phú Yên</option>
                                        <option value="Quảng Bình">Quảng Bình</option>
                                        <option value="Quảng Nam">Quảng Nam</option>
                                        <option value="Quảng Ngãi">Quảng Ngãi</option>
                                        <option value="Quảng Ninh">Quảng Ninh</option>
                                        <option value="Quảng Trị">Quảng Trị</option>
                                        <option value="Sóc Trăng">Sóc Trăng</option>
                                        <option value="Sơn La">Sơn La</option>
                                        <option value="Tây Ninh">Tây Ninh</option>
                                        <option value="Thái Bình">Thái Bình</option>
                                        <option value="Thái Nguyên">Thái Nguyên</option>
                                        <option value="Thanh Hóa">Thanh Hóa</option>
                                        <option value="Thừa Thiên Huế">Thừa Thiên Huế</option>
                                        <option value="Tiền Giang">Tiền Giang</option>
                                        <option value="Trà Vinh">Trà Vinh</option>
                                        <option value="Tuyên Quang">Tuyên Quang</option>
                                        <option value="Vĩnh Long">Vĩnh Long</option>
                                        <option value="Vĩnh Phúc">Vĩnh Phúc</option>
                                        <option value="Yên Bái">Yên Bái</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="district">Quận/Huyện *</label>
                                    <select id="district" name="district" required disabled>
                                        <option value="">Chọn quận/huyện</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="ward">Phường/Xã *</label>
                                    <select id="ward" name="ward" required disabled>
                                        <option value="">Chọn phường/xã</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="street_address">Số nhà, tên đường *</label>
                                    <input type="text" id="street_address" name="street_address" required 
                                           placeholder="Số nhà, tên đường" maxlength="200">
                                </div>
                            </div>
                        </div>
                        <small class="form-help">Vui lòng chọn địa chỉ cụ thể để đảm bảo giao hàng chính xác</small>
                    </div>

                    <div class="form-group">
                        <label for="order_notes">Ghi chú đơn hàng</label>
                        <textarea id="order_notes" name="order_notes" rows="2" placeholder="Ghi chú thêm cho đơn hàng (không bắt buộc)"></textarea>
                    </div>

                    <div class="form-actions">
                        <a href="index.php?action=cart" class="btn btn-secondary">Quay lại giỏ hàng</a>
                        <button type="submit" class="btn btn-primary btn-large">Đặt hàng</button>
                    </div>
                </form>
            </div>

            <div class="order-summary-section">
                <h2>Tóm tắt đơn hàng</h2>
                <div class="order-summary">
                    <div class="order-items">
                        <?php foreach($cart_items as $item): ?>
                        <div class="order-item">
                            <div class="item-image">
                                <img src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                            <div class="item-details">
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p class="item-category"><?php echo isset($item['category']) ? htmlspecialchars($item['category']) : 'Sneakers'; ?></p>
                                <div class="item-quantity-price">
                                    <span class="quantity">Số lượng: <?php echo $item['quantity']; ?></span>
                                    <span class="price"><?php echo number_format($item['price'] * $item['quantity']); ?> VNĐ</span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="order-totals">
                        <div class="total-row">
                            <span>Tạm tính:</span>
                            <span><?php echo number_format($total_amount); ?> VNĐ</span>
                        </div>
                        <div class="total-row">
                            <span>Phí vận chuyển:</span>
                            <span>Miễn phí</span>
                        </div>
                        <div class="total-row final-total">
                            <span><strong>Tổng cộng:</strong></span>
                            <span><strong><?php echo number_format($total_amount); ?> VNĐ</strong></span>
                        </div>
                    </div>

                    <div class="shipping-info">
                        <h3>Thông tin giao hàng</h3>
                        <ul>
                            <li><i class="fas fa-truck"></i> Giao hàng miễn phí toàn quốc</li>
                            <li><i class="fas fa-clock"></i> Thời gian giao hàng: 2-5 ngày làm việc</li>
                            <li><i class="fas fa-shield-alt"></i> Đổi trả trong 30 ngày</li>
                            <li><i class="fas fa-phone"></i> Hỗ trợ: 0123 456 789</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Dữ liệu địa chỉ Việt Nam (simplified)
const addressData = {
    "Hà Nội": {
        "Quận Ba Đình": ["Phường Cống Vị", "Phường Điện Biên", "Phường Đội Cấn", "Phường Giảng Võ", "Phường Kim Mã", "Phường Liễu Giai", "Phường Ngọc Hà", "Phường Ngọc Khánh", "Phường Nguyễn Trung Trực", "Phường Phúc Xá", "Phường Quán Thánh", "Phường Thành Công", "Phường Thụy Khuê", "Phường Vĩnh Phú"],
        "Quận Hoàn Kiếm": ["Phường Chương Dương", "Phường Cửa Đông", "Phường Cửa Nam", "Phường Đồng Xuân", "Phường Hàng Bạc", "Phường Hàng Bồ", "Phường Hàng Bông", "Phường Hàng Buồm", "Phường Hàng Đào", "Phường Hàng Gai", "Phường Hàng Mã", "Phường Hàng Trống", "Phường Lý Thái Tổ", "Phường Phúc Tân", "Phường Phúc Xá", "Phường Tràng Tiền"],
        "Quận Tây Hồ": ["Phường Bưởi", "Phường Nhật Tân", "Phường Phú Thượng", "Phường Quảng An", "Phường Thụy Khuê", "Phường Tứ Liên", "Phường Xuân La", "Phường Yên Phụ"]
    },
    "TP. Hồ Chí Minh": {
        "Quận 1": ["Phường Bến Nghé", "Phường Bến Thành", "Phường Cầu Kho", "Phường Cầu Ông Lãnh", "Phường Cô Giang", "Phường Đa Kao", "Phường Nguyễn Cư Trinh", "Phường Nguyễn Thái Bình", "Phường Phạm Ngũ Lão", "Phường Tân Định", "Phường Tân Định"],
        "Quận 2": ["Phường An Phú", "Phường An Khánh", "Phường Bình An", "Phường Bình Khánh", "Phường Bình Trưng Đông", "Phường Bình Trưng Tây", "Phường Cát Lái", "Phường Thạnh Mỹ Lợi", "Phường Thảo Điền", "Phường Thủ Thiêm"],
        "Quận 3": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường Võ Thị Sáu"]
    },
    "Đà Nẵng": {
        "Quận Hải Châu": ["Phường Hải Châu I", "Phường Hải Châu II", "Phường Phước Ninh", "Phường Hòa Thuận Tây", "Phường Hòa Thuận Đông", "Phường Nam Dương", "Phường Bình Hiên", "Phường Bình Thuận", "Phường Hòa Cường Bắc", "Phường Hòa Cường Nam"],
        "Quận Thanh Khê": ["Phường Thanh Khê Tây", "Phường Thanh Khê Đông", "Phường Xuân Hà", "Phường Tân Chính", "Phường Chính Gián", "Phường Vĩnh Trung", "Phường Thạc Gián", "Phường An Khê", "Phường Hòa Khê"],
        "Quận Sơn Trà": ["Phường An Hải Bắc", "Phường An Hải Nam", "Phường An Hải Tây", "Phường An Hải Đông", "Phường Mân Thái", "Phường Nại Hiên Đông", "Phường Phước Mỹ", "Phường Thọ Quang"]
    }
};

// Simple test function
function testAddressForm() {
    console.log('Testing address form...');
    
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    const streetInput = document.getElementById('street_address');
    
    console.log('Province select:', provinceSelect);
    console.log('District select:', districtSelect);
    console.log('Ward select:', wardSelect);
    console.log('Street input:', streetInput);
    
    if (provinceSelect && districtSelect && wardSelect && streetInput) {
        console.log('All elements found!');
        
        // Test province change
        provinceSelect.addEventListener('change', function() {
            console.log('Province changed to:', this.value);
            
            // Clear other selects
            districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            
            if (this.value && addressData[this.value]) {
                console.log('Found data for province:', this.value);
                districtSelect.disabled = false;
                
                // Add districts
                Object.keys(addressData[this.value]).forEach(function(district) {
                    const option = document.createElement('option');
                    option.value = district;
                    option.textContent = district;
                    districtSelect.appendChild(option);
                });
                
                console.log('Added districts, total options:', districtSelect.options.length);
            } else {
                console.log('No data for province:', this.value);
                districtSelect.disabled = true;
                wardSelect.disabled = true;
            }
        });
        
        // Test district change
        districtSelect.addEventListener('change', function() {
            console.log('District changed to:', this.value);
            
            const province = provinceSelect.value;
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            
            if (province && this.value && addressData[province] && addressData[province][this.value]) {
                console.log('Found data for district:', this.value);
                wardSelect.disabled = false;
                
                // Add wards
                addressData[province][this.value].forEach(function(ward) {
                    const option = document.createElement('option');
                    option.value = ward;
                    option.textContent = ward;
                    wardSelect.appendChild(option);
                });
                
                console.log('Added wards, total options:', wardSelect.options.length);
            } else {
                console.log('No data for district:', this.value);
                wardSelect.disabled = true;
            }
        });
        
        // Create hidden input for combined address
        const addressForm = document.querySelector('.address-form');
        if (addressForm) {
            const addressInput = document.createElement('input');
            addressInput.type = 'hidden';
            addressInput.name = 'customer_address';
            addressInput.id = 'customer_address';
            addressForm.appendChild(addressInput);
            console.log('Hidden address input created');
        }
        
        // Function to update combined address
        function updateAddress() {
            const province = provinceSelect.value;
            const district = districtSelect.value;
            const ward = wardSelect.value;
            const street = streetInput.value;
            
            const addressInput = document.getElementById('customer_address');
            if (addressInput) {
                if (province && district && ward && street) {
                    const fullAddress = street + ', ' + ward + ', ' + district + ', ' + province;
                    addressInput.value = fullAddress;
                    console.log('Address updated:', fullAddress);
                } else {
                    addressInput.value = '';
                    console.log('Address cleared');
                }
            }
        }
        
        // Add change listeners for address update
        wardSelect.addEventListener('change', updateAddress);
        streetInput.addEventListener('input', updateAddress);
        
        console.log('Event listeners added successfully!');
    } else {
        console.error('Some elements not found!');
    }
}

// Run when page loads
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', testAddressForm);
} else {
    testAddressForm();
}
</script>

<?php include 'views/layout/footer.php'; ?>
