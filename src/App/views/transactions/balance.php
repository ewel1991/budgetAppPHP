<?php include $this->resolve("partials/_header.php"); ?>

<body>
  <?php include $this->resolve("partials/_navbar.php"); ?>

  <div class="centered-container rounded-3">
    <h2 class="text-white mb-4 text-center">Bilans przychodów i wydatków</h2>

    <form method="get" id="dateFilterForm" class="mb-4">
      <?php include $this->resolve("partials/_csrf.php"); ?>

      <label for="dateRange" class="form-label">Zakres dat:</label>
      <select id="dateRange" name="dateRange" class="form-select mb-3">
        <option value="current" <?= $dateRange === 'current' ? 'selected' : '' ?>>Bieżący miesiąc</option>
        <option value="previous" <?= $dateRange === 'previous' ? 'selected' : '' ?>>Poprzedni miesiąc</option>
        <option value="year" <?= $dateRange === 'year' ? 'selected' : '' ?>>Bieżący rok</option>
        <option value="custom" <?= $dateRange === 'custom' ? 'selected' : '' ?>>Niestandardowy</option>
      </select>

      <div id="customDateRange" style="<?= $dateRange === 'custom' ? 'display: block;' : 'display: none;' ?>">
        <label for="startDate" class="form-label">Od:</label>
        <input type="date" id="startDate" name="start_date" class="form-control mb-3" value="<?= htmlspecialchars($startDate ?? '') ?>">

        <label for="endDate" class="form-label">Do:</label>
        <input type="date" id="endDate" name="end_date" class="form-control mb-3" value="<?= htmlspecialchars($endDate ?? '') ?>">
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
              <td><?= number_format((float)$income['total'], 2, ',', ' ') ?> zł</td>
            </tr>
          <?php endforeach; ?>
          <tr class="table-success fw-bold">
            <td>Suma</td>
            <td><?= number_format($totalIncome, 2, ',', ' ') ?> zł</td>
          </tr>
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
              <td><?= number_format((float)$expense['total'], 2, ',', ' ') ?> zł</td>
            </tr>
          <?php endforeach; ?>
          <tr class="table-danger fw-bold">
            <td>Suma</td>
            <td><?= number_format($totalExpense, 2, ',', ' ') ?> zł</td>
          </tr>
        </tbody>
      </table>
    <?php else: ?>
      <p>Brak wydatków w wybranym okresie.</p>
    <?php endif; ?>

    <h4 class="mt-5">Bilans:</h4>
    <p class="fs-4">
      <?= number_format((float)$balance, 2, ',', ' ') ?> zł
    </p>
    <p class="fs-6 fw-bold">
      <?= $balance > 0
        ? 'Gratulacje. Świetnie zarządzasz finansami!'
        : ($balance < 0
          ? 'Uważaj, wpadasz w długi!'
          : 'Twój bilans wynosi 0 zł.') ?>
    </p>

    <?php if (!empty($advisorMessage)): ?>
      <div class="alert alert-info mt-3">
        💡 <strong>Porada doradcy finansowego:</strong><br>
        <?= htmlspecialchars($advisorMessage) ?>
      </div>
    <?php endif; ?>



    <div class="mt-2 text-center">
      <h4 class="mt-5">Procentowy udział przychodów i wydatków</h4>

      <!-- Kontener z danymi do wykresu -->
      <div
        id="chartData"
        data-total-income="<?= htmlspecialchars(array_sum(array_column($incomes, 'total'))) ?>"
        data-total-expense="<?= htmlspecialchars(array_sum(array_column($expenses, 'total'))) ?>"></div>

      <canvas id="incomeExpenseChart" width="300" height="300" style="display: block; margin: 0 auto;"></canvas>
    </div>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="/assets/balanceChart.js"></script>

</body>

</html>