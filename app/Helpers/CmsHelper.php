<?php

namespace App\Helpers;

use App\Models\WebsiteCms;
use Illuminate\Support\Collection;

class CmsHelper
{
    /**
     * Get hero section
     */
    public static function getHero()
    {
        return WebsiteCms::getHeroSection();
    }

    /**
     * Get homepage slider items
     */
    public static function getSlider()
    {
        return WebsiteCms::getHomepageSlider();
    }

    /**
     * Get about section
     */
    public static function getAbout()
    {
        return WebsiteCms::getAboutSection();
    }

    /**
     * Get services
     */
    public static function getServices()
    {
        return WebsiteCms::getServicesSection();
    }

    /**
     * Get testimonials
     */
    public static function getTestimonials()
    {
        return WebsiteCms::getTestimonials();
    }

    /**
     * Get contact information
     */
    public static function getContact()
    {
        return WebsiteCms::getContactInfo();
    }

    /**
     * Get social links
     */
    public static function getSocial()
    {
        return WebsiteCms::getSocialLinks();
    }

    /**
     * Get footer content
     */
    public static function getFooter()
    {
        return WebsiteCms::getFooterContent();
    }

    /**
     * Get section by type
     */
    public static function getSection($type)
    {
        return match($type) {
            'hero' => self::getHero(),
            'slider' => self::getSlider(),
            'about' => self::getAbout(),
            'services' => self::getServices(),
            'testimonial' => self::getTestimonials(),
            'contact' => self::getContact(),
            'social' => self::getSocial(),
            'footer' => self::getFooter(),
            default => null,
        };
    }

    /**
     * Check if CMS section exists and is published
     */
    public static function hasSection($type)
    {
        return WebsiteCms::where('section_type', $type)
                         ->where('is_published', true)
                         ->exists();
    }
}
