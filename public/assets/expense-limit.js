async function fetchCategoryLimit(category) {
  if (!category) {
    document.getElementById('category-limit-info').textContent = '';
    return;
  }

  // pobierz datę z inputa
  const dateInput = document.getElementById('date');
  const dateValue = dateInput ? dateInput.value : null;

  let url = '/expense-categories/limit?category=' + encodeURIComponent(category);
  if (dateValue) {
    url += '&date=' + encodeURIComponent(dateValue);
  }

  try {
    const response = await fetch(url);
    if (!response.ok) throw new Error('Błąd sieci');

    const data = await response.json();

    const infoDiv = document.getElementById('category-limit-info');

    if (!data.limitSet) {
      infoDiv.textContent = 'Dla tej kategorii nie ustawiono limitu.';
    } else {
      const remaining = data.remaining.toFixed(2);
      const spent = data.spent.toFixed(2);
      const limit = data.limit.toFixed(2);
      infoDiv.textContent = `Limit: ${limit} zł, Wydano: ${spent} zł, Pozostało: ${remaining} zł`;
    }
  } catch (error) {
    console.error(error);
  }
}


document.getElementById('category').addEventListener('change', (e) => {
  fetchCategoryLimit(e.target.value);
});

document.getElementById('date').addEventListener('change', (e) => {
  const category = document.getElementById('category').value;
  fetchCategoryLimit(category);
});