// Script to generate a notification sound using Web Audio API and save it
// This can be run in browser console to create a sound file

function generateNotificationSound() {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const sampleRate = audioContext.sampleRate;
    const duration = 0.5; // 0.5 seconds
    const length = sampleRate * duration;
    
    const buffer = audioContext.createBuffer(1, length, sampleRate);
    const data = buffer.getChannelData(0);
    
    // Generate a pleasant two-tone notification sound
    for (let i = 0; i < length; i++) {
        const t = i / sampleRate;
        const freq1 = 800; // First tone
        const freq2 = 1000; // Second tone
        
        // Fade between the two frequencies
        const freqMix = t < 0.25 ? freq1 : freq2;
        
        // Generate sine wave with envelope
        const envelope = Math.exp(-t * 3); // Exponential decay
        data[i] = Math.sin(2 * Math.PI * freqMix * t) * envelope * 0.3;
    }
    
    // Play the generated sound
    const source = audioContext.createBufferSource();
    source.buffer = buffer;
    source.connect(audioContext.destination);
    source.start();
    
    console.log('Generated notification sound played');
    return buffer;
}

// Call this function in browser console to test
window.generateNotificationSound = generateNotificationSound;