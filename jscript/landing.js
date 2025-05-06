function showSection(sectionId) {
  // Hide all info sections
  document.querySelectorAll('.info-section').forEach(section => {
    section.classList.add('hidden');
  });

  // Show the selected one
  const selected = document.getElementById(sectionId);
  if (selected) {
    selected.classList.remove('hidden');
    selected.scrollIntoView({ behavior: 'smooth' });
  }
}
