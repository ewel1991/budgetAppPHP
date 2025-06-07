<?php include $this->resolve("partials/_header.php"); ?>

<body>

  <?php include $this->resolve("partials/_csrf.php"); ?>

  <?php include $this->resolve("partials/_navbar.php"); ?>

  <div class="centered-container rounded-3">
    <h2 class="text-white mb-4 text-center">Bilans przychodów i wydatków</h2>

    <form method="post" id="dateFilterForm" class="mb-4">
      <label for="dateRange" class="form-label">Zakres dat:</label>
      <select id="dateRange" name="dateRange" class="form-select mb-3">
        <option value="current" <?= $dateRange === 'current' ? 'selected' : '' ?>>Bieżący miesiąc</option>
        <option value="previous" <?= $dateRange === 'previous' ? 'selected' : '' ?>>Poprzedni miesiąc</option>
        <option value="year" <?= $dateRange === 'year' ? 'selected' : '' ?>>Bieżący rok</option>
        <option value="custom" <?= $dateRange === 'custom' ? 'selected' : '' ?>>Niestandardowy</option>
      </select>

      <div id="customDateRange" style="<?= $dateRange === 'custom' ? 'display: block;' : 'display: none;' ?>">
        <label for="startDate" class="form-label">Od:</label>
        <input type="date" id="startDate" name="start_date" class="form-control mb-3" value="<?= htmlspecialchars($startDate) ?>">

        <label for="endDate" class="form-label">Do:</label>
        <input type="date" id="endDate" name="end_date" class="form-control mb-3" value="<?= htmlspecialchars($endDate) ?>">
      </div>

      <button type="submit" class="btn btn-brownish w-100">Filtruj</button>
    </form>

    <h4 class="mt-5">Przychody:</h4>
    <?php if (!empty($incomes)): ?>
      <table class="table table-bordered table-dark">
        <thead>
          <tr>
            <th>Kategoria</th>
            <th>Kwota</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($incomes as $income): ?>
            <tr>
              <td><?= htmlspecialchars($income['category']) ?></td>
              <td><?= number_format($income['total'], 2, ',', ' ') ?> zł</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>Brak przychodów w wybranym okresie.</p>
    <?php endif; ?>

    <h4 class="mt-5">Wydatki:</h4>
    <?php if (!empty($expenses)): ?>
      <table class="table table-bordered table-dark">
        <thead>
          <tr>
            <th>Kategoria</th>
            <th>Kwota</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($expenses as $expense): ?>
            <tr>
              <td><?= htmlspecialchars($expense['category']) ?></td>
              <td><?= number_format($expense['total'], 2, ',', ' ') ?> zł</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>Brak wydatków w wybranym okresie.</p>
    <?php endif; ?>

    <h4 class="mt-5">Bilans:</h4>
    <p class="fs-4"><?= number_format($balance, 2, ',', ' ') ?> zł</p>
    <p class="fs-6 fw-bold">
      <?= $balance >= 0
        ? 'Gratulacje. Świetnie zarządzasz finansami!'
        : 'Uważaj, wpadasz w długi!' ?>
    </p>
  </div>
  </div>
  </div>


  <?php include $this->resolve("partials/_footer.php"); ?>