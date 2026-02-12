(() => {
  const shareButton = document.querySelector('.share-btn');
  const modal = document.getElementById('share-modal');
  const closeButton = document.getElementById('close-modal');
  const copyButton = document.getElementById('copy-link');
  const shareField = document.getElementById('share-link');

  if (shareButton && modal) {
    const openModal = async () => {
      const url = shareButton.dataset.shareUrl || window.location.href;
      if (navigator.share) {
        try {
          await navigator.share({ title: document.title, url });
          return;
        } catch (error) {
          // Ignore and open fallback modal.
        }
      }
      modal.classList.remove('hidden');
    };

    shareButton.addEventListener('click', openModal);
    closeButton?.addEventListener('click', () => modal.classList.add('hidden'));

    modal.addEventListener('click', (event) => {
      if (event.target === modal) {
        modal.classList.add('hidden');
      }
    });

    copyButton?.addEventListener('click', async () => {
      if (!shareField) return;
      try {
        await navigator.clipboard.writeText(shareField.value);
        copyButton.textContent = 'Copied!';
      } catch (error) {
        shareField.select();
        document.execCommand('copy');
        copyButton.textContent = 'Copied!';
      }

      setTimeout(() => {
        copyButton.textContent = 'Copy Link';
      }, 1200);
    });
  }

  const addLinkButton = document.getElementById('add-link');
  const linksWrapper = document.getElementById('links-wrapper');
  const linkTemplate = document.getElementById('link-template');

  if (addLinkButton && linksWrapper && linkTemplate) {
    let count = 0;
    const maxLinks = 8;

    const appendRow = () => {
      if (count >= maxLinks) return;
      const fragment = linkTemplate.content.cloneNode(true);
      const row = fragment.querySelector('.link-row');
      const removeButton = fragment.querySelector('.remove-link');

      removeButton?.addEventListener('click', () => {
        row?.remove();
        count -= 1;
        addLinkButton.disabled = false;
      });

      linksWrapper.appendChild(fragment);
      count += 1;
      if (count >= maxLinks) {
        addLinkButton.disabled = true;
      }
    };

    addLinkButton.addEventListener('click', appendRow);
    appendRow();
  }
})();
