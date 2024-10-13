
const numberInput = document.getElementById('numberInput');

// Hàm định dạng số có dấu chấm
function formatNumberWithDots(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Xử lý khi người dùng nhập liệu vào input
numberInput.addEventListener('input', function (e) {
    // Lấy giá trị hiện tại và loại bỏ các dấu chấm cũ
    let value = e.target.value.replace(/\./g, '');

    // Chỉ giữ lại các ký tự số
    value = value.replace(/\D/g, '');

    // Định dạng lại với dấu chấm
    e.target.value = formatNumberWithDots(value);
});


function updateFileList(input) {
    const fileCount = input.files.length;
    const fileCountElement = document.getElementById('file-count');
    fileCountElement.textContent = fileCount > 0 ? `${fileCount} files` : '0 files';

    const fileListElement = document.getElementById('file-list');
    fileListElement.innerHTML = '';

    for (let i = 0; i < fileCount; i++) {
    const fileName = input.files[i].name;
    const fileItem = document.createElement('li');
    fileItem.textContent = fileName;
    fileListElement.appendChild(fileItem);
    }
}

