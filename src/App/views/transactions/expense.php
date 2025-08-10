<?php include $this->resolve("partials/_header.php"); ?>

<body>

  <?php include $this->resolve("partials/_navbar.php"); ?>

  <div class="small-container text-white rounded-3">
    <form class="form-wrapper" method="POST">

      <?php include $this->resolve("partials/_csrf.php"); ?>

      <!-- Kwota -->
      <div class="form-group row mb-4">
        <label for="amount" class="form-label input1">Kwota:</label>
        <input value="<?php echo e($oldFormData['amount'] ?? ''); ?>"
          type="number"
          class="form-control rounded-3 py-2 <?= isset($errors['amount']) ? 'is-invalid' : '' ?>"
          id="amount"
          name="amount"
          step="0.01"
          min="0"
          placeholder="Wpisz kwotę"
          title="<?= isset($errors['amount']) ? e($errors['amount'][0]) : ''; ?>">


      </div>

      <!-- Data -->
      <div class="form-group row mb-4">
        <label for="date" class="form-label input1">Data:</label>
        <input value="<?php echo e($oldFormData['date'] ?? ''); ?>"
          type="date"
          class="form-control rounded-3 py-2 <?= isset($errors['date']) ? 'is-invalid' : '' ?>"
          id="date"
          name="date"
          required
          title="<?= isset($errors['date']) ? e($errors['date'][0]) : ''; ?>">


      </div>

      <!-- Sposób płatności -->
      <div class="form-group row mb-4">
        <label for="category" class="form-label input1">Sposób płatności:</label>
        <select class="form-select rounded-3 py-2" id="payment" name="payment">
          <?php foreach ($paymentMethods as $method): ?>
            <option value="<?= e($method['name']) ?>" <?= (isset($oldFormData['payment']) && $oldFormData['payment'] === $method['name']) ? 'selected' : '' ?>>
              <?= e($method['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>


      <!-- Kategoria -->
      <div class="form-group row mb-4">
        <label for="category" class="form-label input1">Kategoria:</label>
        <select class="form-select rounded-3 py-2" id="category" name="category">
          <?php foreach ($expenseCategories as $category): ?>
            <option value="<?= e($category['name']) ?>" <?= (isset($oldFormData['category']) && $oldFormData['category'] === $category['name']) ? 'selected' : '' ?>>
              <?= e($category['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Tutaj wyświetlimy info o limicie kategorii -->
      <div id="category-limit-info" class="mb-4" style="font-weight: bold;"></div>

      <!-- Komentarz -->
      <div class="form-group row mb-4">
        <label for="comment" class="form-label input1">Komentarz:</label>
        <textarea class="form-control rounded-3 py-2" id="comment" name="comment" rows="3" placeholder="Dodaj komentarz..."></textarea>
      </div>

      <!-- Przyciski: Zapisz i Anuluj -->
      <div class="d-flex gap-3 justify-content-center">
        <button type="submit" class="btn btn-brown py-3 px-4 rounded-3">Zapisz</button>
        <a href="index.php" class="btn btn-brown py-3 px-4 rounded-3">Anuluj</a>
      </div>

    </form>

  </div>


  <script src="/assets/expense-limit.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

</body>

</html>