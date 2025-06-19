<?php include $this->resolve("partials/_header.php"); ?>

<body>

  <?php include $this->resolve("partials/_navbar.php"); ?>

  <div class="small-container text-white rounded-3">

    <form class="form-wrapper" method="POST" action="/income">

      <?php include $this->resolve("partials/_csrf.php"); ?>

      <!-- Kwota -->
      <div class="form-group row mb-4">
        <label for="amount" class="form-label input1">Kwota:</label>
        <input
          value="<?php echo e($oldFormData['amount'] ?? ''); ?>"
          type="number"
          class="form-control rounded-3 py-2 <?= isset($errors['amount']) ? 'is-invalid' : '' ?>"
          id="amount"
          name="amount"
          step="0.01"
          min="0"
          placeholder="Wpisz kwotę"
          required
          title="<?= isset($errors['amount']) ? e($errors['amount'][0]) : ''; ?>">
      </div>


      <!-- Data -->
      <div class="form-group row mb-4">
        <label for="date" class="form-label input1">Data:</label>
        <input
          value="<?php echo e($oldFormData['date'] ?? ''); ?>"
          type="date"
          class="form-control rounded-3 py-2 <?= isset($errors['date']) ? 'is-invalid' : '' ?>"
          id="date"
          name="date"
          required
          title="<?= isset($errors['date']) ? e($errors['date'][0]) : '' ?>">
      </div>


      <!-- Kategoria -->
      <div class="form-group row mb-4">
        <label for="category" class="form-label input1">Kategoria:</label>


        <select name="category" id="category" class="form-control">
          <?php foreach ($incomeCategories as $category): ?>
            <option value="<?= htmlspecialchars($category['name']) ?>">
              <?= htmlspecialchars($category['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>




      </div>

      <!-- Komentarz -->
      <div class="form-group row mb-4">
        <label for="comment" class="form-label input1">Komentarz:</label>
        <textarea class="form-control rounded-3 py-2" id="comment" name="comment" rows="3" placeholder="Dodaj komentarz..."></textarea>
      </div>

      <!-- Przyciski: Zapisz i Anuluj -->

      <div class="row g-3 justify-content-center">
        <div class="col-12 col-sm-auto">
          <button type="submit" class="btn btn-brown py-3 px-4 rounded-3 w-100">Zapisz</button>
        </div>
        <div class="col-12 col-sm-auto">
          <a href="/transaction" class="btn btn-brown py-3 px-4 rounded-3 w-100">Anuluj</a>
        </div>
      </div>
    </form>
  </div>

  </div>

  <?php include $this->resolve("partials/_footer.php"); ?>