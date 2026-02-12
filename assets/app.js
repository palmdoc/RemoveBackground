(() => {
  const shareButton = document.querySelector('.share-btn');
  const modal = document.getElementById('share-modal');
  const closeButton = document.getElementById('close-modal');
  const copyButton = document.getElementById('copy-link');
  const shareField = document.getElementById('share-link');

  if (!shareButton || !modal) return;

  const openModal = async () => {
    const url = shareButton.dataset.shareUrl || window.location.href;
    if (navigator.share) {
      try {
        await navigator.share({ title: document.title, url });
        return;
      } catch (error) {
        // fallback to modal when share is cancelled/unsupported by browser state
      }
    }

    modal.classList.remove('hidden');
  };

  shareButton.addEventListener('click', openModal);

  closeButton?.addEventListener('click', () => {
    modal.classList.add('hidden');
  });

  modal.addEventListener('click', (event) => {
    if (event.target === modal) {
      modal.classList.add('hidden');
    }
  });

  copyButton?.addEventListener('click', async () => {
    if (!shareField) return;
    await navigator.clipboard.writeText(shareField.value);
    copyButton.textContent = 'Copied!';
    setTimeout(() => {
      copyButton.textContent = 'Copy Link';
    }, 1200);
  });
})();
