@extends('themes.xylo.partials.app')

@section('title', 'MyStore - Online Shopping')
   <title>Contact Us | Thaiyur Shop</title>
   <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        header {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            text-decoration: none;
        }
        
        .nav-links {
            display: flex;
            list-style: none;
        }
        
        .nav-links li {
            margin-left: 30px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #555;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: #3498db;
        }
        
        .hero {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 42px;
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .contact-section {
            padding: 80px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 36px;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .section-title p {
            color: #7f8c8d;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .contact-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 30px;
        }
        
        .contact-info {
            flex: 1;
            min-width: 300px;
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .contact-item {
            display: flex;
            margin-bottom: 30px;
        }
        
        .contact-icon {
            width: 60px;
            height: 60px;
            background: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            flex-shrink: 0;
        }
        
        .contact-icon i {
            color: white;
            font-size: 24px;
        }
        
        .contact-details h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .contact-details p {
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        
        .map-container {
            flex: 1;
            min-width: 300px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .map-placeholder {
            width: 100%;
            height: 100%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            font-size: 18px;
        }
        
        .business-hours {
            margin-top: 40px;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .business-hours h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #2c3e50;
            text-align: center;
        }
        
        .hours-table {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .hours-table tr {
            border-bottom: 1px solid #eee;
        }
        
        .hours-table td {
            padding: 12px 0;
        }
        
        .hours-table td:first-child {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .hours-table td:last-child {
            text-align: right;
            color: #7f8c8d;
        }
        
        footer {
            background: #2c3e50;
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        
        .footer-column {
            flex: 1;
            min-width: 200px;
            margin-bottom: 30px;
        }
        
        .footer-column h3 {
            font-size: 18px;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-column h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 2px;
            background: #3498db;
        }
        
        .footer-column p, .footer-column a {
            color: #bdc3c7;
            margin-bottom: 10px;
            display: block;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-column a:hover {
            color: #3498db;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }
        
        .social-links a:hover {
            background: #3498db;
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #bdc3c7;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 15px 0;
            }
            
            .nav-links {
                margin-top: 15px;
            }
            
            .nav-links li {
                margin: 0 10px;
            }
            
            .hero h1 {
                font-size: 32px;
            }
            
            .contact-container {
                flex-direction: column;
            }
        }
    </style>
@section('content')
@php $currency = activeCurrency(); @endphp

    <section class="hero">
        <div class="container">
            <h1>Get In Touch With Us</h1>
            <p>We'd love to hear from you. Here's how you can reach us and visit our office.</p>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <div class="section-title">
                <h2>Contact Information</h2>
                <p>Feel free to reach out to us through any of the following methods</p>
            </div>
            
            <div class="contact-container">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Our Shop</h3>
                            <p> Shanthiniketan Altair</p>
                            <p>Kelambakam ,Thaiyur , 603103</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Phone Number</h3>
                            <p>+916381673242</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Email Address</h3>
                            <p>General: thirunasky7@gmail.com</p>
                        </div>
                    </div>
                </div>
                
                <div class="map-container">
                      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3890.9967661044457!2d80.20373857424683!3d12.778721587519135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a5250c1666a8633%3A0xdb112172049adfa1!2sShantiniketan%20Vega!5e0!3m2!1sen!2sin!4v1762854416245!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            
        </div>
    </section>

   @endsection