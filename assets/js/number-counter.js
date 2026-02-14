/**
 * Number Counter Block
 * Animates numbers from 0 to target value when they come into view
 */

export function initNumberCounters() {
    const counterBlocks = document.querySelectorAll('.number-counter-block');
    
    // Only run if counter blocks exist on the page
    if (counterBlocks.length === 0) return;
    
    counterBlocks.forEach(block => {
        const counterElement = block.querySelector('.set-number');
        
        if (!counterElement) return;
        
        const targetNumber = parseInt(counterElement.getAttribute('data-target')) || 0;
        const incrementNumber = parseInt(counterElement.getAttribute('data-increment')) || 1;
        
        let hasAnimated = false;
        
        function animateCounter() {
            if (hasAnimated) return;
            hasAnimated = true;
            
            let currentNumber = 0;
            const duration = 2000; // 2 seconds
            const totalSteps = Math.ceil(targetNumber / incrementNumber);
            const stepDuration = duration / totalSteps;
            
            const counter = setInterval(() => {
                currentNumber += incrementNumber;
                
                if (currentNumber >= targetNumber) {
                    currentNumber = targetNumber;
                    clearInterval(counter);
                }
                
                counterElement.textContent = currentNumber;
            }, stepDuration);
        }
        
        // Use Intersection Observer to trigger animation when element is in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(counterElement);
    });
}
