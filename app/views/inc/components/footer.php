<!-- Compact Dashboard Footer -->
<footer class="dashboard-footer-compact">
    <div class="footer-container">
        <div class="footer-content">
            <!-- Brand & Quick Links -->
            <div class="footer-left">
                <div class="brand-compact">
                    <img src="<?php echo URLROOT; ?>/public/img/logo.png" alt="ConstructStock Logo" class="brand-logo-compact">
                    <span class="brand-name">ConstructStock</span>
                </div>
                <div class="quick-links-compact">
                    <a href="<?php echo URLROOT; ?>/index">Dashboard</a>
                    <a href="<?php echo URLROOT; ?>/inventory/inventory">Inventory</a>
                    <a href="<?php echo URLROOT; ?>/inventory/equipment">Equipment</a>
                </div>
            </div>

            <!-- Contact & Social -->
            <div class="footer-center">
                <div class="contact-compact">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:info@constructstock.com">info@constructstock.com</a>
                </div>
                <div class="social-compact">
                    <a href="#" class="social-icon" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Legal Info -->
            <div class="footer-right">
                <p class="copyright-compact">&copy; <?php echo date('Y'); ?> ConstructStock</p>
                <div class="legal-links-compact">
                    <a href="#">Privacy</a>
                    <span>•</span>
                    <a href="#">Terms</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Compact Footer Design */
.dashboard-footer-compact {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: white;
    font-family: 'Poppins', 'Segoe UI', sans-serif;
    margin: 0; /* Connects directly to sidebar */
    padding: 40px 0; /* Increased by ~1cm (20px more top and bottom) */
    box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
}

.footer-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 40px;
}

.footer-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
}

/* Left Section - Brand & Links */
.footer-left {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.brand-compact {
    display: flex;
    align-items: center;
    gap: 12px;
}

.brand-logo-compact {
    width: 32px;
    height: 32px;
    filter: brightness(0) invert(1);
    opacity: 0.9;
}

.brand-name {
    font-size: 18px;
    font-weight: 700;
    color: white;
}

.quick-links-compact {
    display: flex;
    gap: 20px;
    margin-top: 6px;
}

.quick-links-compact a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: 13px;
    transition: color 0.3s ease;
}

.quick-links-compact a:hover {
    color: #3b82f6;
}

/* Center Section - Contact & Social */
.footer-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}

.contact-compact {
    display: flex;
    align-items: center;
    gap: 8px;
    color: rgba(255, 255, 255, 0.8);
    font-size: 13px;
}

.contact-compact i {
    color: #3b82f6;
    width: 14px;
}

.contact-compact a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.3s ease;
}

.contact-compact a:hover {
    color: #3b82f6;
}

.social-compact {
    display: flex;
    gap: 12px;
    margin-top: 6px;
}

.social-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
}

.social-icon:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    transform: translateY(-1px);
}

.social-icon:hover.facebook { background: #1877f2; color: white; }
.social-icon:hover.twitter { background: #1da1f2; color: white; }
.social-icon:hover.linkedin { background: #0077b5; color: white; }

/* Right Section - Legal */
.footer-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
}

.copyright-compact {
    margin: 0;
    color: rgba(255, 255, 255, 0.6);
    font-size: 12px;
    font-weight: 500;
}

.legal-links-compact {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 11px;
}

.legal-links-compact a {
    color: rgba(255, 255, 255, 0.5);
    text-decoration: none;
    transition: color 0.3s ease;
}

.legal-links-compact a:hover {
    color: #3b82f6;
}

.legal-links-compact span {
    color: rgba(255, 255, 255, 0.3);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .footer-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }

    .footer-center {
        align-items: flex-start;
    }

    .footer-right {
        align-items: flex-start;
    }
}

@media (max-width: 768px) {
    .footer-container {
        padding: 0 20px;
    }

    .footer-content {
        gap: 16px;
        text-align: center;
    }

    .quick-links-compact {
        flex-wrap: wrap;
        justify-content: center;
    }

    .footer-center,
    .footer-right {
        align-items: center;
    }
}

@media (max-width: 480px) {
    .dashboard-footer-compact {
        padding: 16px 0;
    }

    .brand-name {
        font-size: 16px;
    }

    .quick-links-compact {
        gap: 15px;
        font-size: 12px;
    }

    .social-compact {
        gap: 10px;
    }

    .social-icon {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }
}
</style>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/dashboard-footer.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>