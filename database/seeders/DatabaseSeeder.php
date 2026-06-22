<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organization;
use App\Models\Lead;
use App\Models\Inquiry;
use App\Models\Meeting;
use App\Models\Task;
use App\Models\Document;
use App\Models\Activity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear tables to prevent duplicate key constraint violations
        Activity::truncate();
        Document::truncate();
        Task::truncate();
        Meeting::truncate();
        Inquiry::truncate();
        Lead::truncate();
        User::truncate();
        Organization::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // 1. Create Organizations
        $acme = Organization::create(['name' => 'Acme Corporation']);
        $globex = Organization::create(['name' => 'Globex Industries']);

        // 2. Create Users
        $superAdmin = User::create([
            'name' => 'SuperAdmin User',
            'email' => 'superadmin@crm.com',
            'password' => Hash::make('admin123'),
            'role' => 'superadmin',
            'organization_id' => null,
            'staff_role' => null,
        ]);

        $acmeAdmin = User::create([
            'name' => 'Acme Admin',
            'email' => 'acmeadmin@crm.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'organization_id' => $acme->id,
            'staff_role' => 'Org Administrator',
        ]);

        $globexAdmin = User::create([
            'name' => 'Globex Admin',
            'email' => 'globexadmin@crm.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'organization_id' => $globex->id,
            'staff_role' => 'Org Administrator',
        ]);

        $john = User::create([
            'name' => 'John Doe',
            'email' => 'john@crm.com',
            'password' => Hash::make('sales123'),
            'role' => 'staff',
            'organization_id' => $acme->id,
            'staff_role' => 'Sales',
        ]);

        $sarah = User::create([
            'name' => 'Sarah Smith',
            'email' => 'sarah@crm.com',
            'password' => Hash::make('sales123'),
            'role' => 'staff',
            'organization_id' => $globex->id,
            'staff_role' => 'Sales',
        ]);

        // 3. Create Leads
        $lead1 = Lead::create([
            'full_name' => 'Rajesh Kumar',
            'company_name' => 'EduTech Solutions',
            'mobile' => '9876543210',
            'email' => 'rajesh@edutech.com',
            'website' => 'edutech.com',
            'industry' => 'Education',
            'lead_source' => 'Website',
            'budget' => 150000.00,
            'requirement' => 'Need a school ERP software with student registry, fee tracking, reports, and a parent portal app.',
            'notes' => 'Very responsive, wants to implement before the new academic session starts in 2 months.',
            'status' => 'New',
            'ai_score' => 88,
            'ai_qualification' => 'Hot Lead',
            'ai_priority' => 'High',
            'ai_recommended_followup' => 'Schedule a comprehensive ERP demo. Call to discuss current software pain points.',
            'ai_summary' => 'Seeking full-scale school ERP. High buying intent with active timeline.',
            'ai_intent' => 'School ERP System',
            'ai_urgency' => 'High',
            'ai_budget_estimate' => '₹1,50,000 - ₹2,00,000',
            'ai_recommended_department' => 'Enterprise Solutions',
            'ai_sales_probability' => 75,
            'ai_recommended_service' => 'ERP Development',
            'assigned_to' => $john->id,
        ]);

        $lead2 = Lead::create([
            'full_name' => 'Priya Sharma',
            'company_name' => 'Organic Foods',
            'mobile' => '9812345678',
            'email' => 'priya@organicfoods.in',
            'website' => 'organicfoods.in',
            'industry' => 'Retail & E-commerce',
            'lead_source' => 'WhatsApp',
            'budget' => 75000.00,
            'requirement' => 'E-commerce website with product catalog, payments (Stripe/Razorpay), and inventory sync.',
            'notes' => 'Currently selling via Instagram. Ready to move to an automated Shopify or WooCommerce site.',
            'status' => 'Contacted',
            'ai_score' => 70,
            'ai_qualification' => 'Warm Lead',
            'ai_priority' => 'Medium',
            'ai_recommended_followup' => 'Send Shopify vs WooCommerce portfolio and pricing guide.',
            'ai_summary' => 'Moving retail store to e-commerce web platform. Moderate budget.',
            'ai_intent' => 'E-Commerce Website',
            'ai_urgency' => 'Medium',
            'ai_budget_estimate' => '₹60,000 - ₹90,000',
            'ai_recommended_department' => 'Web Development',
            'ai_sales_probability' => 60,
            'ai_recommended_service' => 'WooCommerce Customization',
            'assigned_to' => $sarah->id,
        ]);

        $lead3 = Lead::create([
            'full_name' => 'Amit Patel',
            'company_name' => 'Patel Logistics',
            'mobile' => '9988776655',
            'email' => 'amit@patellogistics.com',
            'website' => 'patellogistics.com',
            'industry' => 'Logistics & Shipping',
            'lead_source' => 'Email',
            'budget' => 350000.00,
            'requirement' => 'CRM + fleet tracking software. Real-time GPS sync, driver assignment, and billing module.',
            'notes' => 'Needs integration with local third-party GPS hardware.',
            'status' => 'Qualified',
            'ai_score' => 92,
            'ai_qualification' => 'Hot Lead',
            'ai_priority' => 'High',
            'ai_recommended_followup' => 'Arrange tech consulting call with the lead architect.',
            'ai_summary' => 'Large enterprise logistic provider needs custom telematics CRM.',
            'ai_intent' => 'Fleet Management CRM',
            'ai_urgency' => 'High',
            'ai_budget_estimate' => '₹3,00,000 - ₹4,50,000',
            'ai_recommended_department' => 'Enterprise Solutions',
            'ai_sales_probability' => 85,
            'ai_recommended_service' => 'Custom Telematics CRM Integration',
            'assigned_to' => $john->id,
        ]);

        $lead4 = Lead::create([
            'full_name' => 'Sneha Reddy',
            'company_name' => 'Reddy Real Estate',
            'mobile' => '9000112233',
            'email' => 'sneha@reddyre.com',
            'website' => 'reddyre.com',
            'industry' => 'Real Estate',
            'lead_source' => 'Facebook',
            'budget' => 120000.00,
            'requirement' => 'Lead capture agent dashboard, property matching engine, and automatic SMS alerts.',
            'notes' => 'Negotiating on payment structure and timeline.',
            'status' => 'Proposal Sent',
            'ai_score' => 65,
            'ai_qualification' => 'Warm Lead',
            'ai_priority' => 'Medium',
            'ai_recommended_followup' => 'Send revised quote reducing initial milestone to 35%.',
            'ai_summary' => 'Brokerage agency requires customer broker portal. Price negotiation active.',
            'ai_intent' => 'Real Estate CRM Portal',
            'ai_urgency' => 'Medium',
            'ai_budget_estimate' => '₹1,00,000 - ₹1,30,000',
            'ai_recommended_department' => 'Web Applications',
            'ai_sales_probability' => 55,
            'ai_recommended_service' => 'Real Estate Agent Portal',
            'assigned_to' => $sarah->id,
        ]);

        // 4. Create Inquiries
        Inquiry::create([
            'customer_name' => 'Rajesh Kumar',
            'contact' => 'rajesh@edutech.com / 9876543210',
            'message' => 'I need ERP software for my school. It has 800 students. We want fee details, exam marking, and app access for parents.',
            'source' => 'Website',
            'date' => now()->subHours(24),
            'ai_summary' => 'Wants 800-student School ERP with parent portal and automated fee tracking.',
            'ai_intent' => 'School ERP software',
            'ai_urgency' => 'High',
            'ai_budget_estimate' => '₹1,50,000 - ₹2,00,000',
            'ai_recommended_department' => 'Sales / ERP',
            'status' => 'Processed',
            'assigned_to' => $john->id,
            'lead_id' => $lead1->id
        ]);

        Inquiry::create([
            'customer_name' => 'Priya Sharma',
            'contact' => '+919812345678',
            'message' => 'Hi, looking to setup an online web store for organic oils. Need payment gateway integration and dashboard to track orders.',
            'source' => 'WhatsApp',
            'date' => now()->subHours(6),
            'ai_summary' => 'Wants an organic oil e-commerce store with dashboard and gateway integrations.',
            'ai_intent' => 'E-Commerce Website',
            'ai_urgency' => 'Medium',
            'ai_budget_estimate' => '₹60,000 - ₹90,000',
            'ai_recommended_department' => 'Web Dev',
            'status' => 'Processed',
            'assigned_to' => $sarah->id,
            'lead_id' => $lead2->id
        ]);

        // 5. Create Meetings
        Meeting::create([
            'title' => 'School ERP Tech Architecture Call',
            'customer_name' => 'Rajesh Kumar',
            'lead_id' => $lead1->id,
            'date' => date('Y-m-d'),
            'time' => '11:00:00',
            'meeting_link' => 'https://zoom.us/j/987654321',
            'location' => 'Zoom',
            'notes' => 'Demonstrate academic module, fee structure engine, and parent login.',
            'status' => 'Scheduled',
            'ai_customer_summary' => 'Rajesh Kumar runs EduTech Solutions. Focused on user experience for parents and security of fee payments.',
            'ai_previous_interactions' => 'Initial inquiry submitted on Website. Brief WhatsApp intro conducted.',
            'ai_suggested_topics' => '1. Core databases. 2. Cloud hosting options. 3. Custom parent dashboard apps.'
        ]);

        Meeting::create([
            'title' => 'Real Estate Broker Portal Proposal Review',
            'customer_name' => 'Sneha Reddy',
            'lead_id' => $lead4->id,
            'date' => date('Y-m-d', strtotime('+1 day')),
            'time' => '16:00:00',
            'meeting_link' => 'https://meet.google.com/abc-def-ghi',
            'location' => 'Google Meet',
            'notes' => 'Review the proposed agent portal mockups and discuss payment milestone breakdown.',
            'status' => 'Scheduled',
            'ai_customer_summary' => 'Sneha Reddy is managing partner at Reddy Real Estate. Very price-conscious and requires flexible stages.',
            'ai_previous_interactions' => 'Sent initial proposal document with ₹1.2 Lakh pricing.',
            'ai_suggested_topics' => '1. Modular rollout of Agent vs Admin screens. 2. 4-milestone payment layout. 3. Hosting server setup.'
        ]);

        // 6. Create Tasks
        Task::create([
            'title' => 'Confirm zoom attendance with Rajesh',
            'type' => 'Call Customer',
            'lead_id' => $lead1->id,
            'user_id' => $john->id,
            'due_date' => date('Y-m-d'),
            'priority' => 'High',
            'status' => 'Pending',
            'notes' => 'Call 30 mins before the scheduled 11 AM Zoom call.',
            'ai_suggested' => true
        ]);

        Task::create([
            'title' => 'Email updated quotation to Priya Sharma',
            'type' => 'Send Proposal',
            'lead_id' => $lead2->id,
            'user_id' => $sarah->id,
            'due_date' => date('Y-m-d'),
            'priority' => 'High',
            'status' => 'Pending',
            'notes' => 'Revised quotation with Razorpay & Stripe integration costs included.',
            'ai_suggested' => true
        ]);

        // 7. Create Documents
        Document::create([
            'lead_id' => $lead1->id,
            'type' => 'Proposal',
            'document_number' => 'PROP-2026-001',
            'title' => 'EduTech School ERP System Proposal',
            'amount' => 150000.00,
            'content' => [
                'executive_summary' => 'Custom School ERP proposal for EduTech Solutions to digitalize their 800-student campus, fees, and grade reports.',
                'scope' => [
                    'Student database management & Registry',
                    'Fee Payment Dashboard & SMS gateway integration',
                    'Parent Portal & Progress Cards generator',
                ],
                'features' => [
                    'Responsive panel for teachers',
                    'Stripe & Razorpay fee gateways',
                    'Automated PDF invoice generation for parents',
                ],
                'timeline' => '6 Weeks from launch date.',
                'pricing' => [
                    ['item' => 'UI Dashboard Design', 'cost' => '₹30,000'],
                    ['item' => 'Backend database & Fee systems', 'cost' => '₹90,000'],
                    ['item' => 'Parent Portal Android App', 'cost' => '₹30,000'],
                ],
                'terms' => [
                    '50% Advance payment, 30% after design lock, 20% on deployment.',
                ]
            ]
        ]);
    }
}
