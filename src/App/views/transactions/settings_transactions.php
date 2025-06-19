<?php include $this->resolve("partials/_header.php"); ?>

<body>

  <?php include $this->resolve("partials/_navbar.php"); ?>

  <div class="small-container text-white rounded-3">

    <!-- Dodaj kategorię przychodu -->
    <form method="POST" action="/income-categories/add">
      <?php include $this->resolve("partials/_csrf.php"); ?>

      <label for="income_category" class="form-label">Dodaj kategorię przychodu:</label>

      <div class="d-flex gap-2 align-items-stretch mb-3" style="max-width: 400px;">
        <input
          type="text"
          class="form-control"
          id="income_category"
          name="income_category"
          required
          placeholder="np. Premia">

        <button type="submit" class="btn btn-brownish px-4">Dodaj</button>
      </div>
    </form>

    <hr class="my-2">

    <!-- Usuń kategorię przychodu -->
    <form method="POST" action="/income-categories/delete" onsubmit="return confirm('Na pewno chcesz usunąć tę kategorię?');">
      <?php include $this->resolve("partials/_csrf.php"); ?>

      <label for="category" class="form-label">Usuń kategorię przychodu:</label>

      <div class="d-flex gap-2 align-items-stretch mb-3" style="max-width: 400px;">
        <select name="category" id="category" class="form-select" required>
          <option value="" disabled selected>-- Wybierz --</option>
          <?php foreach ($incomeCategories as $category): ?>
            <option value="<?= htmlspecialchars($category['name']) ?>">
              <?= htmlspecialchars($category['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-brownish px-4">Usuń</button>
      </div>
    </form>

    <hr class="my-2">

    <!-- Dodaj kategorię wydatku -->
    <form method="POST" action="/expense-categories/add">
      <?php include $this->resolve("partials/_csrf.php"); ?>

      <label for="expense_category" class="form-label">Dodaj kategorię wydatku:</label>

      <div class="d-flex gap-2 align-items-stretch mb-3" style="max-width: 400px;">
        <input
          type="text"
          class="form-control"
          id="expense_category"
          name="expense_category"
          required
          placeholder="np. Rachunki">

        <button type="submit" class="btn btn-brownish px-4">Dodaj</button>
      </div>
    </form>

    <hr class="my-2">

    <!-- Usuń kategorię wydatku -->
    <form method="POST" action="/expense-categories/delete" onsubmit="return confirm('Na pewno chcesz usunąć tę kategorię?');">
      <?php include $this->resolve("partials/_csrf.php"); ?>

      <label for="expense_category_select" class="form-label">Usuń kategorię wydatku:</label>

      <div class="d-flex gap-2 align-items-stretch mb-3" style="max-width: 400px;">
        <select name="category" id="expense_category_select" class="form-select" required>
          <option value="" disabled selected>-- Wybierz --</option>
          <?php foreach ($expenseCategories as $category): ?>
            <option value="<?= htmlspecialchars($category['name']) ?>">
              <?= htmlspecialchars($category['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-brownish px-4">Usuń</button>
      </div>
    </form>

    <hr class="my-2">

    <!-- Dodaj sposób płatności -->
    <form method="POST" action="/payment-categories/add">
      <?php include $this->resolve("partials/_csrf.php"); ?>

      <label for="payment_method" class="form-label">Dodaj sposób płatności:</label>

      <div class="d-flex gap-2 align-items-stretch mb-3" style="max-width: 400px;">
        <input
          type="text"
          class="form-control"
          id="payment_method"
          name="payment_method"
          required
          placeholder="np. Karta kredytowa">

        <button type="submit" class="btn btn-brownish px-4">Dodaj</button>
      </div>
    </form>

    <hr class="my-2">

    <!-- Usuń sposób płatności -->
    <form method="POST" action="/payment-categories/delete" onsubmit="return confirm('Na pewno chcesz usunąć ten sposób płatności?');">
      <?php include $this->resolve("partials/_csrf.php"); ?>

      <label for="payment_method_select" class="form-label">Usuń sposób płatności:</label>

      <div class="d-flex gap-2 align-items-stretch mb-3" style="max-width: 400px;">
        <select name="payment_method_id" id="payment_method_select" class="form-select" required>
          <option value="" disabled selected>-- Wybierz --</option>
          <?php foreach ($paymentMethods as $method): ?>
            <option value="<?= htmlspecialchars($method['id']) ?>">
              <?= htmlspecialchars($method['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-brownish px-4">Usuń</button>
      </div>
    </form>


    <hr class="my-2">

  </div>

  <?php include $this->resolve("partials/_footer.php"); ?>