@extends('layout.app')

@section('title', 'Add Measurement')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-dark">Add Customer Measurement</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('measurements.store') }}" method="POST">
                        @csrf

                        @if(auth()->user()->isSuperAdmin() || auth()->user()->isReceptionist())
                        <div class="mb-3">
                            <label for="user_id" class="form-label fw-bold">Customer <span class="text-danger">*</span></label>
                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">Select Customer</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold">Measurement Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="e.g., Standard, Wedding, Casual" value="{{ old('title') }}">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <h5 class="fw-bold mt-4 mb-3">Body Measurements (in cm)</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="chest" class="form-label">Chest</label>
                                    <div class="input-group">
                                        <input type="number" step="0.5" class="form-control @error('chest') is-invalid @enderror" id="chest" name="chest" value="{{ old('chest') }}">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                    @error('chest')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="waist" class="form-label">Waist</label>
                                    <div class="input-group">
                                        <input type="number" step="0.5" class="form-control @error('waist') is-invalid @enderror" id="waist" name="waist" value="{{ old('waist') }}">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                    @error('waist')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hips" class="form-label">Hips</label>
                                    <div class="input-group">
                                        <input type="number" step="0.5" class="form-control @error('hips') is-invalid @enderror" id="hips" name="hips" value="{{ old('hips') }}">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                    @error('hips')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shoulder" class="form-label">Shoulder</label>
                                    <div class="input-group">
                                        <input type="number" step="0.5" class="form-control @error('shoulder') is-invalid @enderror" id="shoulder" name="shoulder" value="{{ old('shoulder') }}">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                    @error('shoulder')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sleeve_length" class="form-label">Sleeve Length</label>
                                    <div class="input-group">
                                        <input type="number" step="0.5" class="form-control @error('sleeve_length') is-invalid @enderror" id="sleeve_length" name="sleeve_length" value="{{ old('sleeve_length') }}">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                    @error('sleeve_length')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="torso_length" class="form-label">Torso Length</label>
                                    <div class="input-group">
                                        <input type="number" step="0.5" class="form-control @error('torso_length') is-invalid @enderror" id="torso_length" name="torso_length" value="{{ old('torso_length') }}">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                    @error('torso_length')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inseam" class="form-label">Inseam</label>
                                    <div class="input-group">
                                        <input type="number" step="0.5" class="form-control @error('inseam') is-invalid @enderror" id="inseam" name="inseam" value="{{ old('inseam') }}">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                    @error('inseam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="neck" class="form-label">Neck</label>
                                    <div class="input-group">
                                        <input type="number" step="0.5" class="form-control @error('neck') is-invalid @enderror" id="neck" name="neck" value="{{ old('neck') }}">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                    @error('neck')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label fw-bold">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_default">
                                Set as default measurement
                            </label>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fas fa-save me-2"></i>Save Measurement
                                </button>
                                <a href="{{ route('measurements.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
