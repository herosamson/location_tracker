
const shareButton = document.getElementById('shareButton');
const status = document.getElementById('status');

shareButton.addEventListener('click', shareLocation);

async function shareLocation() {
    if (!navigator.geolocation) {
        status.textContent =
            'Your browser does not support location services.';
        return;
    }

    shareButton.disabled = true;
    status.textContent = 'Requesting location permission...';

    navigator.geolocation.getCurrentPosition(
        async (position) => {
            const locationData = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy
            };

            status.textContent = 'Saving location...';

            try {
                const response = await fetch('save_location.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(locationData)
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(
                        result.error || 'Could not save location.'
                    );
                }

                status.textContent =
                    'Location saved successfully!\n\n' +
                    'Latitude: ' + locationData.latitude + '\n' +
                    'Longitude: ' + locationData.longitude + '\n' +
                    'Reported accuracy: ±' +
                    Math.round(locationData.accuracy) + ' meters';

            } catch (error) {
                status.textContent = error.message;
            } finally {
                shareButton.disabled = false;
            }
        },
        (error) => {
            const errors = {
                1: 'Location permission denied. Please allow access to continue.',
                2: 'Location unavailable. Check your device location settings.',
                3: 'Request timed out. Please try again.'
            };

            status.textContent =
                errors[error.code] || 'Unable to obtain location.';

            shareButton.disabled = false;
        },
        {
            enableHighAccuracy: true,
            timeout: 60000,
            maximumAge: 0
        }
    );
}
