   const roleSelect = document.getElementById('roleSelect');
    const referralField = document.getElementById('referralField');

    roleSelect.addEventListener('change', function () {
      if (this.value === 'volunteer') {
        referralField.classList.remove('hidden');
      } else {
        referralField.classList.add('hidden');
      }
    });