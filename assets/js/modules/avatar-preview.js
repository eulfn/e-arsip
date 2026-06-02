/**
 * Handles live preview of avatar image selection
 * @param {string} inputId - ID of the file input
 * @param {string} previewId - ID of the img element for preview
 * @param {string} containerId - ID of the container for the preview
 */
function initAvatarPreview(inputId, previewId, containerId) {
    const input = document.getElementById(inputId);
    if (!input) return;

    input.onchange = function (evt) {
        const tgt = evt.target || window.event.srcElement;
        const files = tgt.files;

        if (FileReader && files && files.length) {
            const fr = new FileReader();
            fr.onload = function () {
                let preview = document.getElementById(previewId);
                const container = document.getElementById(containerId);

                if (!preview && container) {
                    // Create img element if it doesn't exist (e.g., initials were shown)
                    preview = document.createElement('img');
                    preview.id = previewId;
                    preview.className = 'avatar-image-preview'; // Use class instead of inline style
                    // Handle different class names for different views if necessary
                    if (container.classList.contains('user-avatar-preview')) {
                        preview.className = 'user-avatar-image';
                    }
                    
                    container.innerHTML = '';
                    container.appendChild(preview);
                }

                if (preview) {
                    preview.src = fr.result;
                }
            }
            fr.readAsDataURL(files[0]);
        }
    }
}
