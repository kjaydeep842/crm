@extends('layouts.frontend')
@section('title', 'Contact Us | DevineSky CRM')
@section('content')
<div class="page-header">
    <h1 data-aos="fade-up">Get in Touch</h1>
    <p data-aos="fade-up" data-aos-delay="100">Have questions? We're here to help you automate your sales process.</p>
</div>
<section class="section-padding" style="background:var(--bg-light);">
    <div style="max-width:1000px; margin:0 auto; display:grid; grid-template-columns:1fr 1fr; gap:60px;">
        
        <div data-aos="fade-right">
            <h2 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:32px; color:var(--dark); margin-bottom:24px;">Contact Information</h2>
            <p style="color:var(--text); margin-bottom:40px; font-size:16px;">Whether you're looking for enterprise pricing, technical support, or partnership opportunities, our team is ready to assist you.</p>
            
            <div style="display:flex; align-items:center; gap:20px; margin-bottom:24px;">
                <div style="width:50px; height:50px; background:rgba(79,70,229,0.1); color:var(--primary); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                    <h4 style="color:var(--dark); font-size:16px; margin-bottom:4px;">Email Us</h4>
                    <p style="color:var(--text); font-size:14px;">hello@devinesky.com</p>
                </div>
            </div>
            
            <div style="display:flex; align-items:center; gap:20px; margin-bottom:24px;">
                <div style="width:50px; height:50px; background:rgba(6,182,212,0.1); color:var(--secondary); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div>
                    <h4 style="color:var(--dark); font-size:16px; margin-bottom:4px;">Call Us</h4>
                    <p style="color:var(--text); font-size:14px;">+1 (800) 555-DEVINESKY</p>
                </div>
            </div>
            
            <div style="display:flex; align-items:center; gap:20px;">
                <div style="width:50px; height:50px; background:rgba(236,72,153,0.1); color:#ec4899; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <h4 style="color:var(--dark); font-size:16px; margin-bottom:4px;">Headquarters</h4>
                    <p style="color:var(--text); font-size:14px;">123 Innovation Drive, Tech City, SF 94105</p>
                </div>
            </div>
        </div>

        <div style="background:var(--surface); border:1px solid var(--border); border-radius:24px; padding:40px; box-shadow:0 10px 30px rgba(0,0,0,0.03);" data-aos="fade-left">
            <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:24px; color:var(--dark); margin-bottom:24px;">Send a Message</h3>
            <form action="{{ route('public.inquire') }}" method="POST">
                @csrf
                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:8px; text-transform:uppercase;">Name</label>
                    <input type="text" name="name" required style="width:100%; border-radius:12px; border:1px solid #cbd5e1; padding:12px 16px; font-size:15px; background:var(--bg-light); outline:none;">
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:8px; text-transform:uppercase;">Email</label>
                    <input type="email" name="email" required style="width:100%; border-radius:12px; border:1px solid #cbd5e1; padding:12px 16px; font-size:15px; background:var(--bg-light); outline:none;">
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:13px; font-weight:700; color:var(--text); margin-bottom:8px; text-transform:uppercase;">Message</label>
                    <textarea name="requirement" rows="4" required style="width:100%; border-radius:12px; border:1px solid #cbd5e1; padding:12px 16px; font-size:15px; background:var(--bg-light); outline:none; resize:none;"></textarea>
                </div>
                <button type="submit" style="width:100%; background:linear-gradient(135deg, var(--primary), #6366f1); color:#ffffff; border:none; padding:14px; border-radius:12px; font-weight:700; font-size:16px; cursor:pointer; box-shadow:0 8px 20px rgba(79, 70, 229, 0.25);">Send Message</button>
            </form>
        </div>

    </div>
</section>

@section('styles')
<style>
@media (max-width: 768px) {
    .section-padding > div {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
@endsection
