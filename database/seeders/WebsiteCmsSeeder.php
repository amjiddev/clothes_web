<?php

namespace Database\Seeders;

use App\Models\WebsiteCms;
use Illuminate\Database\Seeder;

class WebsiteCmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hero Section
        WebsiteCms::create([
            'section_type' => 'hero',
            'page_title' => 'Welcome to Our Store',
            'page_content' => 'Discover premium quality tailored clothing and customization services. We bring your fashion dreams to life with expert craftsmanship and dedication.',
            'data' => [
                'subtitle' => 'Premium Tailored Fashion',
                'button_text' => 'Shop Now',
                'button_url' => '/shop',
            ],
            'is_published' => true,
            'published_at' => now(),
            'created_by' => 1,
            'display_order' => 1,
        ]);

        // About Section
        WebsiteCms::create([
            'section_type' => 'about',
            'page_title' => 'About Our Company',
            'page_content' => 'With over 10 years of experience in fashion and tailoring, we have built a reputation for excellence. Our team of skilled tailors and designers work tirelessly to ensure every piece meets our high standards of quality and craftsmanship.',
            'is_published' => true,
            'published_at' => now(),
            'created_by' => 1,
            'display_order' => 1,
        ]);

        // Slider Items
        for ($i = 1; $i <= 3; $i++) {
            WebsiteCms::create([
                'section_type' => 'slider',
                'page_title' => "Collection " . $i,
                'page_content' => "Discover our exclusive collection " . $i . ". Premium quality, stylish designs.",
                'is_published' => true,
                'published_at' => now(),
                'created_by' => 1,
                'display_order' => $i,
            ]);
        }

        // Services
        $services = [
            [
                'title' => 'Custom Tailoring',
                'description' => 'Professional tailoring services for perfect fit and style customization.',
                'icon' => 'fas fa-scissors',
            ],
            [
                'title' => 'Quick Alterations',
                'description' => 'Fast turnaround on all alterations and modifications.',
                'icon' => 'fas fa-hammer',
            ],
            [
                'title' => 'Premium Fabrics',
                'description' => 'Access to exclusive premium fabrics from around the world.',
                'icon' => 'fas fa-certificate',
            ],
            [
                'title' => 'Expert Design',
                'description' => 'Professional designers to help you create your perfect look.',
                'icon' => 'fas fa-pencil-ruler',
            ],
        ];

        foreach ($services as $index => $service) {
            WebsiteCms::create([
                'section_type' => 'services',
                'page_title' => $service['title'],
                'page_content' => $service['description'],
                'data' => ['icon' => $service['icon']],
                'is_published' => true,
                'published_at' => now(),
                'created_by' => 1,
                'display_order' => $index + 1,
            ]);
        }

        // Testimonials
        $testimonials = [
            [
                'author' => 'Sarah Johnson',
                'position' => 'Fashion Blogger',
                'rating' => 5,
                'content' => 'The quality of tailoring is outstanding! Every stitch is perfect. Highly recommended!',
            ],
            [
                'author' => 'Michael Chen',
                'position' => 'Business Professional',
                'rating' => 5,
                'content' => 'Best tailoring service I\'ve ever used. Quick turnaround and excellent customer service.',
            ],
            [
                'author' => 'Emma Williams',
                'position' => 'Event Planner',
                'rating' => 4,
                'content' => 'Great selection and professional team. Will definitely order again!',
            ],
        ];

        foreach ($testimonials as $index => $testimonial) {
            WebsiteCms::create([
                'section_type' => 'testimonial',
                'page_title' => $testimonial['author'],
                'page_content' => $testimonial['content'],
                'data' => [
                    'author' => $testimonial['author'],
                    'position' => $testimonial['position'],
                    'rating' => $testimonial['rating'],
                ],
                'is_published' => true,
                'published_at' => now(),
                'created_by' => 1,
                'display_order' => $index + 1,
            ]);
        }

        // Contact Information
        WebsiteCms::create([
            'section_type' => 'contact',
            'page_title' => 'Get In Touch',
            'page_content' => 'Have questions? We\'d love to hear from you. Contact us using any of the methods below and we\'ll respond as quickly as possible.',
            'data' => [
                'email' => 'info@clothesstore.com',
                'phone' => '+1 (555) 123-4567',
                'address' => '123 Fashion Street, Style City, SC 12345, USA',
            ],
            'is_published' => true,
            'published_at' => now(),
            'created_by' => 1,
            'display_order' => 1,
        ]);

        // Social Links
        $socialLinks = [
            ['platform' => 'Facebook', 'icon' => 'fab fa-facebook', 'url' => 'https://facebook.com'],
            ['platform' => 'Instagram', 'icon' => 'fab fa-instagram', 'url' => 'https://instagram.com'],
            ['platform' => 'Twitter', 'icon' => 'fab fa-twitter', 'url' => 'https://twitter.com'],
            ['platform' => 'LinkedIn', 'icon' => 'fab fa-linkedin', 'url' => 'https://linkedin.com'],
        ];

        foreach ($socialLinks as $index => $social) {
            WebsiteCms::create([
                'section_type' => 'social',
                'page_title' => $social['platform'],
                'data' => [
                    'icon' => $social['icon'],
                    'url' => $social['url'],
                ],
                'is_published' => true,
                'published_at' => now(),
                'created_by' => 1,
                'display_order' => $index + 1,
            ]);
        }

        // Footer Content
        WebsiteCms::create([
            'section_type' => 'footer',
            'page_title' => 'Footer Content',
            'page_content' => 'Follow us on social media for latest updates, style tips, and exclusive offers. Subscribe to our newsletter for special discounts!',
            'data' => [
                'copyright' => '© 2024 Clothes Store. All rights reserved.',
            ],
            'is_published' => true,
            'published_at' => now(),
            'created_by' => 1,
            'display_order' => 1,
        ]);
    }
}
