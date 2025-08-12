async function fetchCategoryLimit(category, dateValue, amountValue) {
  if (!category) {
    document.getElementById('category-limit-info').textContent = '';
    return;
  }

  let url = '/expense-categories/limit?category=' + encodeURIComponent(category);

  if (dateValue) {
    url += '&date=' + encodeURIComponent(dateValue);
  }

  if (amountValue !== undefined) {
    url += '&amount=' + encodeURIComponent(amountValue);
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

function updateLimitInfo() {
  const category = document.getElementById('category').value;
  const dateValue = document.getElementById('date').value;
  const amountValue = document.getElementById('amount').value;

  fetchCategoryLimit(category, dateValue, amountValue);
}

document.getElementById('category').addEventListener('change', updateLimitInfo);
document.getElementById('date').addEventListener('change', updateLimitInfo);
document.getElementById('amount').addEventListener('input', updateLimitInfo);
