<!-- Image Upload Component for Ticket Page -->
<div class="card mt-4" id="imageUploadSection">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Upload New Images</h5>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleUploadSection()">
            <i class="bi bi-chevron-down" id="toggleIcon"></i>
        </button>
    </div>
    <div class="card-body" id="uploadContent" style="display: none;">
        <form id="imageUploadForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="ticketUuid" value="{{ $ticket->uuid }}">
            
            <div class="mb-3">
                <label for="imageFiles" class="form-label">Select Images</label>
                <input type="file" class="form-control" id="imageFiles" multiple accept="image/*" required>
                <div class="form-text">You can select up to 10 images. Supported formats: JPEG, PNG, GIF, WebP</div>
            </div>
            
            <!-- Image Preview Section -->
            <div id="imagePreviewSection" class="mb-3" style="display: none;">
                <label class="form-label">Selected Images Preview</label>
                <div id="imagePreviewContainer" class="row g-2">
                    <!-- Preview images will be inserted here -->
                </div>
                <div class="form-text mt-2">
                    <span id="selectedFilesCount">0</span> image(s) selected
                </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary" id="uploadBtn">
                    <i class="bi bi-upload"></i> Upload Images
                </button>
                <div id="uploadProgress" style="display: none;">
                    <div class="progress" style="width: 200px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <small class="text-muted mt-1 d-block">Uploading...</small>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle upload section
function toggleUploadSection() {
    const content = document.getElementById('uploadContent');
    const icon = document.getElementById('toggleIcon');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.classList.remove('bi-chevron-down');
        icon.classList.add('bi-chevron-up');
    } else {
        content.style.display = 'none';
        icon.classList.remove('bi-chevron-up');
        icon.classList.add('bi-chevron-down');
    }
}

// File to Base64 conversion
function fileToBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    });
}

// Handle form submission
document.getElementById('imageUploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const fileInput = document.getElementById('imageFiles');
    const ticketUuid = document.getElementById('ticketUuid').value;
    const uploadBtn = document.getElementById('uploadBtn');
    const progressDiv = document.getElementById('uploadProgress');
    const progressBar = progressDiv.querySelector('.progress-bar');
    
    if (!fileInput.files.length) {
        alert('Please select at least one image to upload.');
        return;
    }
    
    if (fileInput.files.length > 10) {
        alert('You can upload a maximum of 10 images at once.');
        return;
    }
    
    try {
        // Show progress
        uploadBtn.disabled = true;
        progressDiv.style.display = 'block';
        progressBar.style.width = '20%';
        
        // Convert files to base64
        const images = [];
        for (let i = 0; i < fileInput.files.length; i++) {
            const base64 = await fileToBase64(fileInput.files[i]);
            images.push(base64);
        }
        
        progressBar.style.width = '60%';
        
        // Prepare request data
        const requestData = {
            ticket_uuid: ticketUuid,
            images: images
        };
        
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value;
        
        progressBar.style.width = '80%';
        
        // Upload images
        const response = await fetch('/tickets/upload-images', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify(requestData)
        });
        
        progressBar.style.width = '100%';
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        // Success
        alert(`Successfully uploaded ${result.total_uploaded} image(s)!`);
        
        // Reset form and clear previews
        document.getElementById('imageUploadForm').reset();
        document.getElementById('imagePreviewSection').style.display = 'none';
        document.getElementById('imagePreviewContainer').innerHTML = '';
        
        // Refresh page to show new images
        window.location.reload();
        
    } catch (error) {
        console.error('Upload failed:', error);
        alert('Upload failed: ' + error.message);
    } finally {
        // Hide progress
        uploadBtn.disabled = false;
        progressDiv.style.display = 'none';
        progressBar.style.width = '0%';
    }
});

// File input validation and preview
document.getElementById('imageFiles').addEventListener('change', function(e) {
    const files = e.target.files;
    const previewSection = document.getElementById('imagePreviewSection');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const selectedFilesCount = document.getElementById('selectedFilesCount');
    
    // Clear previous previews
    previewContainer.innerHTML = '';
    
    if (files.length === 0) {
        previewSection.style.display = 'none';
        return;
    }
    
    if (files.length > 10) {
        alert('You can upload a maximum of 10 images at once.');
        e.target.value = '';
        previewSection.style.display = 'none';
        return;
    }
    
    // Check file types and sizes, and generate previews
    let validFiles = 0;
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Check file type
        if (!file.type.startsWith('image/')) {
            alert(`File "${file.name}" is not an image.`);
            e.target.value = '';
            previewSection.style.display = 'none';
            return;
        }
        
        // Check file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
            alert(`File "${file.name}" is too large. Maximum size is 10MB.`);
            e.target.value = '';
            previewSection.style.display = 'none';
            return;
        }
        
        validFiles++;
        
        // Create preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'col-md-3 col-sm-4 col-6';
            previewDiv.innerHTML = `
                <div class="card border-0 shadow-sm">
                    <div class="position-relative">
                        <img src="${e.target.result}" 
                             class="card-img-top" 
                             style="height: 120px; object-fit: cover;" 
                             alt="Preview">
                        <button type="button" 
                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 remove-image-btn" 
                                data-index="${i}"
                                style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="card-body p-2">
                        <small class="text-muted d-block text-truncate" title="${file.name}">
                            ${file.name}
                        </small>
                        <small class="text-muted">
                            ${(file.size / 1024 / 1024).toFixed(2)} MB
                        </small>
                    </div>
                </div>
            `;
            previewContainer.appendChild(previewDiv);
        };
        reader.readAsDataURL(file);
    }
    
    // Show preview section and update count
    selectedFilesCount.textContent = validFiles;
    previewSection.style.display = 'block';
    
    // Add event listeners for remove buttons
    setTimeout(() => {
        const removeButtons = previewContainer.querySelectorAll('.remove-image-btn');
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                removeSelectedImage(parseInt(this.dataset.index));
            });
        });
    }, 100);
});

// Function to remove selected image
function removeSelectedImage(indexToRemove) {
    const fileInput = document.getElementById('imageFiles');
    const files = Array.from(fileInput.files);
    
    // Create new FileList without the removed file
    const dt = new DataTransfer();
    files.forEach((file, index) => {
        if (index !== indexToRemove) {
            dt.items.add(file);
        }
    });
    
    // Update the file input
    fileInput.files = dt.files;
    
    // Trigger change event to refresh previews
    fileInput.dispatchEvent(new Event('change'));
}
</script>
