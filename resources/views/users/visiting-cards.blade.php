@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Staff Visiting Cards Management</h4>
                    <p class="text-muted">Manage and generate visiting card links for staff members</p>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> How it works:</h6>
                                <ul class="mb-0">
                                    <li>Each staff member gets a unique visiting card link</li>
                                    <li>No login required - fully public access</li>
                                    <li>Professional design with contact information</li>
                                    <li>QR code generation available</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-success">
                                <h6><i class="fas fa-link"></i> Link Format:</h6>
                                <code>{{ url('/') }}/staff/card/{uuid}</code><br>
                                <code>{{ url('/') }}/staff/profile/{uuid}</code>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staff as $member)
                                <tr>
                                    <td>
                                        @if($member->profile_photo)
                                            <img src="{{ asset('storage/' . $member->profile_photo) }}" 
                                                 alt="{{ $member->name }}" 
                                                 class="rounded-circle" 
                                                 width="40" height="40">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $member->name }}</strong>
                                        <br>
                                        <small class="text-muted">ID: {{ $member->uuid }}</small>
                                    </td>
                                    <td>{{ $member->email }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $member->role ?? 'Staff' }}</span>
                                    </td>
                                    <td>{{ $member->department ?? 'N/A' }}</td>
                                    <td>
                                        @if($member->isActive)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('staff.visiting-card', $member->uuid) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="View Visiting Card">
                                                <i class="fas fa-id-card"></i>
                                            </a>
                                            <a href="{{ route('staff.profile', $member->uuid) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="View Profile">
                                                <i class="fas fa-user"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-success" 
                                                    onclick="copyLink('{{ route('staff.visiting-card', $member->uuid) }}')"
                                                    title="Copy Link">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" 
                                                    onclick="shareWhatsApp('{{ $member->name }}', '{{ route('staff.visiting-card', $member->uuid) }}')"
                                                    title="Share on WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" 
                                                    onclick="editProfile({{ $member->id }})"
                                                    title="Edit Profile">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <h5>Bulk Actions</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary" onclick="generateAllLinks()">
                                    <i class="fas fa-download"></i> Download All Links
                                </button>
                                <button class="btn btn-success" onclick="generateQrCodes()">
                                    <i class="fas fa-qrcode"></i> Generate QR Codes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editProfileForm">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Staff Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="role" class="form-label">Role/Designation</label>
                        <input type="text" class="form-control" id="role" name="role" required>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department">
                    </div>
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                        <input type="url" class="form-control" id="linkedin_url" name="linkedin_url">
                    </div>
                    <div class="mb-3">
                        <label for="twitter_url" class="form-label">Twitter URL</label>
                        <input type="url" class="form-control" id="twitter_url" name="twitter_url">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="public_profile" name="public_profile">
                            <label class="form-check-label" for="public_profile">
                                Make profile public
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(function() {
        alert('Link copied to clipboard!');
    });
}

function shareWhatsApp(name, url) {
    const message = `Check out ${name}'s visiting card: ${url}`;
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

function editProfile(userId) {
    // Load user data and show modal
    $('#editProfileModal').modal('show');
}

function generateAllLinks() {
    // Generate and download all links
    fetch('/users/visiting-card-links')
        .then(response => response.json())
        .then(data => {
            let content = 'Staff Visiting Cards\n\n';
            data.forEach(staff => {
                content += `${staff.name} (${staff.role})\n`;
                content += `Card: ${staff.card_url}\n`;
                content += `Profile: ${staff.profile_url}\n\n`;
            });
            
            const blob = new Blob([content], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'staff-visiting-cards.txt';
            a.click();
        });
}

function generateQrCodes() {
    alert('QR Code generation feature will be implemented soon!');
}
</script>
@endsection
