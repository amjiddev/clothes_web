@php
    use App\Models\WebsiteCms;
    
    // Get phone number from Contact Page
    $contactInfo = WebsiteCms::where('section_type', 'contact')
                              ->where('is_published', true)
                              ->first();
    
    $phoneNumber = $contactInfo?->data['phone'] ?? null;
    
    // Fallback to Settings if no contact page phone is set
    if (!$phoneNumber) {
        $phoneNumber = \App\Models\Setting::getWhatsAppNumber();
    }
    
    // Format the number: remove all non-digit characters
    $formattedNumber = preg_replace('/\D/', '', $phoneNumber);
    
    // If no country code, prepend 92 (Pakistan country code)
    if (strlen($formattedNumber) < 11) {
        $formattedNumber = '92' . $formattedNumber;
    }
    
    // WhatsApp API URL
    $whatsappUrl = 'https://wa.me/' . $formattedNumber . '?text=Hello';
@endphp

<div class="whatsapp-sticky-icon">
    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" title="Chat with us on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
</div>

<style>
    .whatsapp-sticky-icon {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .whatsapp-sticky-icon a {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
        transition: all 0.3s ease;
        animation: pulse-whatsapp 2s ease-in-out infinite;
    }

    .whatsapp-sticky-icon a:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 20px rgba(37, 211, 102, 0.6);
        animation: none;
    }

    .whatsapp-sticky-icon a:active {
        transform: scale(0.95);
    }

    @keyframes pulse-whatsapp {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(37, 211, 102, 0.5);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .whatsapp-sticky-icon {
            bottom: 20px;
            right: 20px;
        }

        .whatsapp-sticky-icon a {
            width: 50px;
            height: 50px;
            font-size: 24px;
        }
    }

    /* Hide on print */
    @media print {
        .whatsapp-sticky-icon {
            display: none !important;
        }
    }
</style>
