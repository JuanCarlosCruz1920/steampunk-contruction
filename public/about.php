<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../src/helpers/functions.php';
?>

<?php require_once __DIR__ . '/../src/views/partials/header.php'; ?>

<div class="brass-panel about-container">
    <h1 class="gears-title">About Steampunk Construction</h1>
    <div class="copper-divider"></div>

    <div class="about-section">
        <div class="gear-decoration left-gear"></div>
        
        <div class="about-content">
            <h2>Our Story</h2>
            <p>
                Founded in 1887 during the height of the Industrial Revolution, Steampunk Construction combines Victorian-era craftsmanship with modern engineering. 
                Our workshop in London's Clockwork District has been producing premium construction materials for over a century.
            </p>
            
        </div>
        
        <div class="gear-decoration right-gear"></div>
    </div>

    <div class="about-section">
        <h2>Our Mission</h2>
        <p>
            To provide architects and builders with the finest steampunk-inspired construction materials, blending aesthetic beauty with uncompromising durability.
        </p>
        
        <div class="mission-statement">
            <div class="mission-item">
                <div class="mission-icon">⚙️</div>
                <h3>Quality Craftsmanship</h3>
                <p>Every gear, bolt and pipe meets our exacting standards</p>
            </div>
            
            <div class="mission-item">
                <div class="mission-icon">🔧</div>
                <h3>Authentic Designs</h3>
                <p>True to 19th century engineering principles</p>
            </div>
            
            <div class="mission-item">
                <div class="mission-icon">⏱️</div>
                <h3>Timeless Durability</h3>
                <p>Built to last through the ages</p>
            </div>
        </div>
    </div>

    <div class="about-section team-section">
        <h2>Meet Our Engineers</h2>
        
        <div class="team-grid">
            <div class="team-member">
                <img src="/images/team/cruz.jpg" alt="Juan Carlos Cruz" class="sepia-filter">
                <h3>Juan Carlos Cruz</h3>
                <p>Web Designer</p>
            </div>
            
            <div class="team-member">
                <img src="/images/team/jebson1.jpg" alt="Jebson Thomas Fajilan" class="sepia-filter">
                <h3>Jebson Thomas Fajilan</h3>
                <p>Back-End Developer</p>
            </div>
            
            <div class="team-member">
                <img src="/images/team/jefferson.jpg" alt="Jefferson Emmanuel Tan" class="sepia-filter">
                <h3>Jefferson Emmanuel Tan</h3>
                <p>Front-End Developer</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../src/views/partials/footer.php'; ?>