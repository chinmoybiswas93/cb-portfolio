/**
 * Global Mouse Spotlight Effect
 * Works across all portfolio pages
 */
(function() {
  'use strict';

  // Only run on desktop
  const isDesktop = () => window.innerWidth >= 1024;

  // Create spotlight element
  function createSpotlight() {
    const spotlight = document.createElement('div');
    spotlight.id = 'cb-portfolio-spotlight';
    spotlight.className = 'cb-portfolio-spotlight';
    document.body.appendChild(spotlight);
    return spotlight;
  }

  // Initialize spotlight
  function initSpotlight() {
    let spotlight = document.getElementById('cb-portfolio-spotlight');
    
    if (!spotlight) {
      spotlight = createSpotlight();
    }

    let mouseX = 0;
    let mouseY = 0;
    let hasMouseEntered = false;

    // Update spotlight position
    function updateSpotlight() {
      if (!isDesktop()) {
        spotlight.style.display = 'none';
        return;
      }

      spotlight.style.display = 'block';
      spotlight.style.background = `radial-gradient(400px at ${mouseX}px ${mouseY}px, rgba(29, 78, 216, 0.15), transparent 80%)`;
    }

    // Handle mouse movement
    function handleMouseMove(e) {
      hasMouseEntered = true;
      mouseX = e.clientX;
      mouseY = e.clientY;
      updateSpotlight();
    }

    // Initialize position at top-left
    function resetToTopLeft() {
      mouseX = 0;
      mouseY = 0;
      hasMouseEntered = false;
      updateSpotlight();
    }

    // Set initial position
    resetToTopLeft();

    // Only enable on desktop
    if (isDesktop()) {
      document.addEventListener('mousemove', handleMouseMove);
    }

    // Handle window resize
    function handleResize() {
      if (isDesktop()) {
        if (!hasMouseEntered) {
          resetToTopLeft();
        }
        document.addEventListener('mousemove', handleMouseMove);
      } else {
        document.removeEventListener('mousemove', handleMouseMove);
        spotlight.style.display = 'none';
      }
    }

    window.addEventListener('resize', handleResize);

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
      document.removeEventListener('mousemove', handleMouseMove);
      window.removeEventListener('resize', handleResize);
    });
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSpotlight);
  } else {
    initSpotlight();
  }
})();

