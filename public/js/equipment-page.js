(function () {
  const headerSearch = document.getElementById('equipmentHeaderSearch');
  const statusFilter = document.getElementById('statusFilter');
  const listContainer = document.getElementById('equipmentList');
  const emptyState = document.getElementById('equipmentEmptyState');

  function filterCards() {
    if (!listContainer) {
      return;
    }

    const cards = listContainer.querySelectorAll('.equipment-card');
    const query = (headerSearch?.value || '').trim().toLowerCase();
    const status = statusFilter?.value || 'all';
    let visibleCount = 0;

    cards.forEach((card) => {
      const matchesStatus = status === 'all' || card.dataset.status === status;
      const matchesSearch = !query || (card.dataset.search || '').includes(query);
      const isVisible = matchesStatus && matchesSearch;
      card.style.display = isVisible ? 'grid' : 'none';
      if (isVisible) {
        visibleCount++;
      }
    });

    if (emptyState) {
      emptyState.style.display = visibleCount === 0 ? 'grid' : 'none';
    }
  }

  headerSearch?.addEventListener('input', filterCards);
  statusFilter?.addEventListener('change', filterCards);

  filterCards();
})();
