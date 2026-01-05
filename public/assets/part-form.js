// Part form submission with image upload
document.addEventListener('DOMContentLoaded', function() {
    const partForm = document.getElementById('partForm');
    if (partForm) {
        partForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const partId = document.getElementById('partId').value;

            const selectedBrands = [];
            document.querySelectorAll('input[name="brands[]"]:checked').forEach(cb => {
                selectedBrands.push(cb.value);
            });
            
            // Remove the JSON string and add individual brand values
            formData.delete('brands');
            selectedBrands.forEach(brandId => {
                formData.append('brands[]', brandId);
            });

            const url = partId ? `/api/parts/${partId}` : '/api/parts';
            
            if (partId) {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                console.log('Response:', response);
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Data:', data);
                if (data.id || data.part_number) {
                    alert('Part saved successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('partModal')).hide();
                    location.reload();
                } else {
                    console.error('Validation errors:', data.errors);
                    let errorMsg = 'Validation errors:\n';
                    for (let field in data.errors) {
                        errorMsg += field + ': ' + data.errors[field].join(', ') + '\n';
                    }
                    alert(errorMsg);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: Check browser console (F12) for details');
            });
        });
    }
});
