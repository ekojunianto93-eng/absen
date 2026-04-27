// Utility functions for form validation, PDF & Excel export, date formatting, and dynamic filtering

// Form Validation
function validateForm(form) {
    let isValid = true;
    // Example validation
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
        if (input.value.trim() === '') {
            isValid = false;
            input.classList.add('error');
        } else {
            input.classList.remove('error');
        }
    });
    return isValid;
}

// Export to PDF function
function exportToPDF(elementId) {
    const element = document.getElementById(elementId);
    const pdf = new html2pdf();
    pdf.from(element).save('export.pdf');
}

// Export to Excel function
function exportToExcel(data, filename = 'export.xlsx') {
    const worksheet = XLSX.utils.json_to_sheet(data);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
    XLSX.writeFile(workbook, filename);
}

// Date Formatting
function formatDate(date) {
    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    return new Intl.DateTimeFormat('en-US', options).format(date);
}

// Dynamic Table Filtering
function filterTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tr');
    for (let i = 1; i < rows.length; i++) { // Skip the header row
        const cells = rows[i].getElementsByTagName('td');
        let visible = false;
        for (let j = 0; j < cells.length; j++) {
            if (cells[j].innerHTML.toLowerCase().indexOf(filter) > -1) {
                visible = true;
                break;
            }
        }
        rows[i].style.display = visible ? '' : 'none';
    }
}

// Loading Indicator
function showLoadingIndicator() {
    const loader = document.createElement('div');
    loader.innerHTML = 'Loading...';
    loader.classList.add('loading-indicator');
    document.body.appendChild(loader);
}

// Confirmation Dialog
function confirmAction(message) {
    return confirm(message);
}