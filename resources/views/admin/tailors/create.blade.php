@extends('admin.layouts.app')

@section('title', 'Add Tailor')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.tailors.index') }}">Tailors</a></li>
    <li class="breadcrumb-item active">Add Tailor</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-plus-circle me-2"></i>Add New Tailor</h1>
                <p class="text-muted">Create a new tailor profile</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.tailors.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Tailors
                </a>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2"></i>Tailor Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tailors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- User Selection -->
                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-bold">Select User <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">Choose a user...</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" placeholder="+92 300 1234567">
                            @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Experience Years -->
                        <div class="mb-4">
                            <label for="experience_years" class="form-label fw-bold">Years of Experience</label>
                            <input type="number" class="form-control @error('experience_years') is-invalid @enderror" 
                                   id="experience_years" name="experience_years" value="{{ old('experience_years', 0) }}" min="0" max="50">
                            @error('experience_years')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Specialization -->
                        <div class="mb-4">
                            <label for="specialization" class="form-label fw-bold">Specialization</label>
                            <select name="specialization" id="specialization" class="form-select @error('specialization') is-invalid @enderror">
                                <option value="">Select specialization...</option>
                                @foreach($specializations as $spec)
                                <option value="{{ $spec }}" {{ old('specialization') == $spec ? 'selected' : '' }}>
                                    {{ $spec }}
                                </option>
                                @endforeach
                            </select>
                            @error('specialization')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Skills -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Skills</label>
                            <div id="skills-container">
                                <div class="input-group mb-2">
                                    <input type="text" name="skills[]" class="form-control" placeholder="Enter a skill...">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeSkillField(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addSkillField()">
                                <i class="fas fa-plus me-1"></i>Add Skill
                            </button>
                        </div>

                        <!-- Bio -->
                        <div class="mb-4">
                            <label for="bio" class="form-label fw-bold">Bio</label>
                            <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" 
                                      rows="4" placeholder="Brief description about the tailor...">{{ old('bio') }}</textarea>
                            @error('bio')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hourly Rate -->
                        <div class="mb-4">
                            <label for="hourly_rate" class="form-label fw-bold">Hourly Rate (Rs.)</label>
                            <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                   id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}" placeholder="500">
                            @error('hourly_rate')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="on_leave" {{ old('status') === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Add Tailor
                            </button>
                            <a href="{{ route('admin.tailors.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="col-lg-4">
            <!-- Profile Photo Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-image me-2"></i>Profile Photo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img id="photoPreview" src="{{ asset('images/default-avatar.png') }}" 
                             alt="Profile Preview" class="img-thumbnail rounded" 
                             width="200" height="200" style="object-fit: cover;">
                        <div class="mt-3">
                            <input type="file" name="profile_image" id="profile_image" 
                                   class="form-control @error('profile_image') is-invalid @enderror"
                                   accept="image/*" onchange="previewImage(event)">
                            <small class="form-text text-muted d-block mt-2">
                                Allowed: JPEG, PNG, JPG, GIF (Max 2MB)
                            </small>
                            @error('profile_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Important Notes
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Select a user from the list to assign as tailor</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Phone number will be used for contact</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Set status to Active for immediate use</small>
                        </li>
                        <li>
                            <i class="fas fa-check text-success me-2"></i>
                            <small>Profile photo is optional</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('photoPreview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function() {
            preview.src = reader.result;
        }
        reader.readAsDataURL(file);
    }
}

function addSkillField() {
    const container = document.getElementById('skills-container');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <input type="text" name="skills[]" class="form-control" placeholder="Enter a skill...">
        <button type="button" class="btn btn-outline-danger" onclick="removeSkillField(this)">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeSkillField(button) {
    button.parentElement.remove();
}
</script>

<style>
.page-header {
    padding: 20px 0;
    border-bottom: 1px solid #e9ecef;
}

.page-title {
    font-size: 28px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.card {
    border-radius: 0.5rem;
}
</style>
@endsection
