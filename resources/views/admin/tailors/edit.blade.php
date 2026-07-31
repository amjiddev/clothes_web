@extends('admin.layouts.app')

@section('title', 'Edit Tailor - ' . $tailor->user->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.tailors.index') }}">Tailors</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title"><i class="fas fa-user-edit me-2"></i>Edit Tailor</h1>
                <p class="text-muted">Update tailor information</p>
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
                        <i class="fas fa-user-circle me-2"></i>Tailor Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tailors.update', $tailor) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- User Info (Read-only) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Assigned User</label>
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-user me-2"></i>{{ $tailor->user->name }} ({{ $tailor->user->email }})
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $tailor->phone) }}" placeholder="+92 300 1234567">
                            @error('phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Experience Years -->
                        <div class="mb-4">
                            <label for="experience_years" class="form-label fw-bold">Years of Experience</label>
                            <input type="number" class="form-control @error('experience_years') is-invalid @enderror" 
                                   id="experience_years" name="experience_years" value="{{ old('experience_years', $tailor->experience_years) }}" min="0" max="50">
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
                                <option value="{{ $spec }}" {{ old('specialization', $tailor->specialization) == $spec ? 'selected' : '' }}>
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
                                @if($tailor->skills && is_array($tailor->skills))
                                    @foreach($tailor->skills as $skill)
                                    <div class="input-group mb-2">
                                        <input type="text" name="skills[]" class="form-control" placeholder="Enter a skill..." value="{{ $skill }}">
                                        <button type="button" class="btn btn-outline-danger" onclick="removeSkillField(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                @else
                                <div class="input-group mb-2">
                                    <input type="text" name="skills[]" class="form-control" placeholder="Enter a skill...">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeSkillField(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addSkillField()">
                                <i class="fas fa-plus me-1"></i>Add Skill
                            </button>
                        </div>

                        <!-- Bio -->
                        <div class="mb-4">
                            <label for="bio" class="form-label fw-bold">Bio</label>
                            <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" 
                                      rows="4" placeholder="Brief description about the tailor...">{{ old('bio', $tailor->bio) }}</textarea>
                            @error('bio')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hourly Rate -->
                        <div class="mb-4">
                            <label for="hourly_rate" class="form-label fw-bold">Hourly Rate (Rs.)</label>
                            <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                   id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $tailor->hourly_rate) }}" placeholder="500">
                            @error('hourly_rate')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $tailor->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $tailor->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="on_leave" {{ old('status', $tailor->status) === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
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
                        <img id="photoPreview" src="{{ $tailor->profile_image_url ?? asset('images/default-avatar.png') }}" 
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

            <!-- Statistics Card -->
            <div class="card border-0 shadow mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Tailor Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <h3 class="text-primary">{{ $tailor->user->name }}</h3>
                            <small class="text-muted">{{ $tailor->user->email }}</small>
                        </div>
                        <div class="col-6 mb-3">
                            <p class="text-muted mb-1"><small>Experience</small></p>
                            <h4 class="text-info">{{ $tailor->experience_years ?? 0 }} yrs</h4>
                        </div>
                        <div class="col-6 mb-3">
                            <p class="text-muted mb-1"><small>Rating</small></p>
                            <h4 class="text-warning">
                                <i class="fas fa-star"></i> {{ number_format($tailor->average_rating ?? 0, 1) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Card -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Information
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Status:</strong>
                            @if($tailor->status === 'active')
                            <span class="badge bg-success">Active</span>
                            @elseif($tailor->status === 'on_leave')
                            <span class="badge bg-warning">On Leave</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </li>
                        <li class="mb-2">
                            <strong>Specialization:</strong><br>
                            {{ $tailor->specialization ?? 'Not specified' }}
                        </li>
                        <li>
                            <strong>Phone:</strong><br>
                            {{ $tailor->phone ?? 'Not provided' }}
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
