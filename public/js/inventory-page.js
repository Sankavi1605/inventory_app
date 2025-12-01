(function () {
  const searchInput = document.getElementById('inventorySearch');
  const categoryFilter = document.getElementById('categoryFilter');
  const lowStockToggle = document.getElementById('lowStockToggle');
  const catalog = document.getElementById('inventoryCatalog');
  const emptyState = document.getElementById('inventoryEmptyState');
  let showLowStockOnly = false;

  function filterInventory() {
    if (!catalog) {
      return;
    }

    const cards = catalog.querySelectorAll('.inventory-card');
    const query = (searchInput?.value || '').trim().toLowerCase();
    const category = categoryFilter?.value || 'all';
    let visibleCount = 0;

    cards.forEach((card) => {
      const matchesSearch = !query || (card.dataset.search || '').includes(query);
      const matchesCategory = category === 'all' || (card.dataset.category || '') === category;
      const matchesLowStock = !showLowStockOnly || card.dataset.lowStock === '1';
      const isVisible = matchesSearch && matchesCategory && matchesLowStock;

      card.style.display = isVisible ? 'grid' : 'none';
      if (isVisible) {
        visibleCount++;
      }
    });

    if (emptyState) {
      emptyState.style.display = visibleCount === 0 ? 'grid' : 'none';
    }
  }

  searchInput?.addEventListener('input', filterInventory);
  categoryFilter?.addEventListener('change', filterInventory);

  lowStockToggle?.addEventListener('click', () => {
    showLowStockOnly = !showLowStockOnly;
    lowStockToggle.classList.toggle('is-active', showLowStockOnly);
    filterInventory();
  });

  filterInventory();
})();
