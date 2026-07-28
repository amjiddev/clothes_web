@extends('frontend.layouts.app')

@section('title', 'Feedback Survey - CLOTHES STORE')

@section('content')
<style>
    .survey-container {
        background: #f8f5ef;
        padding: 60px 0;
        min-height: calc(100vh - 200px);
    }

    .survey-content {
        background: white;
        border-radius: 10px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--accent-gold);
    }

    .survey-content h1 {
        font-size: 2.5rem;
        font-family: 'Playfair Display', serif;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
        border-bottom: 3px solid var(--accent-gold);
        padding-bottom: 1rem;
    }

    .survey-intro {
        text-align: center;
        margin-bottom: 3rem;
    }

    .survey-intro p {
        font-size: 1.1rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .survey-form {
        max-width: 100%;
    }

    .form-section {
        margin-bottom: 3rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .form-section h3 {
        font-size: 1.3rem;
        color: var(--primary-dark);
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.8rem;
        color: var(--primary-dark);
        font-weight: 500;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 5px;
        font-size: 1rem;
        font-family: 'Poppins', sans-serif;
        transition: all 0.3s ease;
    }

    .form-group input[type="text"]:focus,
    .form-group input[type="email"]:focus,
    .form-group input[type="tel"]:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .rating-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .rating-option {
        display: none;
    }

    .star-rating {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .star-label {
        font-size: 2.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .rating-option:checked ~ .star-label,
    .star-label:hover,
    .star-label:hover ~ .star-label {
        color: var(--accent-gold);
        text-shadow: 0 2px 4px rgba(212, 175, 55, 0.4);
        transform: scale(1.2);
    }

    .star-label:hover {
        transform: scale(1.3);
    }

    .checkbox-group,
    .radio-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .checkbox-item,
    .radio-item {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .checkbox-item input[type="checkbox"],
    .radio-item input[type="radio"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: var(--accent-gold);
    }

    .checkbox-item label,
    .radio-item label {
        cursor: pointer;
        color: var(--text-muted);
        margin: 0;
    }

    .required-field {
        color: #d32f2f;
        margin-left: 0.3rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 3rem;
    }

    .btn-submit {
        background: var(--accent-gold);
        color: var(--primary-dark);
        padding: 14px 40px;
        border: none;
        border-radius: 5px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: #FFE066;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .btn-reset {
        background: transparent;
        color: var(--primary-dark);
        padding: 14px 40px;
        border: 2px solid var(--primary-dark);
        border-radius: 5px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        background: var(--primary-dark);
        color: white;
    }

    .success-message {
        background: #D4EDDA;
        border: 2px solid #28A745;
        color: #155724;
        padding: 1.5rem;
        border-radius: 5px;
        margin-bottom: 2rem;
        display: none;
        text-align: center;
    }

    .success-message.show {
        display: block;
    }

    .error-message {
        background: #F8D7DA;
        border: 2px solid #DC3545;
        color: #721C24;
        padding: 1.5rem;
        border-radius: 5px;
        margin-bottom: 2rem;
        display: none;
    }

    .error-message.show {
        display: block;
    }

    .info-banner {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        color: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        border-left: 4px solid var(--accent-gold);
    }

    .info-banner h2 {
        color: var(--accent-gold);
        margin-top: 0;
        margin-bottom: 0.8rem;
    }

    .info-banner p {
        color: white;
        margin: 0;
    }

    .progress-bar {
        background: #e0e0e0;
        height: 8px;
        border-radius: 4px;
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .progress-fill {
        background: var(--accent-gold);
        height: 100%;
        width: 0%;
        transition: width 0.3s ease;
    }

    @media (max-width: 768px) {
        .survey-content {
            padding: 25px;
        }

        .survey-content h1 {
            font-size: 1.8rem;
        }

        .rating-group {
            justify-content: space-between;
        }

        .rating-label {
            width: 45px;
            height: 45px;
            font-size: 0.9rem;
        }

        .checkbox-group,
        .radio-group {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit,
        .btn-reset {
            width: 100%;
        }
    }
</style>

<div class="survey-container">
    <div class="container">
        <div class="survey-content">
            <div class="survey-intro">
                <h1><i class="fas fa-comments"></i> Your Feedback Matters</h1>
                <p>Help us improve our services by sharing your experience with CLOTHES STORE</p>
                <p style="font-size: 0.95rem; color: #999;">Average time to complete: 5-7 minutes</p>
            </div>

            <div class="info-banner">
                <h2><i class="fas fa-gift"></i> Feedback Incentive</h2>
                <p>Complete this survey and get a chance to win exciting rewards! Your feedback helps us serve you better.</p>
            </div>

            <div class="info-banner" style="background: linear-gradient(135deg, #2d5016 0%, #3a6b1f 100%); border-left-color: #28a745;">
                <h2 style="color: #28a745;"><i class="fas fa-trophy"></i> Monthly Raffle Draw</h2>
                <p>Every month, we randomly select one lucky feedback participant to win exclusive prizes!</p>
                <p style="margin: 1rem 0 0 0; font-size: 0.95rem; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 1rem;">
                    <strong>How it works:</strong> Submit your feedback this month → Automatic entry into our monthly raffle → Winner announced on the 1st of next month → Prize delivery within 7 days
                </p>
            </div>

            <div class="progress-bar">
                <div class="progress-fill" style="width: 0%;"></div>
            </div>

            <div id="successMessage" class="success-message">
                <i class="fas fa-check-circle"></i>
                <p><strong>Thank you!</strong> Your feedback has been submitted successfully. We appreciate your time!</p>
            </div>

            <div id="errorMessage" class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <p><strong>Error!</strong> Please fill in all required fields.</p>
            </div>

            <form id="surveyForm" class="survey-form" onsubmit="handleSubmit(event)">
                <!-- Personal Information -->
                <div class="form-section">
                    <h3><i class="fas fa-user"></i> Personal Information</h3>

                    <div class="form-group">
                        <label for="fullName">Full Name <span class="required-field">*</span></label>
                        <input type="text" id="fullName" name="fullName" placeholder="Enter your full name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required-field">*</span></label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
                    </div>

                    <div class="form-group">
                        <label for="orderID">Order ID (if applicable)</label>
                        <input type="text" id="orderID" name="orderID" placeholder="e.g., ORD-12345">
                    </div>
                </div>

                <!-- Product Categories -->
                <div class="form-section">
                    <h3><i class="fas fa-list"></i> Product Categories</h3>

                    <div class="form-group">
                        <label>Which product categories did you purchase from? <span class="required-field">*</span></label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="cat1" name="categories" value="cotton">
                                <label for="cat1">Cotton</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="cat2" name="categories" value="wash-wear">
                                <label for="cat2">Wash & Wear</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="cat3" name="categories" value="khaddar">
                                <label for="cat3">Khaddar</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="cat4" name="categories" value="linen">
                                <label for="cat4">Linen</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="cat5" name="categories" value="boski">
                                <label for="cat5">Boski</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="cat6" name="categories" value="dhanak">
                                <label for="cat6">Dhanak</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommendations -->
                <div class="form-section">
                    <h3><i class="fas fa-lightbulb"></i> Recommendations & Suggestions</h3>

                    <div class="form-group">
                        <label>Would you recommend CLOTHES STORE to others? <span class="required-field">*</span></label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="recommend1" name="recommend" value="definitely" required>
                                <label for="recommend1">Definitely Yes</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="recommend2" name="recommend" value="probably">
                                <label for="recommend2">Probably Yes</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="recommend3" name="recommend" value="neutral">
                                <label for="recommend3">Neutral</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="recommend4" name="recommend" value="probably-no">
                                <label for="recommend4">Probably No</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="recommend5" name="recommend" value="definitely-no">
                                <label for="recommend5">Definitely No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>What could we improve? <span class="required-field">*</span></label>
                        <textarea id="improvements" name="improvements" placeholder="Please share any suggestions for improvement..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Additional Comments</label>
                        <textarea id="comments" name="comments" placeholder="Share any additional thoughts or experiences..."></textarea>
                    </div>
                </div>

                <!-- Preferences -->
                <div class="form-section">
                    <h3><i class="fas fa-bell"></i> Communication Preferences</h3>

                    <div class="form-group">
                        <label>How would you like to receive updates from us?</label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="pref1" name="preferences" value="email">
                                <label for="pref1">Email</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="pref2" name="preferences" value="sms">
                                <label for="pref2">SMS</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="pref3" name="preferences" value="whatsapp">
                                <label for="pref3">WhatsApp</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="pref4" name="preferences" value="none">
                                <label for="pref4">No Contact</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">I agree to share my feedback with CLOTHES STORE for improvement purposes <span class="required-field">*</span></label>
                        </div>
                    </div>
                </div>

                <!-- Raffle Terms -->
                <div class="form-section">
                    <h3><i class="fas fa-star"></i> Monthly Raffle Terms</h3>

                    <div class="info-banner" style="background: #f0f4ff; border-left-color: #6366f1; color: var(--primary-dark);">
                        <p style="margin: 0; font-size: 0.95rem;">
                            <strong>Raffle Eligibility:</strong> Every valid feedback submission automatically enters you into our monthly raffle draw. One lucky winner is selected on the 1st of each month. Winners will be contacted via email and SMS.
                        </p>
                    </div>

                    <div class="form-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="raffleTerms" name="raffleTerms">
                            <label for="raffleTerms">I want to participate in the monthly raffle draw and understand that winners are selected randomly</label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Submit Feedback
                    </button>
                    <button type="reset" class="btn-reset">
                        <i class="fas fa-redo"></i> Clear Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function handleSubmit(event) {
        event.preventDefault();

        // Get form data
        const fullName = document.getElementById('fullName').value;
        const email = document.getElementById('email').value;
        const ease = document.querySelector('input[name="ease"]:checked')?.value;
        const quality = document.querySelector('input[name="quality"]:checked')?.value;
        const pricing = document.querySelector('input[name="pricing"]:checked')?.value;
        const delivery = document.querySelector('input[name="delivery"]:checked')?.value;
        const support = document.querySelector('input[name="support"]:checked')?.value;
        const recommend = document.querySelector('input[name="recommend"]:checked')?.value;
        const improvements = document.getElementById('improvements').value;
        const terms = document.getElementById('terms').checked;

        // Validate required fields
        if (!fullName || !email || !ease || !quality || !pricing || !delivery || !support || !recommend || !improvements || !terms) {
            document.getElementById('errorMessage').classList.add('show');
            setTimeout(() => {
                document.getElementById('errorMessage').classList.remove('show');
            }, 3000);
            return;
        }

        // Show success message
        document.getElementById('successMessage').classList.add('show');
        
        // Reset form
        document.getElementById('surveyForm').reset();

        // Hide success message after 5 seconds
        setTimeout(() => {
            document.getElementById('successMessage').classList.remove('show');
        }, 5000);

        // In a real application, you would send the data to your backend here
        // Example:
        // fetch('/api/feedback', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        //     },
        //     body: JSON.stringify(formData)
        // });

        console.log('Feedback submitted:', {
            fullName,
            email,
            ease,
            quality,
            pricing,
            delivery,
            support,
            recommend,
            improvements
        });
    }

    // Update progress bar on input change
    const form = document.getElementById('surveyForm');
    const inputs = form.querySelectorAll('input, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('change', updateProgress);
        input.addEventListener('input', updateProgress);
    });

    function updateProgress() {
        const totalFields = 11; // Count of required groups
        let filledFields = 0;

        // Check personal info
        if (document.getElementById('fullName').value) filledFields++;
        if (document.getElementById('email').value) filledFields++;

        // Check ratings
        if (document.querySelector('input[name="ease"]:checked')) filledFields++;
        if (document.querySelector('input[name="quality"]:checked')) filledFields++;
        if (document.querySelector('input[name="pricing"]:checked')) filledFields++;
        if (document.querySelector('input[name="delivery"]:checked')) filledFields++;
        if (document.querySelector('input[name="support"]:checked')) filledFields++;
        if (document.querySelector('input[name="recommend"]:checked')) filledFields++;
        if (document.getElementById('improvements').value) filledFields++;

        const progress = (filledFields / totalFields) * 100;
        document.querySelector('.progress-fill').style.width = progress + '%';
    }

    // Update progress on page load
    updateProgress();
</script>

@endsection
